<?php

    //Adicionar Popups de Erros de Adicionar ao carrinho


/*echo "<pre>";
print_r($_POST);
echo "</pre>";
exit;*/
session_start();

// Verifica se o utilizador está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php?auth=0');
}

// Verifica se os dados foram enviados via POST corretamente
if (!isset($_POST['id_bilhete'], $_POST['quantidade'])) {
    header('Location: ../index.php?post=0');
    //die("Dados inválidos fornecidos.");
}

$id_bilhete = intval($_POST['id_bilhete']);
$quantidade = intval($_POST['quantidade']);
$user_id = $_SESSION['user_id'];

// Validação básica dos valores
if ($id_bilhete <= 0 || $quantidade <= 0) {
    header('Location: ../index.php?post=0');
    //die("Dados inválidos fornecidos.");
}

// Abre a base de dados
$db = new SQLite3('../../DataBase/venda_bilhetes.db');

// Verifica se o bilhete existe e obtém os dados, incluindo disponíveis
$stmt = $db->prepare("SELECT * FROM lista_bilhetes WHERE id = :id_bilhete");
$stmt->bindValue(':id_bilhete', $id_bilhete, SQLITE3_INTEGER);
$result = $stmt->execute();
$bilhete = $result->fetchArray(SQLITE3_ASSOC);



if (!$bilhete) {
    header('Location: ../index.php?found=0');
    //die("Bilhete não encontrado.");
}

// Verifica se há entradas suficientes disponíveis
if ($quantidade > $bilhete['disponiveis']) {
    header('Location: ../index.php?available=0');
}



// Obter os bilhetes do carrinho do utilizador
$stmt = $db->prepare("SELECT * FROM carrinhos WHERE user_id = :user_id and id_bilhete = :id_bilhete");
$stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$stmt->bindValue(':id_bilhete', $id_bilhete, SQLITE3_INTEGER);
$result = $stmt->execute();
$bilhete_carrinho = $result->fetchArray(SQLITE3_ASSOC);


/*
echo "<pre>";
print_r($bilhete_carrinho);
echo "</pre>";
exit;*/



if(!$bilhete_carrinho) {
// Insere novo bilhete no carrinho
$insert = $db->prepare("INSERT INTO carrinhos (user_id, id_bilhete, quantidade) VALUES (:user_id, :id_bilhete, :quantidade)");
$insert->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$insert->bindValue(':id_bilhete', $id_bilhete, SQLITE3_INTEGER);
$insert->bindValue(':quantidade', $quantidade, SQLITE3_INTEGER);
$insert->execute();
}
else {
    $update = $db->prepare("UPDATE carrinhos SET quantidade =:quantidade_nova WHERE user_id = :user_id and id_bilhete = :id_bilhete");
    $update->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $update->bindValue(':id_bilhete', $id_bilhete, SQLITE3_INTEGER);
    $update->bindValue(':quantidade_nova',$bilhete_carrinho['quantidade']+$quantidade, SQLITE3_INTEGER);
    $update->execute();
    /*echo "<pre>";
    print_r($bilhete_carrinho['quantidade']+$quantidade);
    echo "</pre>";
    exit;*/

}

//Redireciona para a página principal com mensagem de confirmação
header("Location: ../index.php?adicionado=1");
?>
