<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Só aceita requisições vindas de um POST (do formulário)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/cadastro_funcionario.php');
    exit;
}

require __DIR__ . '/../config/conexao.php';

// Pega os dados do formulário e remove espaços extras
$nome         = trim($_POST['nome'] ?? '');
$email        = trim($_POST['email'] ?? '');
$matricula    = trim($_POST['matricula'] ?? '');
$departamento = trim($_POST['departamento'] ?? '');
$tagRfid      = trim($_POST['tag_rfid'] ?? '');
$senha        = $_POST['senha'] ?? '';

// ===== VALIDAÇÕES BÁSICAS =====
if ($nome === '' || $email === '' || $matricula === '' || $departamento === '' || $tagRfid === '' || $senha === '') {
    $_SESSION['msg_func'] = 'Preencha todos os campos.';
    $_SESSION['status_func'] = 'erro';
    header('Location: ../pages/cadastro_funcionario.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['msg_func'] = 'Digite um email válido.';
    $_SESSION['status_func'] = 'erro';
    header('Location: ../pages/cadastro_funcionario.php');
    exit;
}

if (strlen($senha) < 6) {
    $_SESSION['msg_func'] = 'A senha precisa ter no mínimo 6 caracteres.';
    $_SESSION['status_func'] = 'erro';
    header('Location: ../pages/cadastro_funcionario.php');
    exit;
}

try {
    // Verifica se email, matrícula ou tag RFID já existem (evita duplicados)
    $verifica = $pdo->prepare('SELECT id FROM funcionarios WHERE email = ? OR matricula = ? OR tag_rfid = ?');
    $verifica->execute([$email, $matricula, $tagRfid]);

    if ($verifica->fetch()) {
        $_SESSION['msg_func'] = 'Já existe um funcionário com esse email, matrícula ou tag RFID.';
        $_SESSION['status_func'] = 'erro';
        header('Location: ../pages/cadastro_funcionario.php');
        exit;
    }

    // Nunca salve a senha em texto puro — sempre use password_hash
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $inserir = $pdo->prepare('
        INSERT INTO funcionarios (nome, email, matricula, departamento, tag_rfid, senha_hash, cargo, ativo)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ');
    $inserir->execute([$nome, $email, $matricula, $departamento, $tagRfid, $senhaHash, 'operador', 1]);

    $_SESSION['msg_func'] = 'Funcionário cadastrado com sucesso! Já pode fazer login.';
    $_SESSION['status_func'] = 'sucesso';
} catch (PDOException $e) {
    $_SESSION['msg_func'] = 'Erro ao cadastrar. Tente novamente.';
    $_SESSION['status_func'] = 'erro';
    // Em desenvolvimento, você pode logar o erro real assim:
    // error_log($e->getMessage());
}

header('Location: ../pages/cadastro_funcionario.php');
exit;
