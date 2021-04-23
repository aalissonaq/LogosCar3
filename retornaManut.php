<?php
    require_once('config.php');
    require_once('session.php');
    var_dump($_POST);
    if(isset($_POST)){
        $id_manut = $_POST['inputIDManut'];
        $id_carro = $_POST['inputIDCarro'];
        $local_manut = $_POST['inputLocalManut'];
        $km_retorno = $_POST['inputKMRetorno'];
        $data_retorno = str_replace("T"," ",$_POST['inputDataRetorno']);
        $descricao = $_POST['inputDescricao'];

        $query = $bd->prepare('UPDATE tb_manutencao SET local_manut = :local_manut, status_manut = 0, km_retorno = :km_retorno, data_retorno = :data_retorno, descricao_manut = :descricao WHERE id_manut = :id_manut AND id_veiculo = :id_veiculo');
        $query->bindParam(':local_manut',$local_manut);
        $query->bindParam(':km_retorno',$km_retorno);
        $query->bindParam(':data_retorno',$data_retorno);
        $query->bindParam(':descricao',$descricao);
        $query->bindParam(':id_manut',$id_manut);
        $query->bindParam(':id_veiculo',$id_carro);
        $query->execute();

        //indicar ao sistema que o carro voltou da manutenção (status = 1)
        $query = $bd->prepare('UPDATE tb_veiculo SET status = 1, kilometragem = :km_retorno WHERE id_veiculo = :id_veiculo');
        $query->bindParam(':km_retorno',$km_retorno);
        $query->bindParam(':id_veiculo',$id_carro);
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
        $acao = $user.' indicou retorno da manutenção no veículo de ID: '.$id_carro.' com o km: '.$km_retorno;
        $save->bindParam(':acao',$acao);
        $save->execute();
        header('Location: sys.php?retornaManut=1');
    } else{
        echo 'Ocorreu um erro interno';
        header('Location: sys.php?retornaManut=0');
    }
?>