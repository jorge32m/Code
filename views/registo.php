<?php
require "../api/auth.php"; // Inclui o ficheiro que contém as funções de autenticação e registo

$error_msg = false; // Inicializa variável para indicar se há erro
$msg = ""; // Inicializa mensagem de erro vazia

// Verifica se o método da requisição é POST e se todos os campos necessários estão definidos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["telemovel"]) && isset($_POST["nif"]) && isset($_POST["password"]) && isset($_POST["confirm_password"])) {

    // Verifica se o campo "username" está vazio e define erro e mensagem caso sim
    if (empty($_POST["username"])) {
        $error_msg = true;
        $msg .= "Preencha o campo username.";
    }

    // Verifica se o campo "email" está vazio e define erro e mensagem caso sim
    if (empty($_POST["email"])) {
        $error_msg = true;
        $msg .= "Preencha o campo email.";
    }

    // Verifica se o campo "telemovel" está vazio e define erro e mensagem caso sim
    if (empty($_POST["telemovel"])) {
        $error_msg = true;
        $msg .= "Preencha o campo telemovel.";
    }

    // Verifica se o campo "nif" está vazio e define erro e mensagem caso sim
    if (empty($_POST["nif"])) {
        $error_msg = true;
        $msg .= "Preencha o campo nif.";
    }

    // Verifica se o campo "password" está vazio e define erro e mensagem caso sim
    if (empty($_POST["password"])) {
        $error_msg = true;
        $msg .= "Preencha o campo password.";
    }

    // Verifica se o campo "confirm_password" está vazio e define erro e mensagem caso sim
    if (empty($_POST["confirm_password"])) {
        $error_msg = true;
        $msg .= "Preencha o campo confirmar password.";
    }

    // Verifica se as passwords não coincidem e define erro e mensagem caso sim
    if ($_POST["password"] != $_POST["confirm_password"]) {
        $error_msg = true;
        $msg .= "As passwords não coincidem.";
    }

    // Se não houver erro, tenta efetuar o registo com os dados fornecidos
    if (!$error_msg) {
        if (registo($_POST["email"], $_POST["username"], $_POST["password"], $_POST["telemovel"], $_POST["nif"])) {
            // Se o registo for bem sucedido, redireciona para a página de login
            header("Location: login.php");
        } else {
            // Se falhar o registo, define erro e mensagem de falha
            $error_msg = true;
            $msg = "O registo falhou. Verifique os seus dados.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registo</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

    <?php
    // Exibe a mensagem de erro na página caso exista algum erro
    if ($error_msg) {
        echo "<div class='position-fixed top-0 end-0 p-3' style='z-index: 1050;'>
                  <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                      $msg
                      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>
              </div>";
    }
    ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="h4 text-center mb-4">Registo</h1>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nome de utilizador:</label>
                                <input type="text" id="username" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="telemovel" class="form-label">Telemóvel:</label>
                                <input type="text" id="telemovel" name="telemovel" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="nif" class="form-label">NIF:</label>
                                <input type="text" id="nif" name="nif" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirmar Password:</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Registar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (optional, for interactive components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
