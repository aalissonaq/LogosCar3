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
        header('Location: usuarios.php?psw=1');
    } else{
        echo "Ops! Erro não identificado";
        header('Location: usuarios.php?psw=0');
    }
    

?>