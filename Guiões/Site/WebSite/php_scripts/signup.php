<?php

    //Adicionar Popups de Erros de Registo


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $palavrapasse = trim($_POST['password']);
    $num_cc = intval($_POST['num_cc']);
    $validade_cc = trim($_POST['validade_cc']);
    $cvv_cc = intval($_POST['cvv_cc']);

    /*
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    */

    // Verificação básica
    if (empty($nome) || empty($email) || empty($palavrapasse) || empty($validade_cc)) {
        die("⚠️ Todos os campos são obrigatórios.");
    }

    try {
        $db = new SQLite3('../../DataBase/venda_bilhetes.db');

        // Verifica se o email já existe
        $check = $db->prepare("SELECT user_id FROM dados_cliente WHERE email = :email");
        $check->bindValue(':email', $email, SQLITE3_TEXT);
        $res = $check->execute();

        if ($res->fetchArray()) {
            //Introduzir popup na página anterior
            echo "⚠️ Este email já está registado.";
            die('<br><a href="../iniciar_sessao.php">Ir para login</a>');
        }

        // Hash da password
        $hashed_password = password_hash($palavrapasse, PASSWORD_DEFAULT);

        // Inserção do utilizador
        $stmt = $db->prepare("INSERT INTO dados_cliente (nome, email, palavrapasse, num_cc, validade_cc, cvv_cc)
                              VALUES (:nome, :email, :palavrapasse, :num_cc, :validade_cc, :cvv_cc)");
        $stmt->bindValue(':nome', $nome, SQLITE3_TEXT);
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->bindValue(':palavrapasse', $hashed_password, SQLITE3_TEXT);
        $stmt->bindValue(':num_cc', $num_cc, SQLITE3_INTEGER);
        $stmt->bindValue(':validade_cc', $validade_cc, SQLITE3_TEXT);
        $stmt->bindValue(':cvv_cc', $cvv_cc, SQLITE3_INTEGER);

        $result = $stmt->execute();

        if ($result) {
            //echo "✅ Utilizador registado com sucesso.";
            //echo '<br><a href="iniciar_sessao.php">Ir para login</a>';
            header("Location: ../iniciar_sessao.php"); // Redireciona para a página principal
        } else {
            echo "❌ Falha ao registar utilizador.";
            echo '<br><a href="../registar.php">Ir para registo</a>';
        }
    } catch (Exception $e) {
        echo "❌ Erro de base de dados: " . $e->getMessage();
    }
} else {
    echo "⚠️ Método inválido.";
}
?>
