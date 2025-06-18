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
    <!-- CONFIRMED Pop-up Campos por preencher -->
    <?php if (isset($_GET['fields']) && $_GET['fields'] == '0'): ?>
        <div id="popup" class="popup">
            <div class="popup-content">
                <h2>Erro! Preencha os campos todos.</h2>
                <a href="registar.php">Continuar</a>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- CONFIRMED Pop-up Email existente -->
    <?php if (isset($_GET['found']) && $_GET['found'] == '1'): ?>
        <div id="popup" class="popup">
            <div class="popup-content">
                <h2>Erro! Este email já está registado.</h2>
                <a href="iniciar_sessao.php">Iniciar Sessão</a>
                <a href="registar.php">Continuar</a>
            </div>
        </div>
    <?php endif; ?>

    <!-- CONFIRMED Pop-up Registo criado  -->
    <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
        <div id="popup" class="popup">
            <div class="popup-content">
                <h2>Conta criada com sucesso</h2>
                <a href="iniciar_sessao.php">Iniciar Sessão</a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Pop-up falha ao criar registo  -->
    <?php if (isset($_GET['success']) && $_GET['success'] == '0'): ?>
        <div id="popup" class="popup">
            <div class="popup-content">
                <h2>Falha ao criar registo</h2>
                <a href="registar.php">Tentar de Novo</a>
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

    <a href="iniciar_sessao.php" class="voltar" style="float: left;">⬅ Voltar</a>
       
    <div class="fundo">
        <!-- Div com form para preenchimento de campos de registo -->
        <div class="conteudo">
            <h1>Registar</h1>
            <!-- Implementa validação de dados com JavaScript-->
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

