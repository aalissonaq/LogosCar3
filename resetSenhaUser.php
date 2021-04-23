<?php
    require_once('config.php');
    require_once('session.php');

    if(isset($_POST)){
        //RECEBENDO OS DADOS
        $user = $_POST['inputUserID'];


        //PASSANDO OS PARÂMETROS PARA O PDO
        $query = $bd->prepare('INSERT INTO tb_veiculo (id_uf, id_cidade, montadora, modelo, alias, ano_fab, modelo_fab, placa, renavam, chassi, cor, resp_criacao, data_recebimento, kilometragem, proprietario, status, ativo) VALUES (:id_uf, :id_cidade, :montadora, :modelo, :alias, :ano_fab, :modelo_fab, :placa, :renavam, :chassi, :cor, :resp_criacao, :data_recebimento, :kilometragem, :proprietario, 1, 1)');
        $query->bindParam(':id_uf',$uf);
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
        $acao = 'adicionou o carro '.$montadora.' '.$modelo.' de placa '.$placa.' à lista de veículos';
        $save->bindParam(':acao',$acao);
        $save->execute();

        header('Location: veiculos.php?add=1');

    } else{
        echo 'Ocorreu um erro interno';
        header('Location: veiculos.php?add=0');
    }
?>