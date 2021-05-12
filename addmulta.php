<?php
    require_once('config.php');
    require_once('session.php');
    if(isset($_POST)){
        $dataMulta = $_POST['inputDataOcorrencia'].' '. $_POST['inputHoraOcorrencia'];
        $idMotorista = $_POST['inputIDCondutor'];
        $idVeiculo = $_POST['inputIDVeiculo'];
        $idViagem = $_POST['inputIDViagem'];
        $UF = $_POST['inputUF'];
        $cidade = $_POST['inputCidade'];
        $trechoMulta = $_POST['inputTrecho'];
        $valorMulta = $_POST['inputValorMulta'];
        // PASSANDO OS PARÂMETROS PARA O PDO
        try{
            $query = $bd->prepare('INSERT INTO tb_multas (id_veiculo, id_motorista, id_viagem, data_multa, local_multa, uf_multa, cidade_multa, valor_multa) VALUES (:id_veiculo, :id_motorista, :id_viagem, :data_multa, :local_multa, :uf_multa, :cidade_multa, :valor_multa)');
            $query->bindParam(':data_multa',$dataMulta);
            $query->bindParam(':id_motorista',$idMotorista);
            $query->bindParam(':id_veiculo',$idVeiculo);
            $query->bindParam(':id_viagem',$idViagem);
            $query->bindParam(':uf_multa',$UF);
            $query->bindParam(':cidade_multa',$cidade);
            $query->bindParam(':local_multa',$trechoMulta);
            $query->bindParam(':valor_multa',$valorMulta);
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
            $acao = 'Add multa a ID '.$idMotorista.' no veículo ID '.$idVeiculo.', na viagem ID '.$idViagem;
            $save->bindParam(':acao',$acao);
            $save->execute();
        } catch(PDOException $e){
            echo $e->getMessage();
        }

        header('Location: multas.php?add=1');

    } else{
        echo 'Ocorreu um erro interno';
        header('Location: multas.php?add=0');
    }
?>