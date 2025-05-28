<?php
    $db = new SQLite3('dados_utilizadores.db');

$db->exec("CREATE TABLE dados_utilizadores(id INTEGER PRIMARY KEY, nome TEXT, email TEXT)");


?>