<?php
// Inclua este arquivo no topo de qualquer página que só pode ser vista
// por quem já fez login (ex: index_sistema.php).

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['funcionario_id'])) {
    $_SESSION['msg_login'] = 'Faça login para acessar o sistema.';
    $_SESSION['status_login'] = 'erro';
    header('Location: index_cadastro.php');
    exit;
}
