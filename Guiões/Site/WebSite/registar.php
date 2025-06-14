<!DOCTYPE html>

<html lang="PT">
    <link rel="stylesheet" href="styles/registar.css">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registo</title>
    <script src="scripts/registar.js"></script>
</head>

<script>
    function validacaoRegisto() 
    {
        const email = document.querySelector('input[name="email"]').value;
        const password = document.getElementById('password').value;
        /*
        const num_cc = document.querySelector('input[name="num_cc"]').value;
        const cvv = document.querySelector('input[name="cvv_cc"]').value;
        */

        //Expressão regular para validar o email
        const emailValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailValido.test(email)) 
        {
            alert("Por favor, introduz um email válido.");
            return false;
        }

        //Expressão regular para validar a password
        const passwordValida = /^[0-9a-zA-Z$*&@#]{8,}$/;
        if (!passwordValida.test(password)) 
        {
            alert("A palavra-passe deve ter pelo menos 8 caracteres.");
            return false;
        }    

        //Expressão regular para validar o numero do cartao
        /*const cartaoValido = /^\d{16}$/;
        if (!cartaoValido.test(num_cc)) 
        {
            alert("O número do cartão deve ter 16 dígitos.");
            return false;
        }

        //Expressão regular para validar o cvv
        const cvvValido = /^\d{3}$/;
        if (!cvvValido.test(cvv)) {
            alert("O CVV deve ter 3 dígitos.");
            return false;
        }
        */

        return true;
    }
</script>

<body>
    <a href="iniciar_sessao.php" class="voltar" style="float: left;">⬅ Voltar</a>
       
    <div class="fundo">
        <div class="conteudo">
            <h1>Registar</h1>
            <form action="php_scripts/signup.php" method="POST" onsubmit="return validacaoRegisto()">
                <input name="nome" type="text" placeholder="Nome e Apelido" id="name" required>
                <input name="email" type="email" placeholder="Email" required>
                <input name="palavrapasse" type="password" id="password" placeholder="Palavra passe" required>
                <input name="num_cc" type="text" class="num_cc" maxlength="16" placeholder="Número do cartão" required>
                <div class="validadeCVV"> 
                    <input name="validade_cc" class="data_validade" type="month" required>
                    <input name="cvv" class="cvv" type="text" placeholder="CVV" maxlength="3" required>
                </div>

                <button type="submit" class="Registar"> Registar </button>
            </form>
        </div>
    </div>
</body>
</html>

