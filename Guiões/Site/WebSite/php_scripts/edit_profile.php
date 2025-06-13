<?php
session_start();

// Verificar se o utilizador tem sessão iniciada
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}


$db = new SQLite3('../../DataBase/venda_bilhetes.db');

// Obter o ID do utilizador da sessão
$user_id = $_SESSION['user_id'];


// Verificar se os campos foram submetidos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Receber os dados do formulário
    $num_cc = $_POST['num_cc'];
    $validade_cc = $_POST['validade_cc'];
    $cvv_cc = $_POST['cvv_cc'];
    $password_input = $_POST['palavrapasse'];

    

    // Buscar password atual da base de dados
    $stmt = $db->prepare('SELECT palavrapasse FROM dados_cliente WHERE user_id = :user_id');
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

    
    if (!$result) {
        // Se o utilizador não for encontrado, redirecionar
        header("Location: ../pagina_perfil.php?editar=2&success=0");
        exit;
    }

    $password_atual = $result['palavrapasse'];

    
    // Verificar se o utilizador alterou a password ou não
    if ($password_input !== '********') {
        $password_atual = password_hash($password_input, PASSWORD_DEFAULT);
    }
    
    // Atualizar dados na base de dados
    $update = $db->prepare('UPDATE dados_cliente 
                            SET palavrapasse = :palavrapasse, num_cc = :num_cc, validade_cc = :validade_cc, cvv_cc = :cvv_cc 
                            WHERE user_id = :user_id');

    $update->bindValue(':palavrapasse', $password_atual, SQLITE3_TEXT);
    $update->bindValue(':num_cc', $num_cc, SQLITE3_TEXT);
    $update->bindValue(':validade_cc', $validade_cc, SQLITE3_TEXT);
    $update->bindValue(':cvv_cc', $cvv_cc, SQLITE3_TEXT);
    $update->bindValue(':user_id', $user_id, SQLITE3_INTEGER);

    $update->execute();

    // Redirecionar para a página de perfil com confirmação
    header("Location: ../pagina_perfil.php?editar=2&success=1");
    exit;

} else {
    // Caso o método não seja POST
    header("Location: ../pagina_perfil.php?editar=2&success=0");
    exit;
}
?>
