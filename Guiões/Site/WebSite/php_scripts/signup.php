<?php

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

    // Verificação básica de preenchimento de campos
    if (empty($nome) || empty($email) || empty($palavrapasse) || empty($num_cc) || empty($validade_cc) || empty($cvv_cc)) {
        header('Location: ../registar.php?fields=0');
        //die("⚠️ Todos os campos são obrigatórios.");
    }

    try {
        $db = new SQLite3('../../DataBase/venda_bilhetes.db');

        // Verifica se o email já existe
        $check = $db->prepare("SELECT user_id FROM dados_cliente WHERE email = :email");
        $check->bindValue(':email', $email, SQLITE3_TEXT);
        $res = $check->execute();

        if ($res->fetchArray()) {
            //Redireciona para popup a informar que o utilizador já existe
            header('Location: ../registar.php?found=1');
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
            // Redireciona para popup a informar que foi criado com sucesso
            header("Location: ../registar.php?success=1"); 
        } else {
            // Redireciona para popup a informar que não foi criado
            header("Location: ../registar.php?success=0");
        }
    } catch (Exception $e) {
        // Redireciona para popup a informar que ocorreu um erro de base de dados
        header("Location: ../registar.php?database=0");
    }
} else {
    // Redireciona para popup a informar que ocorreu um erro de comunicação
    header("Location: ../registar.php?method=0");
}
?>
