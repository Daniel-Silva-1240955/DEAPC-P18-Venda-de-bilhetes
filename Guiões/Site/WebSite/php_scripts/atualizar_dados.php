<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Utilizador não autenticado. Por favor, inicie sessão.");
}
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = new SQLite3('../DataBase/venda_bilhetes.db');
        
        $stmt = $db->prepare('UPDATE dados_cliente SET num_cc = :num_cc, validade_cc = :validade_cc, cvv_cc = :cvv_cc WHERE user_id = :user_id');
        
        $stmt->bindValue(':num_cc', $_POST['num_cc'], SQLITE3_TEXT);
        $stmt->bindValue(':validade_cc', $_POST['validade_cc'], SQLITE3_TEXT);
        $stmt->bindValue(':cvv_cc', $_POST['cvv_cc'], SQLITE3_TEXT);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], SQLITE3_INTEGER);
        
        if ($stmt->execute()) {
            header("Location: pagina_perfil.php?success=1");
            exit();
        } else {
            die("Erro ao atualizar os dados.");
        }
    } catch (Exception $e) {
        die("Erro na base de dados: " . $e->getMessage());
    }
} else {
    header("Location: pagina_perfil.php");
    exit();
}
?>