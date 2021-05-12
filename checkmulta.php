<?php
    require_once('isMobile.php');
    if($_POST['act']=='multa'){
        try{
            $idmulta = $_POST['idmulta'];
            require_once('session.php');
            if($level=='MTR'){
                $stmt = $bd->prepare('SELECT m.valor_multa as valor, m.data_multa as dataMulta, m.local_multa as localMulta, u.nome_user as infrator, v.montadora as montadora, v.modelo as modelo, v.placa as placa FROM tb_multas as m, tb_users as u, tb_veiculo as v WHERE m.id_motorista = u.id_user AND m.id_veiculo = v.id_veiculo AND m.id_multa = :id_multa');
                $stmt->bindParam(':id_multa',$idmulta);
                $stmt->execute();
            }
            if($level=='ADM'){
                $stmt = $bd->prepare('SELECT m.valor_multa, m.data_multa, m.local_multa, u.nome_user, v.montadora, v.modelo, v.placa FROM tb_multas as m, tb_users as u, tb_veiculo as v WHERE m.id_motorista = u.id_user AND m.id_veiculo = v.id_veiculo AND m.id_multa = :id_multa AND m.uf_multa = :uf_multa');
                $stmt->bindParam(':id_multa',$idmulta);
                $stmt->bindParam(':uf_multa',$myuf);
                $stmt->execute();
            }
            if( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ){
                $retorno = array(
                    "result"        =>          "ok",
                    "idmulta"       =>          $idmulta,
                    "valor"         =>          $row['valor'],
                    "dataMulta"     =>          $row['dataMulta'],
                    "localMulta"    =>          $row['localMulta'],
                    "infrator"      =>          $row['infrator'],
                    "montadora"     =>          $row['montadora'],
                    "modelo"        =>          $row['modelo'],
                    "placa"         =>          $row['placa']
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