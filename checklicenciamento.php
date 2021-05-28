<?php
    require_once('isMobile.php');
    require_once('session.php');
    if($_POST['act']=='lics'){
        try{
            $idLic = $_POST['idlic'];
            if($level=='MTR'){
                $stmt = $bd->prepare('SELECT v.id_veiculo, v.montadora, v.modelo, v.placa, v.alias, l.* FROM tb_licenciamento as l, tb_veiculo as v WHERE v.id_veiculo = l.id_veiculo AND l.id_licenciamento = :id_licenciamento');
                $stmt->bindParam('id_licenciamento',$idLic);
                $stmt->execute();
            }
            if($level=='ADM'){
                $stmt = $bd->prepare('SELECT v.id_veiculo, v.montadora, v.modelo, v.placa, v.alias, l.* FROM tb_licenciamento as l, tb_veiculo as v WHERE v.id_veiculo = l.id_veiculo AND l.id_licenciamento = :id_licenciamento AND m.uf_multa = :uf_multa');
                $stmt->bindParam(':id_licenciamento',$idLic);
                $stmt->bindParam(':uf_multa',$myuf);
                $stmt->execute();
            }
            if( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ){
                $retorno = array(
                    "result"        =>      "ok",
                    "idLic"         =>      $idLic,
                    "idCarro"       =>      $row['id_veiculo'],
                    "detailCar"     =>      $row['montadora'].' '.$row['modelo'].' ('.$row['placa'].')',
                    "valorIPVA"     =>      $row['valor_ipva'],
                    "valorDPVAT"    =>      $row['valor_dpvat'],
                    "valorLic"      =>      $row['valor_lic'],
                    "valorTotal"    =>      $row['valor_total'],
                    "anoLic"        =>      $row['ano_lic'],
                    "dataPgto"      =>      $row['data_pgto'],
                    "pago"          =>      $row['pago'],
                    "condicoes"     =>      $row['condicoes'],
                    "doc"           =>      $row['doc_pgto']
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
    if($_POST['act']=='verDoc'){
        try{
            $idLic = $_POST['idlic'];
            $stmt = $bd->prepare('SELECT doc_pgto FROM tb_licenciamento WHERE id_licenciamento = :id_licenciamento');
            $stmt->bindParam(':id_licenciamento',$idLic);
            $stmt->execute();
            if( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ){
                $retorno = array(
                    "result"        =>        "ok",
                    "idlic"         =>        $idLic,
                    "valor"         =>        $row['doc_pgto']
                );
            } else{
                $retorno = array(
                    "result"        =>        null
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