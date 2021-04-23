<?php
    require_once('config.php');
    require_once('session.php');
    $user_agents = array("iPhone","iPad","Android","webOS","BlackBerry","iPod","Symbian","IsGeneric");
    $modelo = "Desktop";
   foreach($user_agents as $user_agent){
        if (strpos($_SERVER['HTTP_USER_AGENT'], $user_agent) !== FALSE) {
            $modelo	= $user_agent;
            break;
        }
   }
    try{
        $meuip = json_decode(file_get_contents('https://api.ipify.org?format=json'),true);
        $save = $bd->prepare('INSERT INTO tb_log (id_user,uf,cidade,ip,dispositivo,data_login,acao) VALUES (:user,:uf,:cidade,:ip,:dispositivo,NOW(),:acao)');
        $save->bindParam(':user',$user);
        $save->bindParam(':uf',$myuf);
        $save->bindParam(':cidade',$mycidade);
        $save->bindParam(':dispositivo',$modelo);
        $save->bindParam(':ip',$meuip['ip']);
        $acao = 'efetuou o logout';
        $save->bindParam(':acao',$acao);
        $save->execute();
    } catch(PDOException $e){
        echo $e->getMessage();
    }
    session_start();
    session_unset();
    session_destroy();
    unset($_SESSION['user']);
    unset($_SESSION['nivel']);
    $_SESSION = array();
    $_SESSION['user'] = NULL;
    $_SESSION['nivel'] = NULL;
    unset($_POST);
    $_POST['inputLogin'] = NULL;
    $_POST['inputSenha'] = NULL;
    header('Location: index.php');
?>