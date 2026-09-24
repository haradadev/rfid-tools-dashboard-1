<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function voltarParaLogin(string $mensagem, string $status = 'erro'): void
{
    $_SESSION['msg_login'] = $mensagem;
    $_SESSION['status_login'] = $status;
    header('Location: ../pages/index_cadastro.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    voltarParaLogin('Requisição inválida.');
}

require_once __DIR__ . '/../config/conexao.php';

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if ($email === '' || $senha === '') {

    voltarParaLogin('Preencha o email e a senha.');
}


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    voltarParaLogin('Informe um email válido.');
}


try {

    // =====================================================
    // BUSCAR FUNCIONÁRIO
    // =====================================================

    $sql = "
        SELECT
            id,
            nome,
            matricula,
            departamento,
            email,
            ativo,
            criado_em,
            senha_hash
        FROM funcionarios
        WHERE email = :email
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':email' => $email
    ]);

    $funcionario = $stmt->fetch();


    // =====================================================
    // VERIFICAR SE O FUNCIONÁRIO EXISTE
    // =====================================================

    if (!$funcionario) {

        voltarParaLogin('Email ou senha incorretos.');
    }


    // =====================================================
    // VERIFICAR SE O USUÁRIO ESTÁ ATIVO
    // =====================================================

    if ((int) $funcionario['ativo'] !== 1) {

        voltarParaLogin('Este funcionário está com o acesso desativado.');
    }


    // =====================================================
    // VERIFICAR SENHA
    // =====================================================

    if (!password_verify($senha, $funcionario['senha_hash'])) {
        voltarParaLogin('Email ou senha incorretos.');
    }


    // =====================================================
    // LOGIN AUTENTICADO
    // =====================================================

    session_regenerate_id(true);


    $_SESSION['logado'] = true;

    $_SESSION['funcionario_id'] = $funcionario['id'];
    $_SESSION['funcionario_nome'] = $funcionario['nome'];
    $_SESSION['funcionario_matricula'] = $funcionario['matricula'];
    $_SESSION['funcionario_departamento'] = $funcionario['departamento'];
    $_SESSION['funcionario_email'] = $funcionario['email'];


    // =====================================================
    // REDIRECIONAR PARA O SISTEMA
    // =====================================================

    header('Location: ../pages/index_sistema.php');
    exit;


} catch (PDOException $e) {

    // Registrar o erro no log do servidor.
    error_log(
        'Erro no login RFID Tools: ' . $e->getMessage()
    );


    voltarParaLogin('Não foi possível realizar o login. Tente novamente.');
}
