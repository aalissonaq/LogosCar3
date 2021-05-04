<?php
    
    require_once('config.php');
    require_once('session.php');

    if(isset($_POST)){
        //RECEBENDO OS DADOS
        $nome = $_POST['inputNomeCompleto'];
        $matricula = $_POST['inputMatricula'];
        echo '</br>logos@'.$matricula;
        echo '</br>'.$senha;
        $niveluser = $_POST['inputNivel'];
        $estado = $_POST['inputUF'];
        $cidade = $_POST['inputCidade'];
        $senha = md5('Logos'.$estado.'@'.$matricula);
        $isDriver = $_POST['radioMotorista'];
        if($isDriver){
            $num_cnh = $_POST['inputNumCNH'];
            $categoria = $_POST['inputCategoria'];
            $data_emissao = $_POST['inputDataEmissao'];
            $data_vencimento = $_POST['inputDataValidade'];
        } else{
            $num_cnh = NULL;
            $categoria = NULL;
            $data_emissao = NULL;
            $data_vencimento = NULL;
        }

        // PASSANDO OS PARÂMETROS PARA O PDO
        try{
            $query = $bd->prepare('INSERT INTO tb_users (matr_user,nome_user,senha,uf,cidade,nivel,disponivel,motorista,cnh,cat_cnh,data_emissao,data_validade,ativo,resp_criar) VALUES (:matr_user,:nome_user,:senha,:uf,:cidade,:nivel,:disponivel,:motorista,:cnh,:cat_cnh,:data_emissao,:data_validade,1,:resp_criar)');
            $query->bindParam(':matr_user',$matricula);
            $query->bindParam(':nome_user',$nome);
            $query->bindParam(':senha',$senha);
            $query->bindParam(':uf',$estado);
            $query->bindParam(':cidade',$cidade);
            $query->bindParam(':nivel',$niveluser);
            $query->bindParam(':disponivel',$isDriver);
            $query->bindParam(':motorista',$isDriver);
            $query->bindParam(':cnh',$num_cnh);
            $query->bindParam(':cat_cnh',$categoria);
            $query->bindParam(':data_emissao',$data_emissao);
            $query->bindParam(':data_validade',$data_vencimento);
            $query->bindParam(':resp_criar',$user);
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
            $acao = 'adicionou um novo usuário: nome '.$nome.', de matrícula '.$matricula.' com o nível '.$niveluser;
            $save->bindParam(':acao',$acao);
            $save->execute();
        } catch(PDOException $e){
            echo $e->getMessage();
        }

        header('Location: usuarios.php?add=1');

    } else{
        echo 'Ocorreu um erro interno';
        header('Location: usuarios.php?add=0');
    }
?>