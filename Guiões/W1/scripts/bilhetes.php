<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 

$db = new SQLite3('teste.db')

$db->exec("CREATE TABLE Bilhetes(id INTEGER PRIMARY KEY, nome TEXT, local_ev TEXT, data_ev TEXT, preco INT, limite INT, disp INT)"); 
$db->exec("INSERT INTO Bilhete(nome, preco) VALUES('Plutonio', 20)"); 
$db->exec("INSERT INTO Bilhete(nome, preco) VALUES('Dillaz', 18)");
$db->exec("INSERT INTO Bilhete(nome, preco) VALUES('Nininho', 15)");
 
//Comentário
/*
    Comentário multi-linha
*/






?>