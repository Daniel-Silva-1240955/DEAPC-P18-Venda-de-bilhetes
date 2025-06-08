<?php
/*echo "<pre>";
print_r($_POST);
echo "</pre>";
exit;*/
session_start();

// Verifica se o utilizador está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../iniciar_sessao.php");
    exit;
}

// Verifica se os dados foram enviados via POST corretamente
if (!isset($_POST['id_bilhete'], $_POST['quantidade'])) {
    die("Dados inválidos fornecidos.");
}

$id_bilhete = intval($_POST['id_bilhete']);
$quantidade = intval($_POST['quantidade']);
$user_id = $_SESSION['user_id'];

// Validação básica dos valores
if ($id_bilhete <= 0 || $quantidade <= 0) {
    die("Dados inválidos fornecidos.");
}

// Abre a base de dados
$db = new SQLite3('../../DataBase/venda_bilhetes.db');

// Verifica se o bilhete existe e obtém os dados, incluindo disponíveis
$stmt = $db->prepare("SELECT * FROM lista_bilhetes WHERE id = :id_bilhete");
$stmt->bindValue(':id_bilhete', $id_bilhete, SQLITE3_INTEGER);
$result = $stmt->execute();
$bilhete = $result->fetchArray(SQLITE3_ASSOC);

if (!$bilhete) {
    die("Bilhete não encontrado.");
}

// Verifica se há entradas suficientes disponíveis
if ($quantidade > $bilhete['disponiveis']) {
    die("Quantidade solicitada indisponível.");
}

// Insere novo bilhete no carrinho
$insert = $db->prepare("INSERT INTO carrinhos (user_id, id_bilhete, quantidade) VALUES (:user_id, :id_bilhete, :quantidade)");
$insert->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$insert->bindValue(':id_bilhete', $id_bilhete, SQLITE3_INTEGER);
$insert->bindValue(':quantidade', $quantidade, SQLITE3_INTEGER);
$insert->execute();


// Opcional: Atualizar o campo 'disponiveis' na tabela lista_bilhetes para refletir a reserva no carrinho, se desejado
// Atenção: Essa lógica pode variar conforme o fluxo da sua aplicação.

//Aqui adicionar popup na página principal para indicar que o item foi adicionado ao carrinho
// Redireciona para a página principal ou para o carrinho
header("Location: ../index.php");
exit;
?>
