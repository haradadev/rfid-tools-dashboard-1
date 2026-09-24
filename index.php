<?php
session_start();

// Se estiver logado, vai para o painel; caso contrário, vai para o login.
if (isset($_SESSION['funcionario_id'])) {
    header("Location: pages/index_sistema.php");
} else {
    header("Location: pages/index_cadastro.php");
}
exit;
