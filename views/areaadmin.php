<?php
// Inicia sessão e verifica se o utilizador é administrador
session_start();
require '../api/auth.php';
if (!isAdmin()) {
    header("Location: ../index.php");
    exit();
}

// Conecta à base de dados e busca os produtos
require_once '../api/db.php';
$stmt = $con->prepare("SELECT id, nome, preco, descricao, imagem FROM produto ORDER BY id DESC");
$stmt->execute();
$result = $stmt->get_result();
$produtos = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$con->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Administração de Produtos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Importa Bootstrap e ícones -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">Administração</a>
        </div>
    </nav>

    <!-- Conteúdo principal -->
    <div class="container">
        <!-- Título e botão para inserir novo produto -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 fw-bold">Área de administração</h1>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#insertProductModal">
                <i class="bi bi-plus-circle"></i> Inserir Novo Produto
            </button>
        </div>

        <!-- Tabela de produtos -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h2 class="h5 mb-0">Produtos</h2>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Preço</th>
                                <th>Descrição</th>
                                <th>Imagem</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produtos as $produto): ?>
                                <tr>
                                    <!-- Exibe os dados do produto -->
                                    <td><?= htmlspecialchars($produto['id']) ?></td>
                                    <td><?= htmlspecialchars($produto['nome']) ?></td>
                                    <td><span class="badge bg-success"><?= number_format($produto['preco'], 2, ',', '.') ?> €</span></td>
                                    <td><?= htmlspecialchars($produto['descricao']) ?></td>
                                    <td>
                                        <?php if (!empty($produto['imagem'])): ?>
                                            <!-- Exibe imagem do produto -->
                                            <img src="data:image/jpeg;base64,<?= base64_encode($produto['imagem']) ?>" class="img-thumbnail" style="width: 80px;">
                                        <?php else: ?>
                                            <span class="text-muted">Sem imagem</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <!-- Botão para eliminar produto -->
                                        <button class="btn btn-danger btn-sm me-1" title="Eliminar"
                                            onclick="if(confirm('Tem a certeza?')) {
                                                fetch('../api/admin/delete_product.php?id=<?= $produto['id'] ?>')
                                                    .then(r => r.json())
                                                    .then(result => {
                                                        if(result.status === 'success') location.reload();
                                                        else alert(result.message || 'Erro ao eliminar.');
                                                    });
                                            }">
                                            <i class="bi bi-trash"></i>
                                        </button>

                                        <!-- Botão para abrir modal de edição -->
                                        <button class="btn btn-warning btn-sm" title="Editar"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editProductModal"
                                            data-id="<?= htmlspecialchars($produto['id']) ?>"
                                            data-nome="<?= htmlspecialchars($produto['nome']) ?>"
                                            data-preco="<?= htmlspecialchars($produto['preco']) ?>"
                                            data-descricao="<?= htmlspecialchars($produto['descricao']) ?>">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de edição -->
    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" method="post" enctype="multipart/form-data" id="editProductForm" action="../api/admin/edit_product.php">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Editar Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Campos para editar -->
                    <input type="hidden" name="id" id="editProductId">
                    <div class="mb-3">
                        <label for="editProductName" class="form-label">Nome</label>
                        <input type="text" class="form-control" name="nome" id="editProductName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editProductPrice" class="form-label">Preço</label>
                        <input type="number" step="0.01" class="form-control" name="preco" id="editProductPrice" required>
                    </div>
                    <div class="mb-3">
                        <label for="editProductDescription" class="form-label">Descrição</label>
                        <textarea class="form-control" name="descricao" id="editProductDescription" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editProductImage" class="form-label">Imagem (opcional)</label>
                        <input type="file" class="form-control" name="imagem" id="editProductImage">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de inserção -->
    <div class="modal fade" id="insertProductModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" method="post" enctype="multipart/form-data" id="insertProductForm" action="../api/admin/insert_product.php">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Inserir Produto</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Campos para inserção -->
                    <div class="mb-3">
                        <label for="productName" class="form-label">Nome</label>
                        <input type="text" class="form-control" name="nome" id="productName" required>
                    </div>
                    <div class="mb-3">
                        <label for="productPrice" class="form-label">Preço</label>
                        <input type="number" step="0.01" class="form-control" name="preco" id="productPrice" required>
                    </div>
                    <div class="mb-3">
                        <label for="productDescription" class="form-label">Descrição</label>
                        <textarea class="form-control" name="descricao" id="productDescription" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="productImage" class="form-label">Imagem</label>
                        <input type="file" class="form-control" name="imagem" id="productImage" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Inserir</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast (mensagem rápida de feedback) -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055">
        <div id="feedbackToast" class="toast align-items-center border-0 text-bg-primary" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage"></div>
                <button type="button" class="btn-close btn-close-white m-auto me-2" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <!-- Scripts Bootstrap + JavaScript da página -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // Preenche os dados do modal de edição ao abrir
        const editModal = document.getElementById('editProductModal');
        editModal.addEventListener('show.bs.modal', e => {
            const btn = e.relatedTarget;
            document.getElementById('editProductId').value = btn.dataset.id;
            document.getElementById('editProductName').value = btn.dataset.nome;
            document.getElementById('editProductPrice').value = btn.dataset.preco;
            document.getElementById('editProductDescription').value = btn.dataset.descricao;
            document.getElementById('editProductImage').value = '';
        });

        // Função para mostrar mensagens de feedback
        const showToast = (msg, type = 'success') => {
            const toast = document.getElementById('feedbackToast');
            const toastMsg = document.getElementById('toastMessage');
            toastMsg.textContent = msg;
            toast.className = `toast text-bg-${type} show`;
            new bootstrap.Toast(toast).show();
        };

        // Função para tratar submissões AJAX dos formulários
        const handleSubmit = (form) => {
            form.addEventListener('submit', async e => {
                e.preventDefault();
                const formData = new FormData(form);
                const response = await fetch(form.action, { method: 'POST', body: formData });
                const result = await response.json();
                showToast(result.message || 'Operação concluída.', result.status === 'success' ? 'success' : 'danger');
                if (result.status === 'success') {
                    const modal = bootstrap.Modal.getInstance(form.closest('.modal'));
                    modal.hide();
                    setTimeout(() => location.reload(), 1000);
                }
            });
        };

        // Aplica a função aos dois formulários
        handleSubmit(document.getElementById('editProductForm'));
        handleSubmit(document.getElementById('insertProductForm'));
    });
    </script>
</body>
</html>
