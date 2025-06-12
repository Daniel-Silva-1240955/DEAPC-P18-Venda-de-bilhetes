<?php
session_start();

// Verifica se o utilizador tem um user_id atribuído
// Se não, redireciona direto para a página de login
/*if (!isset($_SESSION['user_id'])) {
    header("Location: iniciar_sessao.php");
    exit;
}*/
$user_id = $_SESSION['user_id'];

$db = new SQLite3('../DataBase/venda_bilhetes.db');
$bilhetes = $db->query("SELECT * FROM lista_bilhetes");
?>


<!DOCTYPE html>
<html lang="pt">
    <link rel="stylesheet" href="styles/index.css">
<head>
    <meta charset="UTF-8">
    <title>Bilheteira Online</title>
</head>
<body>

<div class="main-content">
    <h1>Bilheteira Online</h1>
    <table>
        <thead>
            <tr>
                <th>Evento</th>
                <th>Morada</th>
                <th>Data</th>
                <th>Preço</th>
                <th>Quantidade</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($bilhete = $bilhetes->fetchArray(SQLITE3_ASSOC)): ?>
                <tr>
                    <td><?= htmlspecialchars($bilhete['nome']) ?></td>
                    <td><?= htmlspecialchars($bilhete['morada']) ?></td>
                    <td><?= htmlspecialchars($bilhete['dia']) ?></td>
                    <td><?= number_format($bilhete['preco'], 2) ?> €</td>
                    <td>
                        <form method="POST" action="php_scripts/adicionar_carrinho.php">
                            <input type="hidden" name="user_id" value="<?= $user_id ?>">
                            <input type="hidden" name="id_bilhete" value="<?= $bilhete['id'] ?>">
                            <!-- Introduzir popups quando carregam no botão para informar que não tem bilhetes disponíveis-->
                            <input type="number" name="quantidade" min="1" max="<?= $bilhete['disponiveis'] ?>" value="1" required>
                            <button type="submit">Adicionar</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div class="sidebar">
    <h2>Menu</h2>
    <!-- Introduzir popups quando carregam no botão para informar que não tem sessão iniciada-->
    <a href="carrinho.php">🛒 Carrinho</a> 
    <a href="pagina_perfil.php">👤 Perfil</a>
    <a href="iniciar_sessao.php">🔐 Iniciar Sessão</a>

    <?php if ($user_id == 1): ?>
        <a id="botao_admin" href="admin_page.php">🛠️ Menu Admin</a>
    <?php endif; ?>
    
</div>

</body>
</html>
