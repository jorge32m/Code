<?php
    // Redireciona o utilizador para a página de login
    header("Location: login.php");
    
    // Encerra a sessão atual, removendo todos os dados de sessão
    session_destroy();
    
    // Termina a execução do script para garantir que o redirecionamento ocorra imediatamente
    exit();
?>