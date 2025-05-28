<?php
// Inclui o arquivo de autenticação para garantir que o usuário esteja autenticado
require 'api/auth.php';

// Inicia a sessão para usar variáveis de sessão
session_start();

// Verifica se o usuário está logado; se não, redireciona para a página de login
if(!isset($_SESSION["user"])){
    header("Location: views/login.php");
    exit();
}

// Inclui o arquivo de conexão com o banco de dados
require 'api/db.php';

// IF ternário para verificar se existe parâmetro 'search' na URL; 
// caso exista, escapa a string para evitar SQL Injection, senão fica vazio
$search = isset($_GET['search']) ? $con->real_escape_string($_GET['search']) : '';

// Consulta básica para selecionar os produtos
$sql = "SELECT id, nome, descricao, preco, imagem FROM produto";

// Se houver termo de busca, adiciona cláusula WHERE para filtrar por nome ou descrição
if ($search !== '') {
    $sql .= " WHERE nome LIKE '%$search%' OR descricao LIKE '%$search%'";
}

// Executa a consulta no banco de dados
$result = $con->query($sql);

// Inicializa um array para armazenar os produtos retornados
$produtos = [];

// Se a consulta retornou resultados, adiciona cada produto ao array
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $produtos[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Loja de Compras John Doe</title>
    <!-- Link para o CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<!-- Barra de navegação -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">Loja John Doe</a>
        <!-- Botão para colapsar menu em telas pequenas -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
            aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar navegação">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Itens do menu -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if(isAdmin()){ ?>
                    <!-- Link para área administrativa, mostrado apenas para admins -->
                    <li class="nav-item">
                        <a class="nav-link" href="views/areaadmin.php">Área de administração</a>
                    </li>
                <?php } ?>
                <!-- Link para logout -->
                <li class="nav-item">
                    <a class="nav-link" href="views/logout.php">Logout</a>
                </li>
                <!-- Link para carrinho -->
                <li class="nav-item">
                    <a class="nav-link" href="views/cart.php" title="Carrinho">
                        <!-- Ícone SVG do carrinho -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" 
                            class="bi bi-cart" viewBox="0 0 16 16" style="margin-right: 4px;">
                            <path d="M0 1.5A.5.5 0 0 1 .5 1h1a.5.5 0 0 1 .485.379L2.89 5H14.5a.5.5 0 0 1 
                                .491.592l-1.5 8A.5.5 0 0 1 13 14H4a.5.5 0 0 1-.491-.408L1.01 2H.5a.5.5 0 0 1-
                                .5-.5zm3.14 4l1.25 6.5h7.22l1.25-6.5H3.14zM5.5 16a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm7 
                                0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                        </svg>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">

    <!-- Formulário de busca de produtos -->
    <form class="row mb-4" method="get" action="">
        <div class="col-md-10">
            <!-- Campo de texto para pesquisa, mantendo o valor digitado -->
            <input type="text" class="form-control" name="search" placeholder="Pesquisar produtos..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        </div>
        <div class="col-md-2">
            <!-- Botão para enviar o formulário -->
            <button type="submit" class="btn btn-primary w-100">Buscar</button>
        </div>
    </form>

    <div class="row g-4">
    <!-- Loop para mostrar os produtos retornados -->
    <?php foreach ($produtos as $produto): ?>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm">
                <?php
                // Se houver imagem no banco, converte para base64 para mostrar inline
                if (!empty($produto['imagem'])) {
                    $imgData = base64_encode($produto['imagem']);
                    $src = 'data:image/jpeg;base64,' . $imgData;
                } else {
                    // Caso não tenha imagem, mostra imagem placeholder
                    $src = 'https://via.placeholder.com/300x180?text=Sem+Imagem';
                }
                ?>
                <!-- Imagem do produto -->
                <img src="<?php echo $src; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($produto['nome']); ?>" style="height: 180px; object-fit: cover;">
                <div class="card-body d-flex flex-column">
                    <!-- Nome do produto -->
                    <h5 class="card-title"><?php echo htmlspecialchars($produto['nome']); ?></h5>
                    <!-- Descrição do produto -->
                    <p class="card-text"><?php echo htmlspecialchars($produto['descricao']); ?></p>
                    <div class="mt-auto">
                        <!-- Preço formatado -->
                        <strong class="text-success">€<?php echo number_format($produto['preco'], 2, ',', '.'); ?></strong>
                        <!-- Formulário para adicionar o produto ao carrinho -->
                        <form method="post" action="api/add_to_cart.php" class="mt-3 d-flex align-items-center gap-2">
                            <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                            <!-- Quantidade padrão 1, mínima 1 -->
                            <input type="number" name="quantidade" value="1" min="1" class="form-control form-control-sm" style="width: 70px;">
                            <!-- Botão para adicionar -->
                            <button type="submit" class="btn btn-outline-primary btn-sm">Adicionar ao carrinho</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>

</div>

<!-- Rodapé simples -->
<footer class="bg-primary text-white text-center py-3 mt-4">
    &copy; <?php echo date('Y'); ?> Loja de Compras John Doe. Todos os direitos reservados.
</footer>

<!-- Script JS do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
