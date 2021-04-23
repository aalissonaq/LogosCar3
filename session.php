<?php
    session_start();
    require_once('src/conn/connect.php');
    date_default_timezone_set('America/Fortaleza');
    $mes = date('m');
    if($mes=='09'){
        echo '<style>#nav,#footer{background-color:#cfa306 !important;}</style>';
    }
    if($mes=='10'){
        echo '<style>#nav,#footer{background-color:#ff4787 !important;}</style>';
    }
    if($mes=='11'){
        echo '<style>#nav,#footer{background-color:#0646cf !important;}</style>';
    }
    
    if($_SESSION == '' || $_SESSION == NULL){
        header('Location: /logoscar3/index.php');
        die();
    } else{
        $user = $_SESSION['user'];
        $myuf = $_SESSION['uf'];
        $mycidade = $_SESSION['cidade'];
        $level = $_SESSION['nivel'];
        $sess = $bd->prepare('SELECT * FROM tb_users WHERE id_user = :id');
        $sess->bindParam(':id',$user);
        $sess->execute();
        $result = $sess->fetch();
        $nome_completo = $result['nome_user'];
        $nome_curto = explode(' ', $nome_completo);
    }
?>