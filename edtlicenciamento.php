<?php
    require_once('config.php');
    require_once('session.php');
    if(isset($_POST)){
        try{
            //parameters
            $id        = $_POST['inputIDLic'];
            $ipva      = $_POST['inputEditValorIPVA'];
            $dpvat     = $_POST['inputEditValorDPVAT'];
            $lic       = $_POST['inputEditValorLic'];
            $total     = $_POST['inputEditValorTotal'];
            $dataPgto  = $_POST['inputDataPgto'];
            $formaPgto = $_POST['inputFormaPgto'];
            $docSigned = $_POST['docSign'];
            if($formaPgto=='av'){
                $condicaoPgto = 'À vista';
            } else{
                $condicaoPgto = $_POST['inputParcelamento'];
            }
            // if $docSign is void, it means already exists a doc on MySQl and the user chose
            // to not change. Then, the system don't save anything.
            if($docSigned!=''){
                $query = $bd->prepare('UPDATE tb_licenciamento SET valor_ipva=:valor_ipva,valor_dpvat=:valor_dpvat,valor_lic=:valor_lic,valor_total=:valor_total,data_pgto=:data_pgto,pago=1,condicoes=:condicoes,doc_pgto=:doc_pgto WHERE id_licenciamento = :id');
                $query->bindParam(':id',$id);
                $query->bindParam(':valor_ipva',$ipva);
                $query->bindParam(':valor_dpvat',$dpvat);
                $query->bindParam(':valor_lic',$lic);
                $query->bindParam(':valor_total',$total);
                $query->bindParam(':data_pgto',$dataPgto);
                $query->bindParam(':condicoes',$condicaoPgto);
                $query->bindParam(':doc_pgto',$docSigned);
            } else{
                $query = $bd->prepare('UPDATE tb_licenciamento SET valor_ipva=:valor_ipva,valor_dpvat=:valor_dpvat,valor_lic=:valor_lic,valor_total=:valor_total,data_pgto=:data_pgto,pago=1,condicoes=:condicoes WHERE id_licenciamento = :id');
                $query->bindParam(':id',$id);
                $query->bindParam(':valor_ipva',$ipva);
                $query->bindParam(':valor_dpvat',$dpvat);
                $query->bindParam(':valor_lic',$lic);
                $query->bindParam(':valor_total',$total);
                $query->bindParam(':data_pgto',$dataPgto);
                $query->bindParam(':condicoes',$condicaoPgto);
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
            $acao = 'Atualizou o licenciamento de ID: '.$id;
            $save->bindParam(':acao',$acao);
            $save->execute();
            echo 'QAP!';
        } catch(PDOException $e){
            echo $e->getMessage();
        }
        header('Location: licenciamento.php?edt=1');
    } else{
        echo "Ops! Erro não identificado";
        header('Location: licenciamento.php?edt=0');
    }
?>