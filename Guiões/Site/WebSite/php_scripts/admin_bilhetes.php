<?php
$db = new SQLite3('../../DataBase/venda_bilhetes.db');

// ADICIONAR bilhete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar'])) {
    $nome = $_POST['nome'];
    $morada = $_POST['morada'];
    $dia = $_POST['dia'];
    $preco = $_POST['preco'];
    $limite = $_POST['limite'];

    $stmt = $db->prepare("INSERT INTO lista_bilhetes (nome, morada, dia, preco, limite, disponiveis)
                          VALUES (:nome, :morada, :dia, :preco, :limite, :limite)");
    $stmt->bindValue(':nome', $nome);
    $stmt->bindValue(':morada', $morada);
    $stmt->bindValue(':dia', $dia);
    $stmt->bindValue(':preco', $preco);
    $stmt->bindValue(':limite', $limite);
    $stmt->execute();
}

// REMOVER bilhete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remover_id'])) {
    $id = $_POST['remover_id'];
    $stmt = $db->prepare("DELETE FROM lista_bilhetes WHERE id = :id_bilhete");
    $stmt->bindValue(':id_bilhete', $id);
    $stmt->execute();
}

// LISTAR bilhetes
$result = $db->query("SELECT * FROM lista_bilhetes");
$bilhetes = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $bilhetes[] = $row;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Administração de Bilhetes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        h1, h2 {
            text-align: center;
        }
        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background-color: white;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #eee;
        }
        form {
            margin: 20px auto;
            text-align: center;
        }
        input[type="text"], input[type="number"], input[type="date"] {
            margin: 0.3rem;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 200px;
        }
        button {
            padding: 0.5rem 1rem;
            
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button.remove {
            background-color: #e74c3c;
        }
        button.remove:hover {
            background-color: #c0392b;
        }

        button.add {
            background-color: #3498db;
        }
        button.add:hover {
            background-color: #2980b9;
        }
        a {
            padding: 5px 10px;
            background-color: #2ecc71;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        a:hover {
            background-color: #27ae60;
        }
    </style>
</head>
<body>

<h1>Painel de Administração - Bilhetes</h1>

<h2>Adicionar novo bilhete</h2>
<form method="POST">
    <input type="text" name="nome" placeholder="Nome do evento" required>
    <input type="text" name="morada" placeholder="Morada" required>
    <input type="date" name="dia" required>
    <input type="number" name="preco" placeholder="Preço (€)" required>
    <input type="number" name="limite" placeholder="Nº Máximo" required>
    <button class="add" type="submit" name="adicionar">Adicionar</button>
</form>

<h2>Lista de Bilhetes</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Morada</th>
        <th>Data</th>
        <th>Preço</th>
        <th>Limite</th>
        <th>Disponíveis</th>
        <th>Ações</th>
    </tr>
    <?php foreach ($bilhetes as $bilhete): ?>
    <tr>
        <td><?= $bilhete['id'] ?></td>
        <td><?= htmlspecialchars($bilhete['nome']) ?></td>
        <td><?= htmlspecialchars($bilhete['morada']) ?></td>
        <td><?= $bilhete['dia'] ?></td>
        <td><?= $bilhete['preco'] ?> €</td>
        <td><?= $bilhete['limite'] ?></td>
        <td><?= $bilhete['disponiveis'] ?></td>
        <td>
            <form method="POST" style="display:inline;" onsubmit="return confirm('Remover este bilhete?');">
                <input type="hidden" name="remover_id" value="<?= $bilhete['id'] ?>">
                <button class="remove" type="submit">Remover</button>
            </form>
            <a href="admin_editar_bilhete.php?id=<?= $bilhete['id'] ?>">Editar</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>