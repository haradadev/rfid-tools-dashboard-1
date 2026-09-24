<?php

// Inicia a sessão somente se ela ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se os dados vieram pelo formulário
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/index_cadastro.php');
    exit;
}

// Conecta ao banco (agora só existe $pdo — nada de $conn/mysqli)
require __DIR__ . '/conexao.php';

// Recebe os dados do formulário
$email = trim($_POST['email'] ?? $_POST['Email'] ?? '');
$senha = $_POST['senha'] ?? $_POST['Senha'] ?? '';

// Verifica se os campos estão preenchidos
if (empty($email) || empty($senha)) {
    $_SESSION['msg_login'] = 'Preencha o email e a senha!';
    $_SESSION['status_login'] = 'erro';

    header('Location: ../pages/index_cadastro.php');
    exit;
}

try {
    // Busca o funcionário pelo email
    $stmt = $pdo->prepare('SELECT id, nome, senha_hash, ativo FROM funcionarios WHERE email = ?');
    $stmt->execute([$email]);
    $funcionario = $stmt->fetch();

    // password_verify compara a senha digitada com o hash salvo no cadastro
    if ($funcionario && $funcionario['ativo'] && password_verify($senha, $funcionario['senha_hash'])) {

        // Regenera o ID de sessão por segurança (evita fixação de sessão)
        session_regenerate_id(true);

        $_SESSION['funcionario_id'] = $funcionario['id'];
        $_SESSION['funcionario_nome'] = $funcionario['nome'];
        $_SESSION['logado'] = true;
        $_SESSION['msg_login'] = 'Login realizado com sucesso!';
        $_SESSION['status_login'] = 'sucesso';

        header('Location: ../pages/index_sistema.php');
        exit;

    } else {

        $_SESSION['msg_login'] = 'Email ou senha incorretos!';
        $_SESSION['status_login'] = 'erro';

        header('Location: ../pages/index_cadastro.php');
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['msg_login'] = 'Erro ao consultar o banco de dados.';
    $_SESSION['status_login'] = 'erro';

    header('Location: ../pages/index_cadastro.php');
    exit;
}
