<?php
    if(isset($_POST['act'])){
        //matricula
        if($_POST['act']=='matr'){
            if(isset($_POST['num'])){
                try{
                    $matr = $_POST['num'];
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
        if($_POST['act']=='cnh'){
            if(isset($_POST['num'])){
                try{
                    $cnh = $_POST['num'];
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
        if($_POST['act']=='id_psw'){
            if(isset($_POST['id'])){
                try{
                    $id = $_POST['id'];
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
        if($_POST['act']=='placa'){
            if(isset($_POST['placa'])){
                try{
                    $placa = $_POST['placa'];
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
        if($_POST['act']=='renavam'){
            if(isset($_POST['renavam'])){
                try{
                    $renavam = $_POST['renavam'];
                    require_once('session.php');
                    $stmt = $bd->prepare('SELECT * FROM tb_veiculo WHERE renavam = :renavam');
                    $stmt->bindParam(':renavam',$renavam);
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
        if($_POST['act']=='chassi'){
            if(isset($_POST['chassi'])){
                try{
                    $chassi = $_POST['chassi'];
                    require_once('session.php');
                    $stmt = $bd->prepare('SELECT * FROM tb_veiculo WHERE chassi = :chassi');
                    $stmt->bindParam(':chassi',$chassi);
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
        if($_POST['act']=='alias'){
            if(isset($_POST['alias'])){
                try{
                    $alias = $_POST['alias'];
                    require_once('session.php');
                    $stmt = $bd->prepare('SELECT * FROM tb_veiculo WHERE alias = :alias');
                    $stmt->bindParam(':alias',$alias);
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