<?php
try {
    $db = new SQLite3('../../DataBase/venda_bilhetes.db');

    // Remover utilizador se o formulário for submetido
    if (isset($_POST['remover']) && isset($_POST['user_id'])) {
        $stmt = $db->prepare("DELETE FROM dados_cliente WHERE user_id = :user_id");
        $stmt->bindValue(':user_id', $_POST['user_id'], SQLITE3_INTEGER);
        $stmt->execute();
    }

    // Obter todos os utilizadores
    $results = $db->query("SELECT user_id, nome, email, palavrapasse, num_cc, validade_cc, cvv_cc FROM dados_cliente");

    echo "<!DOCTYPE html>
    <html lang='pt'>
    <head>
        <meta charset='UTF-8'>
        <title>Lista de Utilizadores</title>
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
                margin: 0;
            }
            button {
                padding: 5px 10px;
                background-color: #e74c3c;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }
            button:hover {
                background-color: #c0392b;
            }
        </style>
    </head>
    <body>
        <h1>Registos de Utilizadores</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Palavra-passe</th>
                <th>Número CC</th>
                <th>Validade CC</th>
                <th>CVV</th>
                <th>Ações</th>
            </tr>";

    while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
        echo "<tr>
                <td>" . htmlspecialchars($row['user_id']) . "</td>
                <td>" . htmlspecialchars($row['nome']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['palavrapasse']) . "</td>
                <td>" . htmlspecialchars($row['num_cc']) . "</td>
                <td>" . htmlspecialchars($row['validade_cc']) . "</td>
                <td>" . htmlspecialchars($row['cvv_cc']) . "</td>
                <td>
                    <form method='POST' onsubmit=\"return confirm('Tem a certeza que quer remover este utilizador?');\">
                        <input type='hidden' name='user_id' value='" . $row['user_id'] . "'>
                        <button type='submit' name='remover'>Remover</button>
                    </form>
                </td>
              </tr>";
    }

    echo "</table></body></html>";

} catch (Exception $e) {
    echo "Erro ao aceder à base de dados: " . $e->getMessage();
}
?>
