<?php

session_start();

require "../api/auth.php";

$error_msg = false;
$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    if (empty($username) || empty($password)) {
        $error_msg = true;
        $msg = "Preencha todos os campos";
    } else {

        if (login($username, $password)) {
            header("Location: ../index.php");
        } else {
            $error_msg = true;
            $msg = "O login falhou. Verifique o seu username e password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Loja John Doe</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100 bg-light">

    <?php
    if ($error_msg) {
        echo "<div class='position-fixed top-0 end-0 p-3' style='z-index: 1050;'>
                  <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                      $msg
                      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>
              </div>";
    }
    ?>

    <!-- Header -->
    <header class="bg-primary text-white text-center py-4">
        <h1 class="mb-0">Bem-vindo à loja de compras John Doe</h1>
    </header>

    <!-- Formulário -->
    <main class="flex-grow-1 d-flex align-items-center justify-content-center py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h2 class="h4 text-center mb-4">Login</h2>
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username:</label>
                                    <input type="text" id="username" name="username" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password:</label>
                                    <input type="password" id="password" name="password" class="form-control" required>
                                </div>
                                <div class="d-grid">
                                    <input type="submit" value="Login" class="btn btn-primary">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-light text-center text-lg-start text-muted border-top mt-auto pt-4">
        <div class="container text-center text-md-start">
            <div class="row">
                <!-- Loja -->
                <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                    <h6 class="text-uppercase fw-bold mb-4">Loja John Doe</h6>
                    <p>A melhor loja online para as suas compras do dia-a-dia.</p>
                </div>

                <!-- Links úteis -->
                <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                    <h6 class="text-uppercase fw-bold mb-4">Links úteis</h6>
                    <p><a href="#" class="text-reset">Contactos</a></p>
                    <p><a href="#" class="text-reset">Termos</a></p>
                    <p><a href="#" class="text-reset">Ajuda</a></p>
                </div>

                <!-- Idioma -->
                <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                    <h6 class="text-uppercase fw-bold mb-4">Idioma</h6>
                    <p>Português (PT)</p>
                </div>

                <!-- Redes sociais -->
                <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                    <h6 class="text-uppercase fw-bold mb-4">Siga-nos</h6>
                    <a href="#" class="me-3 text-reset"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="me-3 text-reset"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="me-3 text-reset"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="me-3 text-reset"><i class="bi bi-envelope"></i></a>
                </div>
            </div>
        </div>

        <div class="text-center p-3 bg-light text-muted border-top mt-4">
            &copy; <?= date("Y") ?> Loja John Doe. Todos os direitos reservados.
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
