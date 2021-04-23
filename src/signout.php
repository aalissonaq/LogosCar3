<?php
    session_start();
    session_unset();
    session_destroy();
    unset($_SESSION['user']);
    unset($_SESSION['nivel']);
    $_SESSION = array();
    $_SESSION['user'] = NULL;
    $_SESSION['nivel'] = NULL;
    header('Location: /conferekm/index.php');
?>