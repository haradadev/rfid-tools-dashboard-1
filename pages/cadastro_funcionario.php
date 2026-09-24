<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$msg = $_SESSION['msg_func'] ?? '';
$status = $_SESSION['status_func'] ?? '';

unset($_SESSION['msg_func'], $_SESSION['status_func']);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastrar Funcionário · RFID Tools</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Alata&display=swap"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="style_sistema.css">
</head>

<body>

    <main class="tela">

        <h1 class="titulo">RFID TOOLS</h1>

        <p class="subtitulo">
            Sistema de Monitoramento
        </p>

        <section class="container">

            <h2>Cadastrar Funcionário</h2>

            <p class="descricao">
                Crie um acesso para um novo funcionário usar o sistema.
            </p>

            <?php if ($msg): ?>

                <div
                    id="mensagem"
                    class="mensagem <?= htmlspecialchars($status) ?>"
                    role="alert"
                >
                    <?= htmlspecialchars($msg) ?>
                </div>

            <?php endif; ?>


            <form
                id="formulario_cadastro_funcionario"
                action="../controller/cadastrar_funcionario.php"
                method="POST"
            >

                <label for="nome">
                    Nome completo
                </label>

                <input
                    type="text"
                    id="nome"
                    name="nome"
                    placeholder="Nome do funcionário"
                    autocomplete="name"
                    required
                >


                <label for="email">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="nome@empresa.com"
                    autocomplete="username"
                    required
                >


                <label for="matricula">
                    Matrícula
                </label>

                <input
                    type="text"
                    id="matricula"
                    name="matricula"
                    placeholder="Ex: F001"
                    required
                >


                <label for="departamento">
                    Departamento
                </label>

                <input
                    type="text"
                    id="departamento"
                    name="departamento"
                    placeholder="Ex: Manutenção"
                    required
                >


                <label for="tag_rfid">
                    Tag RFID
                </label>

                <input
                    type="text"
                    id="tag_rfid"
                    name="tag_rfid"
                    placeholder="Código do crachá/tag"
                    autocomplete="off"
                    required
                >


                <label for="senha">
                    Senha
                </label>

                <div class="campo-senha">

                    <input
                        type="password"
                        id="senha"
                        name="senha"
                        placeholder="Mínimo 6 caracteres"
                        autocomplete="new-password"
                        minlength="6"
                        required
                    >

                    <button
                        type="button"
                        class="botao-olho"
                        id="toggleSenhaCadastro"
                        aria-label="Mostrar senha"
                        title="Mostrar senha"
                    >
                        👁
                    </button>

                </div>


                <button
                    type="submit"
                    class="botao-primario"
                >
                    Cadastrar funcionário
                </button>

            </form>


            <a
                href="index_cadastro.php"
                class="botao-secundario"
            >
                Voltar para o login
            </a>

        </section>

    </main>


    <script>
        const botaoOlhoCadastro =
            document.getElementById('toggleSenhaCadastro');

        const campoSenhaCadastro =
            document.getElementById('senha');

        if (botaoOlhoCadastro && campoSenhaCadastro) {

            botaoOlhoCadastro.addEventListener('click', () => {

                const senhaVisivel =
                    campoSenhaCadastro.type === 'text';

                campoSenhaCadastro.type =
                    senhaVisivel ? 'password' : 'text';

                botaoOlhoCadastro.setAttribute(
                    'aria-label',
                    senhaVisivel
                        ? 'Mostrar senha'
                        : 'Ocultar senha'
                );

                botaoOlhoCadastro.setAttribute(
                    'title',
                    senhaVisivel
                        ? 'Mostrar senha'
                        : 'Ocultar senha'
                );
            });
        }
    </script>

</body>

</html>
