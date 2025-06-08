<?php
//Cria todas as tabelas necessárias para a execução do website
try {
    //Acede à base de dados
    $db = new SQLite3('../../DataBase/venda_bilhetes.db');
    
    //$db->exec("DROP TABLE IF EXISTS dados_cliente");
    //Cria a tabela com os dados de cada cliente
    $db->exec("CREATE TABLE IF NOT EXISTS dados_cliente (
        user_id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        email TEXT NOT NULL,
        palavrapasse TEXT NOT NULL,
        num_cc INTEGER,
        validade_cc TEXT NOT NULL,
        cvv_cc INTEGER
    )");
    //$db->exec("DROP TABLE IF EXISTS lista_bilhetes");
    //Cria a tabela com os dados de cada bilhete
    $db->exec("CREATE TABLE IF NOT EXISTS lista_bilhetes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        morada TEXT NOT NULL,
        dia TEXT NOT NULL,
        preco REAL NOT NULL,
        limite INTEGER,
        disponiveis INTEGER
    )");
    //Apaga a tabela carrinhos para a atualizar
    //$db->exec("DROP TABLE IF EXISTS carrinhos");
    //Cria a tabela com os dados de cada bilhete adicionado ao carrinho e o seu utilizador respetivo
    $db->exec("CREATE TABLE IF NOT EXISTS carrinhos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        id_bilhete INTEGER,
        quantidade INTEGER
    )");
    //Apaga a tabela historico para a atualizar
    //$db->exec("DROP TABLE IF EXISTS historico");
    //Cria a tabela que guarda o historico de compras de todos os clientes
    $db->exec("CREATE TABLE IF NOT EXISTS historico (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        id_bilhete INTEGER,
        nome TEXT NOT NULL,
        morada TEXT NOT NULL,
        dia TEXT NOT NULL,
        preco REAL NOT NULL,
        quantidade INTEGER,
        data_compra TEXT
    )");
    echo "✅ Database and tables created.";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
