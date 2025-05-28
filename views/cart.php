<?php

// Inclui o arquivo de autenticação para garantir que o usuário esteja autenticado
require '../api/auth.php';

// Inicia a sessão para manipular variáveis de sessão
session_start();

// Verifica se o usuário está logado; caso contrário, redireciona para a página de login
if(!isset($_SESSION["user"])){
    header("Location: views/login.php");
    exit();
}

// Inclui o arquivo de conexão com o banco de dados
require '../api/db.php';

// Prepara uma consulta SQL para obter os produtos no carrinho do usuário logado
$sql = $con->prepare("SELECT p.id, p.nome, p.descricao, p.preco, p.imagem, c.quantidade FROM produto p JOIN Carrinho c ON p.id = c.produtoId WHERE c.userId = ?");

// Associa o ID do usuário logado ao parâmetro da consulta
$sql->bind_param("i", $_SESSION["user"]["id"]);

// Executa a consulta
$sql->execute();

// Obtém o resultado da consulta
$result = $sql->get_result();

// Inicializa um array para armazenar os produtos do carrinho
$carrinho = [];

// Loop para adicionar cada linha do resultado ao array do carrinho
while($row = $result->fetch_assoc()) {
    $carrinho[] = $row;
}

// Variável para armazenar o Client ID do PayPal (deve ser preenchida)
$PAYPAL_CLIENT_ID = ""; // coloque seu Client ID do PayPal aqui

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Carrinho de Compras - Loja JOHN DOE</title>
    <!-- Link para o CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        /* Estilos para o item do carrinho */
        .cart-item {
            border: 1px solid #e3e3e3;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }
        /* Imagem do produto com tamanho fixo e borda arredondada */
        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
        /* Layout dos botões de ação no carrinho */
        .cart-actions {
            display: flex;
            gap: 10px;
        }
        /* Estilo do rodapé fixo */
        footer {
            background-color: #0d6efd; /* cor primária do Bootstrap */
            color: white;
            padding: 15px 0;
            text-align: center;
            position: fixed;
            width: 100%;
            bottom: 0;
            left: 0;
            box-shadow: 0 -2px 8px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        /* Espaço para o conteúdo não ficar atrás do footer */
        body {
            padding-bottom: 60px;
            background-color: #f8f9fa;
        }
        /* Botão sair no topo direito */
        .logout-btn {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 1100;
        }
        /* Botão voltar para index no topo direito, antes do logout */
        .back-index-btn {
            position: fixed;
            top: 10px;
            right: 90px; /* deslocado para não ficar junto do logout */
            z-index: 1100;
        }
    </style>
</head>
<body>

    <!-- Botão Voltar para a página principal -->
    <a href="../index.php" title="Voltar ao índice" class="btn btn-outline-primary back-index-btn d-flex align-items-center gap-1">
        <!-- Ícone carrinho -->
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
          <path d="M0 1.5A.5.5 0 0 1 .5 1h1a.5.5 0 0 1 .485.379L2.89 5H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 14H4a.5.5 0 0 1-.491-.408L1.01 2H.5a.5.5 0 0 1-.5-.5zm3.14 4l1.25 6.25a.5.5 0 0 0 .491.41h7.348a.5.5 0 0 0 .49-.408L14.89 6H3.14z"/>
          <path d="M5.5 12a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm6 1a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
        </svg>
        Votar aos produtos
    </a>

    <!-- Botão Sair -->
    <a href="../logout.php" title="Sair" class="btn btn-outline-danger logout-btn d-flex align-items-center gap-1">
        <!-- Ícone de saída -->
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M10 15a1 1 0 0 0 1-1v-2h-1v2H4V3h6v2h1V4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h6z"/>
          <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
        </svg>
        Sair
    </a>
    
    <div class="container mt-5">
        <h2 class="mb-4">Carrinho de Compras - Loja JOHN DOE</h2>
        <!-- Mensagem caso o carrinho esteja vazio -->
        <?php if (count($carrinho) === 0): ?>
            <div class="alert alert-info">O seu carrinho está vazio.</div>
        <?php endif; ?>
        <div class="row">
            <!-- Loop para mostrar cada item do carrinho -->
            <?php foreach ($carrinho as $item): ?>
                <div class="col-md-12 cart-item d-flex align-items-center">
                    <?php 
                        // Codifica a imagem do produto para base64 para exibição inline
                        $image = base64_encode($item['imagem']);
                        $src = 'data:image/jpeg;base64,' . $image;
                    ?>
                    <div class="me-4">
                        <!-- Exibe a imagem do produto -->
                        <img src="<?php echo $src ?>" alt="Imagem do produto <?php echo htmlspecialchars($item['nome']); ?>">
                    </div>
                    <div class="flex-grow-1">
                        <!-- Nome do produto -->
                        <h5><?php echo htmlspecialchars($item['nome']); ?></h5>
                        <!-- Descrição do produto -->
                        <p class="mb-1 text-muted"><?php echo htmlspecialchars($item['descricao']); ?></p>
                        <!-- Preço do produto formatado -->
                        <div class="fw-bold mb-2"><?php echo number_format($item['preco'], 2, ',', '.'); ?> €</div>
                        <div class="cart-actions">
                            <!-- Formulário para atualizar a quantidade do produto no carrinho -->
                            <form action="../api/update_cart.php" method="post" class="d-flex align-items-center gap-2">
                                <input type="hidden" name="produtoId" value="<?php echo $item['id']; ?>">
                                <input type="number" name="quantidade" value="<?php echo $item['quantidade']; ?>" min="1" class="form-control form-control-sm" style="width: 70px;">
                                <button type="submit" class="btn btn-primary btn-sm">Atualizar</button>
                            </form>
                            <!-- Formulário para remover o produto do carrinho -->
                            <form action="../api/delete_cart.php" method="post">
                                <input type="hidden" name="produtoId" value="<?php echo $item['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Remover</button>
                            </form>
                        </div>
                    </div>
                    <div class="ms-auto text-center">
                        <!-- Exibe o subtotal do item (quantidade x preço) -->
                        <span class="badge bg-secondary fs-6">Subtotal: <?php echo number_format($item["quantidade"] * $item['preco'], 2, ',', '.'); ?> €</span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php
            // Calcula o total do pedido somando os subtotais dos produtos
            $total = 0;
            foreach($carrinho as $item) {
                $total += $item["quantidade"] * $item["preco"];
            }
        ?>

        <!-- Mostra o total do pedido se maior que zero -->
        <?php if ($total > 0): ?>
            <div class="d-flex justify-content-end mt-4">
                <h4>Total do Pedido: <span class="badge bg-success"><?php echo number_format($total, 2, ',', '.'); ?> €</span></h4>
            </div>
        <?php endif; ?>
    </div>

    <!-- Container para o botão do PayPal -->
    <div class="d-flex justify-content-center my-4">
        <div id="paypal-button-container" class="w-50"></div>
    </div>

    <!-- Script do SDK do PayPal, inserindo o client-id dinâmico -->
    <script src="<?php echo "https://www.paypal.com/sdk/js?client-id=$PAYPAL_CLIENT_ID&currency=EUR" ?>"></script>

    <script>
        paypal.Buttons({
            // Cria a ordem de pagamento com o valor total formatado no padrão internacional (ponto decimal)
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '<?php echo number_format($total, 2, '.', ''); ?>'
                        }
                    }]
                });
            },
            // Ao aprovar o pagamento, redireciona para página de finalização
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    window.location.href = "finish.php";
                });
            },
            // Tratamento de erro no pagamento
            onError: function(err) {
                console.error('Erro no pagamento:', err);
                alert('Ocorreu um erro durante o pagamento. Tente novamente.');
            }
        }).render('#paypal-button-container');
    </script>

    <!-- Rodapé fixo -->
    <footer>
        &copy; <?php echo date("Y"); ?> Loja JOHN DOE - Todos os direitos reservados
    </footer>

    <!-- Script JS do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
