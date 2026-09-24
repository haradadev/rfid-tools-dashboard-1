<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

// Só funciona para quem está logado
if (empty($_SESSION['funcionario_id'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'mensagem' => 'Sessão expirada. Faça login novamente.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'mensagem' => 'Método não permitido.']);
    exit;
}

require __DIR__ . '/../config/conexao.php';

$tagRfid = trim($_POST['tag_rfid'] ?? '');
$funcionarioId = $_SESSION['funcionario_id'];

if ($tagRfid === '') {
    echo json_encode(['ok' => false, 'mensagem' => 'Nenhuma tag foi lida.']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Trava a linha da ferramenta durante a transação (evita duas leituras simultâneas conflitarem)
    $stmt = $pdo->prepare('SELECT id, nome, status FROM ferramentas WHERE tag_rfid = ? FOR UPDATE');
    $stmt->execute([$tagRfid]);
    $ferramenta = $stmt->fetch();

    if (!$ferramenta) {
        $pdo->rollBack();
        echo json_encode(['ok' => false, 'mensagem' => "Nenhuma ferramenta cadastrada com a tag \"$tagRfid\"."]);
        exit;
    }

    if ($ferramenta['status'] === 'manutencao') {
        $pdo->rollBack();
        echo json_encode(['ok' => false, 'mensagem' => $ferramenta['nome'] . ' está em manutenção e não pode ser retirada.']);
        exit;
    }

    // Alterna: disponível -> empréstimo | emprestada -> devolução
    if ($ferramenta['status'] === 'disponivel') {
        $novoStatus = 'emprestada';
        $tipoMovimentacao = 'emprestimo';
    } else {
        $novoStatus = 'disponivel';
        $tipoMovimentacao = 'devolucao';
    }

    $pdo->prepare('UPDATE ferramentas SET status = ? WHERE id = ?')
        ->execute([$novoStatus, $ferramenta['id']]);

    $pdo->prepare('INSERT INTO movimentacoes (ferramenta_id, funcionario_id, tipo) VALUES (?, ?, ?)')
        ->execute([$ferramenta['id'], $funcionarioId, $tipoMovimentacao]);

    $pdo->commit();

    echo json_encode([
        'ok' => true,
        'tipo' => $tipoMovimentacao,
        'ferramenta' => $ferramenta['nome'],
        'novo_status' => $novoStatus,
        'ferramenta_id' => $ferramenta['id'],
        'funcionario' => $_SESSION['funcionario_nome'],
        'mensagem' => $tipoMovimentacao === 'emprestimo'
            ? $ferramenta['nome'] . ' retirada com sucesso.'
            : $ferramenta['nome'] . ' devolvida com sucesso.',
    ]);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['ok' => false, 'mensagem' => 'Erro ao registrar a movimentação.']);
}
