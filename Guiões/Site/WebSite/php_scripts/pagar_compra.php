<?php

session_start();

//Verifica se o utilizador tem sessão inciada (redundante)
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?auth=0');
}

$db = new SQLite3('../../DataBase/venda_bilhetes.db');

// Obter o ID do utilizador da sessão
$user_id = $_SESSION['user_id'];


// Obter os bilhetes do carrinho do utilizador
$stmt = $db->prepare("SELECT id_bilhete, quantidade FROM carrinhos WHERE user_id = :user_id");
$stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$result = $stmt->execute();


//Verificar se todos os bilhetes estão disponíveis nas quantidades necessárias
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $id_bilhete = $row['id_bilhete'];
    $quantidade = $row['quantidade'];

    // Buscar nome e preço atual do bilhete
    $bilhete_stmt = $db->prepare("SELECT id,nome,morada,dia,preco,disponiveis FROM lista_bilhetes WHERE id = :id_bilhete");
    $bilhete_stmt->bindValue(':id_bilhete', $id_bilhete, SQLITE3_INTEGER);
    $bilhete_info = $bilhete_stmt->execute()->fetchArray(SQLITE3_ASSOC);

    if (!$bilhete_info) {
        //Se as informações do bilhete forem inválidas, salta este ciclo
        continue;
    }

    if ($quantidade>$disponiveis) {
        //Se a quantidade pretendida é superior à disponivel
        header("Location: ../carrinho.php?availbale=0");
    }
}


//Se está neste passo do programa é porque não houve redirect e todos os 
// bilhetes estão disponíveis nas quantidades necessárias
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $id_bilhete = $row['id_bilhete'];
    $quantidade = $row['quantidade'];
    /*
    echo "<pre>";
    print_r($id_bilhete);
    echo "</pre>";
    */

    // Buscar nome e preço atual do bilhete
    $bilhete_stmt = $db->prepare("SELECT id,nome,morada,dia,preco,disponiveis FROM lista_bilhetes WHERE id = :id_bilhete");
    $bilhete_stmt->bindValue(':id_bilhete', $id_bilhete, SQLITE3_INTEGER);
    $bilhete_info = $bilhete_stmt->execute()->fetchArray(SQLITE3_ASSOC);

    if (!$bilhete_info) {
        //Se as informações do bilhete forem inválidas, salta este ciclo
        continue;
    }

    if ($quantidade>$disponiveis) {
        //Se a quantidade pretendida é superior à disponivel
        header("Location: ../carrinho.php?availbale=0");
    }

    $nome = $bilhete_info['nome'];
    $morada = $bilhete_info['morada'];
    $dia = $bilhete_info['dia'];
    $preco = $bilhete_info['preco'];
    //Falta implementar utilização de email para enviar confirmação de compra

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

//Se não tem bilhetes na tabela
//Simula um While Else
if (!$row = $result->fetchArray(SQLITE3_ASSOC)) {
    header("Location: ../carrinho.php?success=0");
}

// Limpar o carrinho do utilizador
$delete = $db->prepare("DELETE FROM carrinhos WHERE user_id = :user_id");
$delete->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$delete->execute();

header("Location: ../carrinho.php?success=1");
?>
