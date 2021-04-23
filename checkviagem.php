<?php
    if($_GET['act']=='check'){
        try{
            $data = str_replace("T"," ",$_GET['data']);
            $veiculo = $_GET['carro'];
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