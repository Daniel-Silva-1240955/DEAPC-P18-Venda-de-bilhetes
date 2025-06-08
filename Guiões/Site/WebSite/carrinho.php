<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    //Abre popup a dizer que não tem sessão iniciada
    //Carregar no botão faz redirect para a página de login
    die("Utilizador não autenticado. Por favor, inicie sessão.");
}
$user_id = $_SESSION['user_id'];

try {
    $db = new SQLite3('../DataBase/venda_bilhetes.db');

    // Total de itens do utilizador
    $stmt = $db->prepare('SELECT SUM(quantidade) as total_items FROM carrinhos WHERE user_id = :user_id');
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    $total_items = $row['total_items'] ?? 0;

} catch (Exception $e) {
    $total_items = 0;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Carrinho</title>
    <link rel="stylesheet" href="styles/carrinho.css">
</head>

<body>
<div class="alinhamento-tabela">
    <form action="php_scripts/pagar_compra.php" method="POST">
        <table class="background-branco-tabela">
            <colgroup>
                <col span="2">
                <col span="1" class="background-cinza-tabela">
            </colgroup>

            <tr>
                <th class="table-header left">Carrinho</th>
                <th class="table-header center"><?php echo $total_items; ?> items</th>
                <th class="table-header right">Checkout</th>
            </tr>

            <tr>
                <td colspan="2" class="product-cell">
                    <div class="scrollable-wrapper">
                        <div class="scrollable-products">
                            <?php
                            $stmt = $db->prepare(
                                'SELECT l.nome, l.preco, c.quantidade
                                FROM carrinhos c
                                JOIN lista_bilhetes l ON c.id_bilhete = l.id
                                WHERE c.user_id = :user_id'
                                );

                            $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
                            $results = $stmt->execute();

                            $total_price = 0;
                            while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
                                $total_price += $row['preco'] * $row['quantidade'];
                                echo '<div class="product-item">';
                                echo '<span class="product-name">' . htmlspecialchars($row['nome']) . '</span>';
                                echo '<span class="price-qty-wrapper">';
                                echo '<span class="price">€' . number_format($row['preco'], 2) . '</span>';
                                echo '<span class="quantity">x' . intval($row['quantidade']) . '</span>';
                                echo '</span>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </td>

                <td>
                    <div class="cell center">
                        <div class="email-box">
                            <label for="email">Receba os seus bilhetes por email:</label>
                            <input type="email" id="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="total-box">
                            <span>Total:</span>
                            <span id="total-valor">€<?php echo number_format($total_price, 2); ?></span>
                        </div>
                    </div>
                </td>
            </tr>

            <tr class="table-footer">
                <td>
                    <div class="cell left">
                        <a href="index.php" class="botao back">⬅ Continuar a Comprar</a>
                    </div>
                </td>
                <td></td>
                <td>
                    <div class="cell center">
                        <button type="submit" class="botao pagar">Pagar</button>
                    </div>
                </td>
            </tr>

        </table>
    </form>
</div>
</body>
</html>
