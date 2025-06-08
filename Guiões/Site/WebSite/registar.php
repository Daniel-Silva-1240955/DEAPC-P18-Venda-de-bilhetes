<!DOCTYPE html>

<html lang="PT">
    <link rel="stylesheet" href="styles/registar.css">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registo</title>
</head>

<body>
    <a href="iniciar_sessao.php" class="voltar" style="float: left;">⬅ Voltar</a>
       
    <div class="fundo">
        <div class="conteudo">
            <h1>Registar</h1>
            <form action="php_scripts/signup.php" method="POST">
                <input name="nome" type="text" placeholder="Nome e Apelido" id="name" required>
                <input name="email" type="email" placeholder="Email" required>
                <input name="palavrapasse" type="password" id="password" placeholder="Palavra passe" required>
                <input name="num_cc" type="tel" maxlength="19" placeholder="Número do cartão" required>
                <div class="validadeCVV">
                    <input name="validade_cc" class="data_validade" type="month" required>
                    <input name="cvv_cc" class="cvv" type="text" placeholder="CVV" maxlength="3" required>
                </div>

                <button type="submit" class="Registar"> Registar </button>
            </form>
        </div>
    </div>
</body>
</html>