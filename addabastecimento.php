<?php
    require_once('config.php');
    require_once('session.php');
    /*var_dump($_POST);
    die();*/
    if(count($_POST)>0){
        //recebe data cheia
        $dataFull = str_replace("T"," ", $_POST['inputAddDataAbastecimento']);
        //separa data de horário
        $separaDataEHora = explode(" ", $dataFull);
        //captura em $horario somente o horario repassado
        $horario = $separaDataEHora[1];
        //divide o horário em hora, minuto e segundo (se existir segundo)
        $dividoHorario = explode(":", $horario);
        //se for menor que 3, indica que só tem hora e minuto no datetime repassado
        if(count($dividoHorario)<3){
            //acrescenta-se os segundos
            $dataAbastecimento = str_replace("T"," ",$_POST['inputAddDataAbastecimento']).':00';
        } else{
            //deixa como está
            $dataAbastecimento = str_replace("T"," ",$_POST['inputAddDataAbastecimento']);
        }
        $idMotorista = $_POST['inputAddMotorista'];
        $idVeiculo   = $_POST['inputAddVeiculo'];  
        $litros = $_POST['inputAddLitros'];
        $valorTotal = $_POST['inputAddValorTotal'];
        $km = $_POST['inputAddKM'];
        $docDig = $_POST['inputAddComprovante'];
        // PASSANDO OS PARÂMETROS PARA O PDO
        try{
            $query = $bd->prepare('INSERT INTO tb_abastecimento (id_veiculo,id_motorista,valor_abastecimento,data_abastecimento,km_abastecimento,litros,comprovante_abastecimento) VALUES (:id_veiculo,:id_motorista,:valor_abastecimento,:data_abastecimento,:km_abastecimento,:litros,:comprovante_abastecimento)');
            $query->bindParam(':id_veiculo',$idVeiculo);
            $query->bindParam(':id_motorista',$idMotorista);
            $query->bindParam(':valor_abastecimento',$valorTotal);
            $query->bindParam(':data_abastecimento',$dataAbastecimento);
            $query->bindParam(':km_abastecimento',$km);
            $query->bindParam(':litros',$litros);
            $query->bindParam(':comprovante_abastecimento',$docDig);
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
            $acao = 'Add abastecimento do motorista ID '.$idMotorista.' no veículo ID '.$idVeiculo;
            $save->bindParam(':acao',$acao);
            $save->execute();
        } catch(PDOException $e){
            echo $e->getMessage();
        }
        echo 'ok';
        header('Location: fuel.php?add=1');

    } else{
        echo "ops!";
        echo 'Ocorreu um erro interno';
        header('Location: fuel.php?add=0');
    }
?>