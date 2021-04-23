<?php
    define('HOST', 'localhost');
    define('USER', 'root');
    define('PASS', 'password');
    define('DBNAME', 'logoscar3');
    define('PORT', '3306');
    try{
        $bd = new PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, USER, PASS);
        $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    catch (PDOException $e) {
        echo 'ERRO: ', $e->getMessage();
    }
?>
