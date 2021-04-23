<?php
    require_once('config.php');
    require_once('session.php');
    
    if(isset($_POST)){
        $ufbase = $_POST['inputUFBase'];
        $cidadebase = $_POST['inputCidadeBase'];
        $kmatual = $_POST['inputKMatual'];
        $momentoSaida = str_replace("T"," ", $_POST['inputHoraSaida']);
        $motorista = $_POST['inputDriver'];
        $rota = $_POST['inputRota'];
        $carro = $_POST['inputIDCarro'];

        try {
            $query = $bd->prepare('INSERT INTO tb_viagem (id_user,id_veiculo,id_uf,id_cidade,em_andamento,rota,data_lancamento,km_inicio) VALUES (:id_user,:id_veiculo,:id_uf,:id_cidade,1,:rota,:lancamento,:km_inicio)');
            $query->bindParam(':id_user',$motorista);
            $query->bindParam(':id_veiculo',$carro);
            $query->bindParam(':id_uf',$ufbase);
            $query->bindParam(':id_cidade',$cidadebase);
            $query->bindParam(':rota',$rota);
            $query->bindParam(':lancamento',$momentoSaida);
            $query->bindParam(':km_inicio',$kmatual);
            $query->execute();
        } catch (PDOException $e){
            echo $e->getMessage();
        }
        

        // Indicando ao sistema que o motorista está ocupado:
        $query = $bd->prepare('UPDATE tb_users SET disponivel = false WHERE matr_user = :matr_user');
        $query->bindParam(':matr_user',$motorista);
        $query->execute();

        // Indicando ao sistema que o carro está em rota:
        $query = $bd->prepare('UPDATE tb_veiculo SET status = 2 WHERE id_veiculo = :id_veiculo');
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
        $acao = 'inseriu a rota '.$rota.' ao veículo de id '.$carro.', dirigido por '.$motorista;
        $save->bindParam(':acao',$acao);
        $save->execute();
        
        header('Location: sys.php?startRota=1');
    } else{
        echo 'Ocorreu um erro interno';
        header('Location: sys.php?startRota=0');
    }
?>