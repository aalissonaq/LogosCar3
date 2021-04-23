<?php
    if(isset($_GET['act'])){
        //matricula
        if($_GET['act']=='matr'){
            if(isset($_GET['num'])){
                try{
                    $matr = $_GET['num'];
                    require_once('session.php');
                    $stmt = $bd->prepare('SELECT * FROM tb_users WHERE matr_user = :matr');
                    $stmt->bindParam(':matr',$matr);
                    $stmt->execute();
                    if( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ){
                        $retorno = array(
                            "matr"        =>  $row['matr_user']
                        );
                    } else{
                        $retorno = array(
                            "matr"        =>  null
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
        }
        //cnh
        if($_GET['act']=='cnh'){
            if(isset($_GET['num'])){
                try{
                    $cnh = $_GET['num'];
                    require_once('session.php');
                    $stmt = $bd->prepare('SELECT * FROM tb_users WHERE cnh = :cnh');
                    $stmt->bindParam(':cnh',$cnh);
                    $stmt->execute();
                    if( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ){
                        $retorno = array(
                            "cnh"        =>  $row['cnh']
                        );
                    } else{
                        $retorno = array(
                            "cnh"        =>  null
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
        }
        if($_GET['act']=='id_psw'){
            if(isset($_GET['id'])){
                try{
                    $id = $_GET['id'];
                    require_once('session.php');
                    $stmt = $bd->prepare('SELECT * FROM tb_users WHERE id_user = :id');
                    $stmt->bindParam(':id',$id);
                    $stmt->execute();
                    if( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ){
                        $retorno = array(
                            "nome"           =>  $row['nome_user'],
                            "matr"        =>  $row['matr_user']
                        );
                    } else{
                        $retorno = array(
                            "nome"           =>  null,
                            "matr"        =>  null
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
        }
        if($_GET['act']=='placa'){
            if(isset($_GET['placa'])){
                try{
                    $placa = $_GET['placa'];
                    require_once('session.php');
                    $stmt = $bd->prepare('SELECT * FROM tb_veiculo WHERE placa = :placa');
                    $stmt->bindParam(':placa',$placa);
                    $stmt->execute();
                    if( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ){
                        $retorno = true;
                    } else{
                        $retorno = false;
                    }
                    echo json_encode(["status"=>true,"msg"=>"Json enviado com sucesso!","data"=>$retorno]);
                    exit;
                } catch(PDOException $e){
                    $erro = $e->getMessage();
                    echo json_encode(["status"=>false,"msg"=>"Ops! Houve algum erro...","data"=>$erro]);
                    exit;
                }
            }
        }
    }

?>