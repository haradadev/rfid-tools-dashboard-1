<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['funcionario_id'])) {
    header('Location: ../pages/index_cadastro.php');
    exit;
}

require_once __DIR__ . '/../config/conexao.php';

try {

    $sql = "
        SELECT
            id,
            nome,
            matricula,
            departamento,
            email,
            ativo,
            criado_em
        FROM funcionarios
        ORDER BY id DESC
    ";

    $stmt = $pdo->query($sql);

    $funcionarios = $stmt->fetchAll();

} catch (PDOException $e) {

    error_log(
        'Erro ao listar funcionários: ' . $e->getMessage()
    );

    $funcionarios = [];

    $erroBanco = 'Não foi possível carregar os funcionários.';

}


$nomeUsuario = $_SESSION['funcionario_nome'] ?? 'Usuário';
$departamentoUsuario = $_SESSION['funcionario_departamento'] ?? '';

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Funcionários · RFID Tools</title>


    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Alata&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="../pages/style_sistema.css"
    >

</head>


<body>

    <header class="topbar">

        <div class="marca">

            <h1>RFID TOOLS</h1>

            <span>
                Sistema de Monitoramento
            </span>

        </div>


        <div class="usuario">

            <div class="usuario-info">

                <strong>
                    <?= htmlspecialchars($nomeUsuario) ?>
                </strong>

                <?php if ($departamentoUsuario): ?>

                    <span>
                        <?= htmlspecialchars($departamentoUsuario) ?>
                    </span>

                <?php endif; ?>

            </div>


            <a
                href="../controller/logout.php"
                class="botao-sair"
            >
                Sair
            </a>

        </div>

    </header>

    <main class="painel">

        <section class="cabecalho-pagina">

            <div>

                <span class="etiqueta-pagina">
                    ADMINISTRAÇÃO
                </span>

                <h2>
                    Funcionários cadastrados
                </h2>

                <p>
                    Gerencie os usuários que possuem acesso ao sistema.
                </p>

            </div>


            <a
                href="../pages/cadastro_funcionario.php"
                class="botao-primario botao-novo-funcionario"
            >
                + Novo funcionário
            </a>

        </section>

        <?php

        $totalFuncionarios = count($funcionarios);

        $funcionariosAtivos = 0;
        $funcionariosInativos = 0;

        foreach ($funcionarios as $funcionario) {

            if ((int) $funcionario['ativo'] === 1) {
                $funcionariosAtivos++;
            } else {
                $funcionariosInativos++;
            }

        }

        ?>


        <section class="stats">

            <div class="stat-card total">

                <div class="valor">
                    <?= $totalFuncionarios ?>
                </div>

                <div class="rotulo">
                    Total de funcionários
                </div>

            </div>


            <div class="stat-card disponivel">

                <div class="valor">
                    <?= $funcionariosAtivos ?>
                </div>

                <div class="rotulo">
                    Usuários ativos
                </div>

            </div>


            <div class="stat-card manutencao">

                <div class="valor">
                    <?= $funcionariosInativos ?>
                </div>

                <div class="rotulo">
                    Usuários inativos
                </div>

            </div>


            <div class="stat-card emprestada">

                <div class="valor">
                    <?= date('d/m/Y') ?>
                </div>

                <div class="rotulo">
                    Acesso realizado em
                </div>

            </div>

        </section>
        <section class="painel-secao tabela-funcionarios">

            <div class="secao-cabecalho">

                <div>

                    <h2>
                        Lista de usuários
                    </h2>

                    <p class="subinfo">
                        Todos os funcionários cadastrados no sistema.
                    </p>

                </div>

                <span class="contador-usuarios">
                    <?= $totalFuncionarios ?>
                    <?= $totalFuncionarios === 1 ? 'usuário' : 'usuários' ?>
                </span>

            </div>



            <?php if (isset($erroBanco)): ?>

                <div class="mensagem erro">
                    <?= htmlspecialchars($erroBanco) ?>
                </div>


            <?php elseif (empty($funcionarios)): ?>

                <div class="estado-vazio">

                    <div class="icone-vazio">
                        +
                    </div>

                    <strong>
                        Nenhum funcionário cadastrado
                    </strong>

                    <p>
                        Cadastre o primeiro funcionário para começar.
                    </p>

                    <a
                        href="../pages/cadastro_funcionario.php"
                        class="botao-secundario"
                    >
                        Cadastrar funcionário
                    </a>

                </div>


            <?php else: ?>

                <div class="tabela-scroll">

                    <table class="ferramentas tabela-usuarios">

                        <thead>

                            <tr>

                                <th>ID</th>

                                <th>Funcionário</th>

                                <th>Matrícula</th>

                                <th>Setor</th>

                                <th>Email</th>

                                <th>Status</th>

                                <th>Cadastro</th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php foreach ($funcionarios as $funcionario): ?>

                                <tr>

                                    <td class="id-usuario">
                                        #<?= (int) $funcionario['id'] ?>
                                    </td>


                                    <td>

                                        <div class="nome-usuario">

                                            <span class="avatar-usuario">
                                                <?= strtoupper(
                                                    substr(
                                                        $funcionario['nome'],
                                                        0,
                                                        1
                                                    )
                                                ) ?>
                                            </span>

                                            <strong>
                                                <?= htmlspecialchars(
                                                    $funcionario['nome']
                                                ) ?>
                                            </strong>

                                        </div>

                                    </td>


                                    <td class="tag">
                                        <?= htmlspecialchars(
                                            $funcionario['matricula']
                                        ) ?>
                                    </td>


                                    <td>
                                        <?= htmlspecialchars(
                                            $funcionario['departamento']
                                        ) ?>
                                    </td>


                                    <td class="email-usuario">
                                        <?= htmlspecialchars(
                                            $funcionario['email']
                                        ) ?>
                                    </td>


                                    <td>

                                        <?php if ((int) $funcionario['ativo'] === 1): ?>

                                            <span class="status-badge disponivel">
                                                Ativo
                                            </span>

                                        <?php else: ?>

                                            <span class="status-badge manutencao">
                                                Inativo
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <td class="data-cadastro">

                                        <?php

                                        if (!empty($funcionario['criado_em'])) {

                                            echo date(
                                                'd/m/Y',
                                                strtotime(
                                                    $funcionario['criado_em']
                                                )
                                            );

                                        } else {

                                            echo '-';

                                        }

                                        ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </section>

    </main>


</body>

</html>
