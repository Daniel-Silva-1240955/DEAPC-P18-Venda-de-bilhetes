<?php

    //Adicionar Popups de Erros de Remoção do carrinho


session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?auth=0');
    exit();
}

if (isset($_POST['id_bilhete'])) {
    $user_id = $_SESSION['user_id'];
    $id_bilhete = intval($_POST['id_bilhete']);

    try {
        $db = new SQLite3('../../DataBase/venda_bilhetes.db');
        
        $stmt = $db->prepare('DELETE FROM carrinhos WHERE user_id = :user_id AND id_bilhete = :id_bilhete');
        $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
        $stmt->bindValue(':id_bilhete', $id_bilhete, SQLITE3_INTEGER);
        $stmt->execute();

        header("Location: ../carrinho.php?removed=1");
        exit();

    } catch (Exception $e) {
        die("Erro ao remover item.");
    }
} else {
    die("Dados inválidos.");
}
?>
