<?php
session_start();

// Verifica se o utilizador está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?auth=0');
}

if (isset($_POST['id_bilhete'])) {
    $user_id = $_SESSION['user_id'];
    $id_bilhete = intval($_POST['id_bilhete']);

    try {
        $db = new SQLite3('../../DataBase/venda_bilhetes.db');
        
        //Apaga bilhete do carrinho
        $stmt = $db->prepare('DELETE FROM carrinhos WHERE user_id = :user_id AND id_bilhete = :id_bilhete');
        $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
        $stmt->bindValue(':id_bilhete', $id_bilhete, SQLITE3_INTEGER);
        $stmt->execute();

        header("Location: ../carrinho.php?removed=1");

    } catch (Exception $e) {
        //Erro de remoção do item do carrinho
        header("Location: ../carrinho.php?removed=0");
    }
} else {
    //Dados de POST inválidos
    header("Location: ../carrinho.php?data_error=1");
}
?>
