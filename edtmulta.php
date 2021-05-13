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
            $acao = 'Editou multa de ID '.$id;
            $save->bindParam(':acao',$acao);
            $save->execute();
        } catch(PDOException $e){
            echo $e->getMessage();
        }
        header('Location: multas.php?edt=1');
    } else{
        echo "Ops! Erro não identificado";
        header('Location: multas.php?edt=0');
    }
?>