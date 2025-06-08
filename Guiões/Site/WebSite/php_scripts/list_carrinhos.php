<?php
$db = new SQLite3('../../DataBase/venda_bilhetes.db');

// Query para obter bilhetes que estão no carrinho com dados detalhados
$query = "
    SELECT c.user_id, c.id_bilhete, c.quantidade, lb.nome, lb.morada, lb.dia, lb.preco
    FROM carrinhos c
    JOIN lista_bilhetes lb ON c.id_bilhete = lb.id
    ORDER BY c.user_id
";

$result = $db->query($query);
$bilhetes_carrinho = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $bilhetes_carrinho[] = $row;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Bilhetes no Carrinho</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fafafa;
            padding: 20px;
        }
        h1 {
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
    </style>
</head>
<body>

<h1>Bilhetes Presentes no Carrinho</h1>

<table>
    <thead>
        <tr>
            <th>ID Utilizador</th>
            <th>ID Bilhete</th>
            <th>Nome</th>
            <th>Morada</th>
            <th>Data</th>
            <th>Preço (€)</th>
            <th>Quantidade</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($bilhetes_carrinho)): ?>
            <tr><td colspan="7">Nenhum bilhete no carrinho.</td></tr>
        <?php else: ?>
            <?php foreach ($bilhetes_carrinho as $bilhete): ?>
                <tr>
                    <td><?= htmlspecialchars($bilhete['user_id']) ?></td>
                    <td><?= htmlspecialchars($bilhete['id_bilhete']) ?></td>
                    <td><?= htmlspecialchars($bilhete['nome']) ?></td>
                    <td><?= htmlspecialchars($bilhete['morada']) ?></td>
                    <td><?= htmlspecialchars($bilhete['dia']) ?></td>
                    <td><?= number_format($bilhete['preco'], 2, ',', '.') ?></td>
                    <td><?= htmlspecialchars($bilhete['quantidade']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
