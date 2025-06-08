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