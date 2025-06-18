<?php
session_start();

// Verifica se o utilizador está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?auth=0');
}

$db = new SQLite3('../../DataBase/venda_bilhetes.db');

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $num_cc = $_POST['num_cc'];
    $validade_cc = $_POST['validade_cc'];
    $cvv_cc = $_POST['cvv_cc'];
    $password_input = $_POST['password'];

    // Buscar password atual
    $stmt = $db->prepare('SELECT palavrapasse FROM dados_cliente WHERE user_id = :user_id');
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

    if (!$result) {
        //Erro ao aceder à base de dados
        header("Location: ../pagina_perfil.php?editar=2&success=0");
    }

    // O utilizador alterou a password
    if ($password_input !== '********') {
        $password_nova = password_hash($password_input, PASSWORD_DEFAULT);

        // Atualizar com nova password
        $update = $db->prepare('UPDATE dados_cliente 
                                SET palavrapasse = :palavrapasse, num_cc = :num_cc, validade_cc = :validade_cc, cvv_cc = :cvv_cc 
                                WHERE user_id = :user_id');
        $update->bindValue(':palavrapasse', $password_nova, SQLITE3_TEXT);

        
    } else {
        // Atualizar sem alterar a password
        $update = $db->prepare('UPDATE dados_cliente 
                                SET num_cc = :num_cc, validade_cc = :validade_cc, cvv_cc = :cvv_cc 
                                WHERE user_id = :user_id');
    }

    $update->bindValue(':num_cc', $num_cc, SQLITE3_INTEGER);
    $update->bindValue(':validade_cc', $validade_cc, SQLITE3_TEXT);
    $update->bindValue(':cvv_cc', $cvv_cc, SQLITE3_INTEGER);
    $update->bindValue(':user_id', $user_id, SQLITE3_INTEGER);

    $update->execute();
    /*
    echo "<pre>";
    echo "Palavrapasse antiga: ";
    print_r($palavrapasse);
    echo "\n";
    echo "Password input: ";
    print_r($password_input);
    echo "\n";
    echo "Password nova: ";
    print_r($password_nova);
    echo "</pre>";
    exit();*/

    header("Location: ../pagina_perfil.php?editar=2&success=1");
    exit();

} else {
    header("Location: ../pagina_perfil.php?editar=2&success=0");
    exit();
}
?>
