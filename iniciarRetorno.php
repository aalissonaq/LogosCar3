<?php
    require_once('config.php');
    require_once('session.php');

    var_dump($_POST);
    
    if(isset($_POST)){
        $id_viagem = $_POST['inputIDviagem'];
        $ufbase = $_POST['inputUFBase'];
        $cidadebase = $_POST['inputCidadeBase'];
        $kmatual = $_POST['inputKMatual'];
        $kmretorno = $_POST['inputKMretorno'];
        $motorista = $_POST['inputDriver'];
        $rota = $_POST['inputRota'];
        $alterRota = $_POST['inputAlteracao'];
        $momentoRetorno = str_replace("T"," ", $_POST['inputHoraRetorno']);
        if($rota == $alterRota){
            $alterRota = NULL;
        }
        $carro = $_POST['inputIDCarro'];

        $query = $bd->prepare('UPDATE tb_viagem SET em_andamento = 0, alter_rota = :alter_rota, data_retorno = :lancamento, km_fim = :km_fim WHERE id_viagem = :id_viagem');
        $query->bindParam(':alter_rota',$alterRota);
        $query->bindParam(':km_fim',$kmretorno);
        $query->bindParam(':id_viagem',$id_viagem);
        $query->bindParam(':lancamento',$momentoRetorno);
        $query->execute();

        // Indicando ao sistema que o motorista está ocupado:
        $query = $bd->prepare('UPDATE tb_users SET disponivel = 1 WHERE matr_user = :matr_user');
        $query->bindParam(':matr_user',$motorista);
        $query->execute();

        // Indicando ao sistema que o carro está em rota:
        $query = $bd->prepare('UPDATE tb_veiculo SET status = 1, kilometragem = :km WHERE id_veiculo = :id_veiculo');
        $query->bindParam(':km',$kmretorno);
        $query->bindParam(':id_veiculo',$carro);
        $query->execute();

        // Dedo Duro Mode: ON
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
        $acao = 'encerrou a rota '.$rota.', de id '.$id_viagem.' ao veículo de id '.$carro.', dirigido por '.$motorista;
        $save->bindParam(':acao',$acao);
        $save->execute();

        header('Location: sys.php?retornoRota=1');
    } else{
        echo 'Ocorreu um erro interno';
        header('Location: sys.php?retornoRota=0');
    }
?>