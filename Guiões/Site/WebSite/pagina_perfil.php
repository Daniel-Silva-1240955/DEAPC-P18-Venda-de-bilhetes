<?php
session_start();
/*
Fazer confirmação e redirecionar com popup a dar erro*/
if (!isset($_SESSION['user_id'])) {
    die("Utilizador não autenticado. Por favor, inicie sessão.");
}

$user_id = $_SESSION['user_id'];

try {
    $db = new SQLite3('../DataBase/venda_bilhetes.db');

    // Dados pessoais
    $stmt = $db->prepare('SELECT * FROM dados_cliente WHERE user_id = :user_id');
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    if (!$row) {
        die("Utilizador não encontrado.");
    }

    $name = $row['nome'];
    $email = $row['email'];
    $num_cc = $row['num_cc'];
    $val_cc = $row['validade_cc'];
    $cvv_cc = $row['cvv_cc'];

    // Histórico
    $stmt = $db->prepare('SELECT * FROM historico WHERE user_id = :user_id ORDER BY data_compra DESC');

    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $result = $stmt->execute();

    $bilhetes_hist = [];
    while ($bilhete = $result->fetchArray(SQLITE3_ASSOC)) {
        $bilhetes_hist[] = $bilhete;
    }

} catch (Exception $e) {
    echo "Erro ao aceder à base de dados: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="styles/pagina_perfil.css">
  <title>Perfil do Utilizador</title>
</head>
<body>
  <!-- Pop-up Editar Dados -->
  <?php if (isset($_GET['editar']) && $_GET['editar'] == '1'): ?>
    <div id="popup" class="popup">
        <div class="popup-content">
            <h2>Editar Dados</h2>
            <form action="php_scripts/edit_profile.php" method="POST">
                <input name="palavrapasse" type="password" id="password" value="********" required>

                <!-- Campo de email -->
                <!--<input name="email" type="email" value="<?= htmlspecialchars($email); ?>" required> -->

                <!-- Campo de número de cartão -->
                <input name="num_cc" type="tel" maxlength="19" value="<?= htmlspecialchars($num_cc); ?>" required>

                <!-- Validade e CVV -->
                <div class="validadeCVV">
                    <input name="validade_cc" class="data_validade" type="month" value="<?= htmlspecialchars($val_cc); ?>" required>
                    <input name="cvv_cc" class="cvv" type="text" value="<?= htmlspecialchars($cvv_cc); ?>" maxlength="3" required>
                </div>

                <!-- Bloco de botões -->
                <div class="popup-buttons">
                    <button type="submit" class="popup-button">Confirmar</button>
                    <a href="pagina_perfil.php" class="popup-button">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

  <!-- Pop-up Dados Editados -->
  <?php if (isset($_GET['editar']) && $_GET['editar'] == '2'): ?>
      <div id="popup" class="popup">
          <div class="popup-content">
            <?php if (isset($_GET['success']) && $_GET['success'] == '0'): ?>
              <h2>Erro ao Editar Dados</h2>
            <?php endif; ?>
            <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
              <h2>Dados editados com sucesso</h2>
            <?php endif; ?>
              <a href="pagina_perfil.php" class="popup-button">Continuar</a>
          </div>
      </div>
  <?php endif; ?>
<div class="sidebar">
    <div>
        <h2>Perfil</h2>

        <div class="info">
            <label>Nome</label>
            <p><?= htmlspecialchars($name) ?></p>
        </div>

        <div class="info">
            <label>Email</label>
            <p><?= htmlspecialchars($email) ?></p>
        </div>

        <div class="info">
            <label>Nº Cartão</label>
            <p><?= htmlspecialchars($num_cc) ?></p>
        </div>

        <div class="info">
            <label>Validade</label>
            <p><?= htmlspecialchars($val_cc) ?></p>
        </div>

        <div class="info">
            <label>CVV</label>
            <p><?= htmlspecialchars($cvv_cc) ?></p>
        </div>
        <a href="pagina_perfil.php?editar=1" class="edit-button">📝 Editar</a>
    </div>

    <!-- Bloco dos botões -->
        
        <a href="index.php" class="back-button">⬅ Voltar</a>
</div>

<div class="main">
  <h2>Histórico de Bilhetes</h2>

  <?php if (count($bilhetes_hist) === 0): ?>
    <div class="no-history">Ainda não comprou bilhetes.</div>
  <?php else: ?>
    <?php foreach ($bilhetes_hist as $b): ?>
      <div class="bilhete">
        <img src="images/bilheteimg.png" alt="Bilhete">
        <div class="bilhete-info">
          <strong><?= htmlspecialchars($b['nome']) ?></strong><br>
          <?= htmlspecialchars($b['morada']) ?><br>
          <?= date('d/m/Y', strtotime($b['dia'])) ?><br>
          <?= $b['quantidade'] ?> × <?= number_format($b['preco'], 2) ?> €<br>
          <em>Total: <?= number_format($b['quantidade'] * $b['preco'], 2) ?> €</em>
          <em>Comprado em: <?= date('d/m/Y H:i', strtotime($b['data_compra'])) ?></em>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

</body>
</html>
