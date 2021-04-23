<?php
    
    require_once('config.php');
    require_once('session.php');

    if(isset($_POST)){
        var_dump($_POST);
        //RECEBENDO OS DADOS
        $id = $_POST['inputID'];
        $niveluser = $_POST['inputNivel'];
        $status = $_POST['inputStatus'];
        $estado = $_POST['inputUF'];
        $cidade = $_POST['inputCidade'];
        $isDriver = $_POST['radioMotorista'];
        if(isset($_POST['inputNumCNH'])){
            $num_cnh = $_POST['inputNumCNH'];
        } else{
            $num_cnh = NULL;
        }
        if($isDriver){
            $categoria = $_POST['inputCategoria'];
            $data_emissao = $_POST['inputDataEmissao'];
            $data_vencimento = $_POST['inputDataValidade'];
            if(isset($_POST['inputNumCNH'])){
                $sql = 'UPDATE tb_users SET nivel = :nivel, ativo = :ativo, uf = :uf, cidade = :cidade, motorista = :motorista, cnh = :cnh, cat_cnh = :categoria, data_emissao = :emissao, data_validade = :validade WHERE id_user = :id';
                $query = $bd->prepare($sql);
                $query->bindParam(':cnh',$num_cnh);
            } else{
                $sql = 'UPDATE tb_users SET nivel = :nivel, ativo = :ativo, uf = :uf, cidade = :cidade, motorista = :motorista, cat_cnh = :categoria, data_emissao = :emissao, data_validade = :validade WHERE id_user = :id';
                $query = $bd->prepare($sql);
            }
        } else{
            $categoria = NULL;
            $data_emissao = NULL;
            $data_vencimento = NULL;
            $sql = 'UPDATE tb_users SET nivel = :nivel, ativo = :ativo, uf = :uf, cidade = :cidade, motorista = :motorista, cnh = :cnh, cat_cnh = :categoria, data_emissao = :emissao, data_validade = :validade WHERE id_user = :id';
            $query = $bd->prepare($sql);
            $query->bindParam(':cnh',$num_cnh);
        }

        // PASSANDO OS PARÂMETROS PARA O PDO
        try{
            $query->bindParam(':id',$id);
            $query->bindParam(':nivel',$niveluser);
            $query->bindParam(':ativo',$status);
            $query->bindParam(':uf',$estado);
            $query->bindParam(':cidade',$cidade);
            $query->bindParam(':motorista',$isDriver);
            $query->bindParam(':categoria',$categoria);
            $query->bindParam(':emissao',$data_emissao);
            $query->bindParam(':validade',$data_vencimento);
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
            $acao = 'Editou o usuario de ID: '.$id;
            $save->bindParam(':acao',$acao);
            $save->execute();
        } catch(PDOException $e){
            echo $e->getMessage();
        }

        header('Location: usuarios.php?edt=1');
    } else{
        
        echo 'Ocorreu um erro interno';
        header('Location: usuarios.php?edt=0');
        
    }
?>