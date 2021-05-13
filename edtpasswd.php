<?php
    require_once('config.php');
    require_once('session.php');

    if(isset($_POST)){
        try{
            $id = $_POST['inputID'];
            $senha = @md5($_POST['novaSenha']);
            $sql = 'UPDATE tb_users SET senha = :senha WHERE id_user = :id';
            $query = $bd->prepare($sql);
            $query->bindParam(':senha',$senha);
            $query->bindParam(':id',$id);
            $query->execute();
        } catch(PDOException $e){
            echo $e->getMessage();
        }
        // Dedo Duro Mode: ON
        try{
            $user_agents = array("iPhone","iPad","Android","webOS","BlackBerry","iPod","Symbian","IsGeneric");
            $model = "Desktop";
            foreach($user_agents as $user_agent){
                if (strpos($_SERVER['HTTP_USER_AGENT'], $user_agent) !== FALSE) {
                    $model	= $user_agent;
                    break;
                }
            }
            $meuip = json_decode(file_get_contents('https://api.ipify.org?format=json'),true);
            $save = $bd->prepare('INSERT INTO tb_log (id_user,uf,cidade,ip,dispositivo,data_login,acao) VALUES (:user,:uf,:cidade,:ip,:dispositivo,NOW(),:acao)');
            $save->bindParam(':user',$user);
            $save->bindParam(':uf',$myuf);
            $save->bindParam(':cidade',$mycidade);
            $save->bindParam(':dispositivo',$model);
            $save->bindParam(':ip',$meuip['ip']);
            $acao = 'Alterou senha do usuário de ID '.$id;
            $save->bindParam(':acao',$acao);
            $save->execute();
        } catch(PDOException $e){
            echo $e->getMessage();
        }
        header('Location: usuarios.php?psw=1');
    } else{
        echo "Ops! Erro não identificado";
        header('Location: usuarios.php?psw=0');
    }
    

?>