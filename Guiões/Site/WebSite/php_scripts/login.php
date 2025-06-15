<?php

    //Adicionar Popups de Erros de Início de sessão


//Este código recebe os dados de login e tenta iniciar sessão
//Dá erro se não for possível iniciar sessão
session_start();

// Verifica se os dados foram enviados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $palavrapasse = trim($_POST['palavrapasse']);

    // Valida campos
    if (empty($email) || empty($palavrapasse)) {
        die("⚠️ Email e palavra-passe são obrigatórios.");
    }

    try {
        $db = new SQLite3('../../DataBase/venda_bilhetes.db');

        // Prepara e executa a consulta
        $stmt = $db->prepare('SELECT user_id, palavrapasse FROM dados_cliente WHERE email = :email');
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $result = $stmt->execute();
        $user = $result->fetchArray(SQLITE3_ASSOC);

        if ($user && password_verify($palavrapasse, $user['palavrapasse'])) {
            // Iniciar sessão com o ID do utilizador
            $_SESSION['user_id'] = $user['user_id'];
            header("Location: ../index.php"); // Redireciona para a página principal
            exit();
        } else {
            echo "❌ Email ou palavra-passe incorretos.";
        }
    } catch (Exception $e) {
        echo "❌ Erro de base de dados: " . $e->getMessage();
    }
} else {
    echo "⚠️ Método de acesso inválido.";
}
?>
