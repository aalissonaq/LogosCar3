<?php
    var_dump($_POST); 
    //id motorista      --
    //id veiculo        --
    //id_viagem         --
    //data multa        --
    //valor multa       
    // status: pago?
    // cidade da multa
    // uf multa
    // localidade multa
    require_once('config.php');
    require_once('session.php');

    if(isset($_POST)){
        $dataMulta = $_POST['inputDataOcorrencia'].' '. = $_POST['inputHoraOcorrencia'];
        $idVeiculo = $_POST['inputVeiculo'];
        $nomeMotorista = $_POST['inputNomeCondutor'];
        $idMotorista = $_POST['inputIDCondutor'];
        $idVeiculo = $_POST['inputIDVeiculo'];
        $rotaEfetuada = $_POST['inputRotaEfetuada'];
        $idViagem = $_POST['inputIDViagem'];
        $UF = $_POST['inputUF'];
        $cidade = $_POST['inputCidade'];
        $trechoMulta = $_POST['inputTrecho'];
        $valorMulta = $_POST['inputValorMulta'];
        // PASSANDO OS PARÂMETROS PARA O PDO
        try{
            $query = $bd->prepare('INSERT INTO tb_users () VALUES (:)');
            $query->bindParam(':',$);
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