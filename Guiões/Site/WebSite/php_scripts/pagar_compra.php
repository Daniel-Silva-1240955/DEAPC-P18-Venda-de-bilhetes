<?php
/*echo "<pre>";
print_r($_POST);
echo "</pre>";
exit;*/
session_start();

//Verifica se o utilizador tem sessão inciada (redundante)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$db = new SQLite3('../../DataBase/venda_bilhetes.db');

// Obter o ID do utilizador da sessão
$user_id = $_SESSION['user_id'];

// Obter os bilhetes do carrinho do utilizador
$stmt = $db->prepare("SELECT id_bilhete, quantidade FROM carrinhos WHERE user_id = :user_id");
$stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$result = $stmt->execute();

while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $id_bilhete = $row['id_bilhete'];
    $quantidade = $row['quantidade'];

    // Buscar nome e preço atual do bilhete
    $bilhete_stmt = $db->prepare("SELECT id,nome,morada,dia,preco FROM lista_bilhetes WHERE id = :id_bilhete");
    $bilhete_stmt->bindValue(':id_bilhete', $id_bilhete, SQLITE3_INTEGER);
    $bilhete_info = $bilhete_stmt->execute()->fetchArray(SQLITE3_ASSOC);

    if (!$bilhete_info) {
        continue; // ignora bilhetes inválidos
    }

    $nome = $bilhete_info['nome'];
    $morada = $bilhete_info['morada'];
    $dia = $bilhete_info['dia'];
    $preco = $bilhete_info['preco'];
    /*
    echo "<pre>";
    print_r($bilhete_info);
    echo "</pre>";
    exit;   */

    // Inserir no histórico
    $data_compra = date('Y-m-d H:i:s');
    
    $stmt = $db->prepare("INSERT INTO historico (user_id, id_bilhete, nome, morada, dia, preco, quantidade, data_compra) 
                          VALUES (:user_id, :id_bilhete, :nome, :morada, :dia, :preco, :quantidade, :data_compra)");
    
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $stmt->bindValue(':id_bilhete', $id_bilhete, SQLITE3_INTEGER);
    $stmt->bindValue(':nome', $nome, SQLITE3_TEXT);
    $stmt->bindValue(':morada', $morada, SQLITE3_TEXT);
    $stmt->bindValue(':dia', $dia, SQLITE3_TEXT);
    $stmt->bindValue(':preco', $preco, SQLITE3_FLOAT);
    $stmt->bindValue(':quantidade', $quantidade, SQLITE3_INTEGER);
    $stmt->bindValue(':data_compra', $data_compra, SQLITE3_TEXT);
    $stmt->execute();
    // Atualizar a quantidade disponível
    $update_stmt = $db->prepare("UPDATE lista_bilhetes SET disponiveis = disponiveis - :quantidade WHERE id = :id_bilhete");
    $update_stmt->bindValue(':quantidade', $quantidade, SQLITE3_INTEGER);
    $update_stmt->bindValue(':id_bilhete', $id_bilhete, SQLITE3_INTEGER);
    $update_stmt->execute();


}


// Limpar o carrinho do utilizador
$delete = $db->prepare("DELETE FROM carrinhos WHERE user_id = :user_id");
$delete->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$delete->execute();

header("Location: ../carrinho.php?success=1");
exit;
?>
