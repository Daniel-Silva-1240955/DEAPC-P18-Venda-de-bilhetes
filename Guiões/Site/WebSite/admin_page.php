<?php
$db = new SQLite3('../DataBase/venda_bilhetes.db');

// Função: Adicionar Bilhete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar'])) {
    $stmt = $db->prepare("INSERT INTO lista_bilhetes (nome, morada, dia, preco, limite, disponiveis) VALUES (:nome, :morada, :dia, :preco, :limite, :limite)");
    $stmt->bindValue(':nome', $_POST['nome']);
    $stmt->bindValue(':morada', $_POST['morada']);
    $stmt->bindValue(':dia', $_POST['dia']);
    $stmt->bindValue(':preco', $_POST['preco']);
    $stmt->bindValue(':limite', $_POST['limite']);
    $stmt->execute();
}

// Função: Remover Bilhete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remover_id'])) {
    $stmt = $db->prepare("DELETE FROM lista_bilhetes WHERE id = :id");
    $stmt->bindValue(':id', $_POST['remover_id']);
    $stmt->execute();
}

// Função: Remover Utilizador
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remover_utilizador'])) {
    $stmt = $db->prepare("DELETE FROM dados_cliente WHERE user_id = :user_id");
    $stmt->bindValue(':user_id', $_POST['user_id']);
    $stmt->execute();
}

// Listar Bilhetes
$bilhetes = [];
$result = $db->query("SELECT * FROM lista_bilhetes");
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $bilhetes[] = $row;
}

// Listar Bilhetes no Carrinho
$bilhetes_carrinho = [];
$resultCarrinho = $db->query("
    SELECT c.user_id, c.id_bilhete, c.quantidade, lb.nome, lb.morada, lb.dia, lb.preco
    FROM carrinhos c
    JOIN lista_bilhetes lb ON c.id_bilhete = lb.id
    ORDER BY c.user_id
");
while ($row = $resultCarrinho->fetchArray(SQLITE3_ASSOC)) {
    $bilhetes_carrinho[] = $row;
}

// Listar Histórico de Compras
$historico = [];
$resultHistorico = $db->query("SELECT * FROM historico");
while ($row = $resultHistorico->fetchArray(SQLITE3_ASSOC)) {
    $historico[] = $row;
}

// Listar Utilizadores
$utilizadores = [];
$resultUsers = $db->query("SELECT * FROM dados_cliente");
while ($row = $resultUsers->fetchArray(SQLITE3_ASSOC)) {
    $utilizadores[] = $row;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Painel de Administração Unificado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        h1, h2 {
            text-align: center;
            margin-top: 50px;
            color: #333;
        }

        section {
            margin-bottom: 60px;
        }

        table {
            width: 95%;
            margin: 20px auto;
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

        button.add { 
            background-color: #3498db; 
        }
        button.add:hover { 
            background-color: #2980b9; 
        }

        button.remove { 
            background-color: #e74c3c;
        }
        button.remove:hover { 
            background-color: #c0392b; 
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

        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin-bottom: 30px;
        }

        .header .voltar {
            position: absolute;
            left: 0;
            color: white;
            text-decoration: none;
            margin: 1rem 0;
            padding: 0.75rem 1.2rem;
            background-color: #34495e;
            border-radius: 8px;
            transition: 0.2s;
            font-size: 1rem;
            text-align: center;
        }
        .header .voltar:hover {
            background-color: #3d566e;
        }

    </style>
</head>
<body>

    <div class="header">
        <a href="index.php" class="voltar">⬅ Voltar</a>
        <h1>Painel de Administração</h1>
    </div>

<!-- Gestão de Bilhetes -->
<section id="gestao-bilhetes">
    <h2>Adicionar Novo Bilhete</h2>
    <form method="POST">
        <input type="text" name="nome" placeholder="Nome do evento" required>
        <input type="text" name="morada" placeholder="Morada" required>
        <input type="date" name="dia" required>
        <input type="number" name="preco" placeholder="Preço (€)" min="0" required>
        <input type="number" name="limite" placeholder="Nº Máximo" min="1" required>
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
                <!--<a href="admin_editar_bilhete.php?id=<?= $bilhete['id'] ?>">Editar</a>-->
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</section>

<!-- Bilhetes no Carrinho -->
<section id="bilhetes-carrinho">
    <h2>Bilhetes no Carrinho</h2>
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
</section>

<!-- Histórico de Compras -->
<section id="historico">
    <h2>Histórico de Compras</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>ID Utilizador</th>
            <th>ID Bilhete</th>
            <th>Nome</th>
            <th>Morada</th>
            <th>Data</th>
            <th>Preço (€)</th>
            <th>Quantidade</th>
            <th>Data da Compra</th>
        </tr>
        <?php foreach ($historico as $compra): ?>
        <tr>
            <td><?= $compra['id'] ?></td>
            <td><?= htmlspecialchars($compra['user_id']) ?></td>
            <td><?= htmlspecialchars($compra['id_bilhete']) ?></td>
            <td><?= htmlspecialchars($compra['nome']) ?></td>
            <td><?= htmlspecialchars($compra['morada']) ?></td>
            <td><?= htmlspecialchars($compra['dia']) ?></td>
            <td><?= number_format($compra['preco'], 2, ',', '.') ?></td>
            <td><?= htmlspecialchars($compra['quantidade']) ?></td>
            <td><?= htmlspecialchars($compra['data_compra']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</section>

<!-- Gestão de Utilizadores -->
<section id="utilizadores">
    <h2>Lista de Utilizadores</h2>
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
        </tr>
        <?php foreach ($utilizadores as $utilizador): ?>
        <tr>
            <td><?= htmlspecialchars($utilizador['user_id']) ?></td>
            <td><?= htmlspecialchars($utilizador['nome']) ?></td>
            <td><?= htmlspecialchars($utilizador['email']) ?></td>
            <td><?= htmlspecialchars($utilizador['palavrapasse']) ?></td>
            <td><?= htmlspecialchars($utilizador['num_cc']) ?></td>
            <td><?= htmlspecialchars($utilizador['validade_cc']) ?></td>
            <td><?= htmlspecialchars($utilizador['cvv_cc']) ?></td>
            <td>
                <form method="POST" onsubmit="return confirm('Tem a certeza que quer remover este utilizador?');">
                    <input type="hidden" name="user_id" value="<?= $utilizador['user_id'] ?>">
                    <button class="remove" type="submit" name="remover_utilizador">Remover</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</section>

</body>
</html>
