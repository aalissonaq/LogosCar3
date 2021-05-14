<?php

    require_once('isMobile.php');
    if($_POST['act']=='check'){
        try{
            $idAbast = $_POST['id'];
            require_once('session.php');
            $query = $bd->prepare('SELECT u.nome_user, u.id_user, v.id_veiculo, v.montadora, v.modelo, v.placa, v.alias, v.renavam, a.* FROM tb_abastecimento as a, tb_users as u, tb_veiculo as v WHERE a.id_motorista = u.id_user AND a.id_veiculo = v.id_veiculo AND a.id_abastecimento = :id_abastecimento');
            $query->bindParam(':id_abastecimento',$idAbast);
            $query->execute();
            if( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ){
                $retorno = array(
                    "result"        =>          "ok",
                    "id_abast"      =>          $idAbast,
                    "carro_id"      =>          $row['id_veiculo'],
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