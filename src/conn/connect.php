<?php
    date_default_timezone_set('America/Fortaleza');
    try{
        $bd = new PDO('mysql:host=localhost;dbname=logoscar3;', "root", "password");
        $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    catch (PDOException $e) {
        echo 'ERRO: ', $e->getMessage();
    }
?>
