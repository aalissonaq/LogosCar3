<?php
    require_once('isMobile.php');
    if($_POST['act']=='check'){
        try{
            if($isMobile){
                //recebe data cheia
                $dataFull = str_replace("T"," ", $_POST['data']);
                //separa data de horário
                $separaDataEHora = explode(" ", $dataFull);
                //captura em $horario somente o horario repassado
                $horario = $separaDataEHora[1];
                //divide o horário em hora, minuto e segundo (se existir segundo)
                $dividoHorario = explode(":", $horario);
                //se for menor que 3, indica que só tem hora e minuto no datetime repassado
                if(count($dividoHorario)<3){
                    //acrescenta-se os segundos
                    $data = str_replace("T"," ",$_POST['data']).':00';
                } else{
                    //deixa como está
                    $data = str_replace("T"," ",$_POST['data']);
                }
            } else{
                //quando não é mobile, por padrão vem os segundos zerados.
                $data = str_replace("T"," ",$_POST['data']);
            }
            $veiculo = $_POST['carro'];
            require_once('session.php');
            if($level=='MTR'){
                $sql="SELECT l.*, u.matr_user, u.nome_user, v.id_veiculo, v.proprietario, v.montadora, v.placa, v.modelo, v.status FROM tb_viagem as l, tb_users as u, tb_veiculo as v WHERE u.matr_user = l.id_user AND v.id_veiculo = l.id_veiculo AND l.id_veiculo = ".$veiculo." AND ' ".$data." ' BETWEEN l.data_lancamento AND l.data_retorno";
                $stmt = $bd->prepare($sql);
                $stmt->execute();
            }
            if($level=='ADM'){
                $sql="SELECT l.*, u.matr_user, u.nome_user, v.id_veiculo, v.proprietario, v.montadora, v.placa, v.modelo, v.status FROM tb_viagem as l, tb_users as u, tb_veiculo as v WHERE u.matr_user = l.id_user AND v.id_veiculo = l.id_veiculo AND l.id_veiculo = ".$veiculo." AND ' ".$data." ' BETWEEN l.data_lancamento AND l.data_retorno AND l.id_uf = ".$myuf;
                $stmt = $bd->prepare($sql);
                $stmt->execute();
            }
            if( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ){
                $retorno = array(
                    "result"        =>          "ok",
                    "user_id"       =>          $row['id_user'],
                    "carro_id"       =>          $row['id_carro'],
                    "nome"          =>          $row['nome_user'],
                    "rota"          =>          $row['rota'],
                    "alter_rota"    =>          $row['alter_rota'],
                    "id_viagem"     =>          $row['id_viagem']
                );
            } else{
                $retorno = array(
                    "result"          =>          null
                );
            }
            echo json_encode(["status"=>true,"msg"=>"Json enviado com sucesso!","data"=>$retorno]);
            exit;
        } catch(PDOException $e){
            $erro = $e->getMessage();
            echo json_encode(["status"=>false,"msg"=>"Ops! Houve algum erro...","data"=>$erro]);
            exit;
        }
    }
?>