<!DOCTYPE html>

<html lang="PT">
    <link rel="stylesheet" href="styles/registar.css">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registo</title>
    <script src="scripts/validar_registo.js" defer></script>
</head>
<body>
    <a href="iniciar_sessao.php" class="voltar" style="float: left;">⬅ Voltar</a>
       
    <div class="fundo">
        <div class="conteudo">
            <h1>Registar</h1>
            <form action="php_scripts/signup.php" method="POST" onsubmit="return validacao()">
                <input name="nome" type="text" placeholder="Nome e Apelido" id="name" required>
                <input name="email" type="email" placeholder="Email" required>
                <input name="password" type="password" id="password" placeholder="Palavra passe" required>
                <input name="num_cc" type="text" class="num_cc" maxlength="16" placeholder="Número do cartão" required>
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

