<!DOCTYPE html>

<html lang="pt">
    <link rel="stylesheet" href="styles/iniciar_sessao.css">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="widht-devide-width, initial-scale=0.1">
    <title>Iniciar sessão</title>
</head>

<body>
    <!-- Pop-up Campos por preencher -->
    <?php if (isset($_GET['fields']) && $_GET['fields'] == '0'): ?>
        <div id="popup" class="popup">
            <div class="popup-content">
                <h2>Erro! Preencha os campos todos.</h2>
                <a href="iniciar_sessao.php">Continuar</a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Pop-up falha ao iniciar sessão  -->
    <?php if (isset($_GET['success']) && $_GET['success'] == '0'): ?>
        <div id="popup" class="popup">
            <div class="popup-content">
                <h2>Email ou Palavra-Passe incorretos</h2>
                <a href="inicar_sessao.php">Tentar de Novo</a>
            </div>
        </div>
    <?php endif; ?>


    <!-- Pop-up falha na base de dados  -->
    <?php if (isset($_GET['database']) && $_GET['database'] == '0'): ?>
        <div id="popup" class="popup">
            <div class="popup-content">
                <h2>Falha na Base de Dados. Contacte o Admin</h2>
                <a href="index.php">Continuar</a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Pop-up falha ao aceder ao signup.php  -->
    <?php if (isset($_GET['method']) && $_GET['method'] == '0'): ?>
        <div id="popup" class="popup">
            <div class="popup-content">
                <h2>Erro de ficheiro. Contacte o Admin</h2>
                <a href="index.php">Continuar</a>
            </div>
        </div>
    <?php endif; ?>

    <a href="index.php" class="voltar" style="float: left;">⬅ Voltar</a>

    <div class="fundo">
        <div class="conteudo">
            <h1>Iniciar sessão</h1>
            <form action="php_scripts/login.php" method="POST">
                <input type="email" name="email" placeholder="email" required>
                <input type="password" name="palavrapasse" placeholder="palavra passe" required>
                <button type="submit" class="iniciar_sessao"> Iniciar sessão </button>
            </form>
            <a href="registar.php" class="Registar"> Registar </a>
        </div>
    </div>

</body>
</html>