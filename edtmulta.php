<?php
    require_once('config.php');
    require_once('session.php');
    if(isset($_POST)){
        try{
            $id = $_POST['inputIDMulta'];
            $aceite = $_POST['aceitaMulta'];
            $formaPgto = $_POST['inputFormaPgto'];
            $docSigned = $_POST['docSign'];
            if($formaPgto=='av'){
                $condicaoPgto = NULL;
            } else{
                $condicaoPgto = $_POST['inputParcelamento'];
            }
            if($aceite==0){
                $query = $bd->prepare('UPDATE tb_multas SET pago = 2 WHERE id_multa = :id');
                $query->bindParam(':id',$id);
            } else{
                $query = $bd->prepare('UPDATE tb_multas SET pago = 1, aceito = :aceito, forma_pgto = :forma_pgto, condicao_pgto = :condicao_pgto, termo_assinado = :termo_assinado WHERE id_multa = :id');
                $query->bindParam(':id',$id);
                $query->bindParam(':aceito',$aceite);
                $query->bindParam(':forma_pgto',$formaPgto);
                $query->bindParam(':condicao_pgto',$condicaoPgto);
                $query->bindParam(':termo_assinado',$docSigned);
            }
            $query->execute();
        } catch(PDOException $e){
            echo $e->getMessage();
        }
        header('Location: multas.php?edt=1');
    } else{
        echo "Ops! Erro não identificado";
        header('Location: multas.php?edt=0');
    }
?>