<?php
// Inclui o ficheiro de autenticação que contém a função ativarConta()
require "../api/auth.php";

// Verifica se os parâmetros "email" e "token" foram passados pela URL
if (isset($_GET["email"]) && isset($_GET["token"])) {
    // Chama a função para ativar a conta com os dados recebidos
    ativarConta($_GET["email"], $_GET["token"]);
    
    // Redireciona o utilizador para a página de login após ativação
    header("Location: login.php");
    exit();
}
?>