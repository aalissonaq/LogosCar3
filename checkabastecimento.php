<?php
    require_once('isMobile.php');
    if($_POST['act']=='check'){
        try{
            $idAbast = $_POST['id'];
            require_once('session.php');
            $query = $bd->prepare('SELECT u.nome_user as nomeUser, u.id_user as idUser, v.id_veiculo as idVeiculo, v.montadora, v.modelo, v.placa, v.alias, a.* FROM tb_abastecimento as a, tb_users as u, tb_veiculo as v WHERE a.id_motorista = u.id_user AND a.id_veiculo = v.id_veiculo AND a.id_abastecimento = :id_abastecimento');
            $query->bindParam(':id_abastecimento',$idAbast);
            $query->execute();
            if( $row = $query->fetch( PDO::FETCH_ASSOC ) ){
                $retorno = array(
                    "result"                    =>          "ok",
                    "id_abast"                  =>          $idAbast,
                    "nomeUser"                  =>          $row['nomeUser'],
                    "idUser"                    =>          $row['idUser'],
                    "idVeiculo"                 =>          $row['idVeiculo'],
                    "litros"                    =>          $row['litros'],
                    "valor_abastecimento"       =>          $row['valor_abastecimento'],
                    "dataAb"                    =>          $row['data_abastecimento'],
                    "km_abastecimento"          =>          $row['km_abastecimento'],
                    "comprovante"               =>          $row['comprovante_abastecimento']
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
    if($_POST['act']=='view'){
        try{
            $idAbast = $_POST['id'];
            require_once('session.php');
            $query = $bd->prepare('SELECT u.nome_user as nomeUser, u.id_user as idUser, v.id_veiculo as idVeiculo, v.montadora, v.modelo, v.placa, v.alias, a.* FROM tb_abastecimento as a, tb_users as u, tb_veiculo as v WHERE a.id_motorista = u.id_user AND a.id_veiculo = v.id_veiculo AND a.id_abastecimento = :id_abastecimento');
            $query->bindParam(':id_abastecimento',$idAbast);
            $query->execute();
            if( $row = $query->fetch( PDO::FETCH_ASSOC ) ){
                $retorno = array(
                    "result"                    =>          "ok",
                    "id_abast"                  =>          $idAbast,
                    "comprovante"               =>          $row['comprovante_abastecimento']
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