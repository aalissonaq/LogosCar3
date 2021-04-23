<?php
    require_once('config.php');
    require_once('session.php');

    if(isset($_POST)){
        $id_carro = $_POST['inputIDCarro'];
        $uf = $_POST['inputUFBase'];
        $cidade = $_POST['inputCidadeBase'];
        $km_atual = $_POST['inputKMatual'];
        $tipo_manut = $_POST['inputTipoManutencao'];
        $local_manut = $_POST['inputLocalManut'];
        if($tipo_manut){
            $kmprog = $_POST['inputKMProg'];
        } else{
            $kmprog = NULL;
        }
        $data_manut = str_replace("T"," ",$_POST['inputDataManut']);
        $descricao = $_POST['inputDescricao'];
    try{
        $query = $bd->prepare('INSERT INTO tb_manutencao (status_manut,id_veiculo,uf,cidade,km_ida,tipo_manut,local_manut,km_programada,data_manut,descricao_manut) VALUES (1,:id_veiculo,:uf,:cidade,:km_ida,:tipo_manut,:local_manut,:km_programada,:data_manut,:descricao_manut)');
        $query->bindParam(':id_veiculo',$id_carro);
        $query->bindParam(':uf',$uf);
        $query->bindParam(':cidade',$cidade);
        $query->bindParam(':km_ida',$km_atual);
        $query->bindParam(':tipo_manut',$tipo_manut);
        $query->bindParam(':local_manut',$local_manut);
        $query->bindParam(':km_programada',$kmprog);
        $query->bindParam(':data_manut',$data_manut);
        $query->bindParam(':descricao_manut',$descricao);
        
        $query->execute();
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }

    try{
        $query = $bd->prepare('SELECT * FROM tb_viagem WHERE em_andamento = 1 AND id_veiculo = :id_veiculo');
        $query->bindParam(':id_veiculo',$id_carro);
        $query->execute();
        $emrota = $query->rowCount();
        if($emrota>0){
            $corridaAndamento = $query->fetch();
            // finalizar a viagem
            $query = $bd->prepare('UPDATE tb_viagem SET em_andamento = 0, data_retorno = now(), km_fim = :km WHERE id_user = :matr_user AND id_veiculo = :id_veiculo');
            $query->bindParam(':km',$km_atual);
            $query->bindParam(':matr_user', $corridaAndamento['id_user']);
            $query->bindParam(':id_veiculo',$id_carro);
            $query->execute();
            //liberar o motorista
            $query = $bd->prepare('UPDATE tb_users SET disponivel = 1 WHERE matr_user = :matr_user');
            $query->bindParam(':matr_user', $corridaAndamento['id_user']);
            $query->execute();
        }

        //indicar ao sistema que o carro foi pra manutenção (status = 3)
        $query = $bd->prepare('UPDATE `tb_veiculo` SET `status` = 3 WHERE `id_veiculo` = :id_veiculo');
        $query->bindParam(':id_veiculo',$id_carro);
        $query->execute();
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }
    try{
        // Dedo Duro Mode: ON
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
        if($isProgrammed){
            $acao = $user.' indicou manutenção programada no veículo de ID: '.$carro.'com o km: '.$km_atual;
        } else{
            $acao = $user.' indicou manutenção não programada no veículo de ID: '.$carro.'com o km: '.$km_atual;
        }
        $save->bindParam(':acao',$acao);
        $save->execute();
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }
        header('Location: sys.php?startManut=1');
    } else{
        echo 'Ocorreu um erro interno';
        header('Location: sys.php?startManut=0');
    }
?>