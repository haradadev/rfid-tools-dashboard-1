<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['funcionario_id'])) {
    header('Location: index_sistema.php');
    exit;
}

$msg = $_SESSION['msg_login'] ?? '';
$status = $_SESSION['status_login'] ?? '';

unset($_SESSION['msg_login'], $_SESSION['status_login']);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login · RFID Tools</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Alata&display=swap"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="style_sistema.css">
</head>

<body>

    <main class="tela tela-login">

        <div class="logo-rfid">
            <span class="logo-ponto"></span>
            <span class="logo-ponto"></span>
            <span class="logo-ponto"></span>
        </div>

        <h1 class="titulo">RFID TOOLS</h1>

        <p class="subtitulo">
            Sistema de Monitoramento
        </p>


        <section class="container login-container">

            <div class="login-cabecalho">

                <h2>Acessar sistema</h2>

                <p class="descricao">
                    Entre com suas credenciais para continuar.
                </p>

            </div>


            <?php if ($msg): ?>

                <div
                    id="mensagem"
                    class="mensagem <?= htmlspecialchars($status) ?>"
                    role="alert"
                >
                    <?= htmlspecialchars($msg) ?>
                </div>

            <?php endif; ?>


            <form id="formulario_login" action="../controller/login.php" method="POST" >

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
                    autofocus
                >


                <label for="senha">
                    Senha
                </label>

                <div class="campo-senha">

                    <input
                        type="password"
                        id="senha"
                        name="senha"
                        placeholder="Digite sua senha"
                        autocomplete="current-password"
                        required
                    >

                    <button
                        type="button"
                        class="botao-olho"
                        id="toggleSenhaLogin"
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
                    Entrar no sistema
                </button>

            </form>


            <div class="separador-login">
                <span>ou</span>
            </div>


            <div class="cadastro-login">

                <p>
                    Ainda não possui acesso?
                </p>

                <a
                    href="cadastro_funcionario.php"
                    class="botao-secundario"
                >
                    Cadastrar funcionário
                </a>

            </div>

        </section>


        <p class="rodape-login">
            RFID TOOLS · Sistema de Monitoramento
        </p>

    </main>


    <script>
        const botaoOlhoLogin =
            document.getElementById('toggleSenhaLogin');

        const campoSenhaLogin =
            document.getElementById('senha');

        if (botaoOlhoLogin && campoSenhaLogin) {

            botaoOlhoLogin.addEventListener('click', () => {

                const senhaVisivel =
                    campoSenhaLogin.type === 'text';

                campoSenhaLogin.type =
                    senhaVisivel ? 'password' : 'text';

                botaoOlhoLogin.setAttribute(
                    'aria-label',
                    senhaVisivel
                        ? 'Mostrar senha'
                        : 'Ocultar senha'
                );

                botaoOlhoLogin.setAttribute(
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
