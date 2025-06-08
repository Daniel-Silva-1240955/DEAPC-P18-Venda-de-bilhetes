<?php
try {
    $db = new SQLite3('../../DataBase/venda_bilhetes.db');

// Obter todos os registos do histórico
    $results = $db->query("SELECT * FROM historico");

    echo "<!DOCTYPE html>
    <html lang='pt'>
    <head>
        <meta charset='UTF-8'>
        <title>Histórico de Compras</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                padding: 20px;
            }
            h1 {
                text-align: center;
            }
            table {
                width: 95%;
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
        <h1>Histórico de Compras</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>ID Utilizador</th>
                <th>ID Bilhete</th>
                <th>Nome</th>
                <th>Morada</th>
                <th>Dia</th>
                <th>Preço (€)</th>
                <th>Quantidade</th>
                <th>Data da Compra</th>
            </tr>";

    while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
        echo "<tr>
                <td>" . htmlspecialchars($row['id']) . "</td>
                <td>" . htmlspecialchars($row['user_id']) . "</td>
                <td>" . htmlspecialchars($row['id_bilhete']) . "</td>
                <td>" . htmlspecialchars($row['nome']) . "</td>
                <td>" . htmlspecialchars($row['morada']) . "</td>
                <td>" . htmlspecialchars($row['dia']) . "</td>
                <td>" . number_format($row['preco'], 2) . "</td>
                <td>" . htmlspecialchars($row['quantidade']) . "</td>
                <td>" . htmlspecialchars($row['data_compra']) . "</td>
              </tr>";
    }

    echo "</table></body></html>";

} catch (Exception $e) {
    echo "Erro ao aceder à base de dados: " . $e->getMessage();
}
?>
