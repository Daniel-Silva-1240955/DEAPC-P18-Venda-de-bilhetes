<?php
session_start();

// Verifica se o utilizador está autenticado
if (!isset($_SESSION['user_id'])) {
    //Abre popup a dizer que não tem sessão iniciada
    //Carregar no botão faz redirect para a página de login
    header('Location: index.php?auth=0');
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

    // Vai buscar o email do utilizador
    $stmt = $db->prepare('SELECT email FROM dados_cliente WHERE user_id = :user_id');
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $user_row = $result->fetchArray(SQLITE3_ASSOC);

    $email_user = $user_row['email'] ?? '';

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
    <script src="scripts/carrinho.js" defer></script>
</head>

<body>
    <!-- Pop-up Pagar Compra Carrinho Vazio -->
    <?php if (isset($_GET['success']) && $_GET['success'] == '0'): ?>
        <div id="popup" class="popup">
            <div class="popup-content">
                <h2>Erro! Carrinho Vazio</h2>
                <a href="index.php">Voltar à Página Inicial</a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Pop-up Pagar Compra sucesso -->
    <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
        <div id="popup" class="popup">
            <div class="popup-content">
                <h2>Compra Efetuada com Sucesso!</h2>
                <a href="index.php">Voltar à Página Inicial</a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Pop-up Sem bilhetes disponiveis -->
    <?php if (isset($_GET['available']) && $_GET['available'] == '0'): ?>
        <div id="popup" class="popup">
            <div class="popup-content">
                <h2>Existem bilhetes indisponíveis</h2>
                <a href="carrinho.php">Continuar</a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Pop-up Bilhete Removido -->
    <?php if (isset($_GET['removed']) && $_GET['removed'] == '1'): ?>
        <div id="popup" class="popup">
            <div class="popup-content">
                <h2>Bilhete removido com Sucesso!</h2>
                <a href="carrinho.php">Continuar</a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Pop-up Erro Remoção Bilhete -->
    <?php if (isset($_GET['removed']) && $_GET['removed'] == '0'): ?>
        <div id="popup" class="popup">
            <div class="popup-content">
                <h2>Erro ao remover bilhete. Contacte o Admin</h2>
                <a href="carrinho.php">Continuar</a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Pop-up Erro Dados Bilhete -->
    <?php if (isset($_GET['data_error']) && $_GET['data_error'] == '1'): ?>
        <div id="popup" class="popup">
            <div class="popup-content">
                <h2>Erro ao remover bilhete. Contacte o Admin</h2>
                <a href="carrinho.php">Continuar</a>
            </div>
        </div>
    <?php endif; ?>


    <div class="alinhamento-tabela">
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
                                'SELECT l.nome, l.preco, c.quantidade, c.id_bilhete FROM carrinhos c
                                JOIN lista_bilhetes l ON c.id_bilhete = l.id WHERE c.user_id = :user_id'
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
                                
                                // Botão eliminar
                                echo '<form action="php_scripts/remover_carrinho.php" method="POST" style="display:inline;">';
                                echo '<input type="hidden" name="id_bilhete" value="' . $row['id_bilhete'] . '">';
                                echo '<button type="submit" class="botao-remover">Eliminar</button>';
                                echo '</form>';
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
                            <!--<input type="email" id="email" name="email" placeholder="Email" required>-->
                            <input type="email" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email_user); ?>" required>
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
                        <!-- <button type="submit" class="botao pagar">Pagar</button>
                        Formulário de pagamento separado -->
                        <form id="form-pagar" action="php_scripts/pagar_compra.php" method="POST" onsubmit="return validacao_email()">
                            <!-- Este input vai receber o email via JS -->
                            <input type="hidden" id="email-hidden" name="email">
                            <button type="submit" class="botao pagar">Pagar</button>
                        </form>
                    </div>
                </td>
            </tr>
        </table>

    </div>
</body>
</html>