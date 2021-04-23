<?php
    require_once('config.php');
    require_once('session.php');
    var_dump($_POST);
    if(isset($_POST)){
        //RECEBENDO OS DADOS
        $uf = $_POST['inputUF'];
        $cidade = $_POST['inputCidade'];
        $montadora = $_POST['inputMontadora'];
        $modelo = $_POST['inputModelo'];
        $alias = $_POST['inputAlias'];
        $ano_fab = $_POST['inputAnoFab'];
        $ano_mod = $_POST['inputAnoMod'];
        $placa = $_POST['inputPlaca'];
        $renavam = $_POST['inputRENAVAM'];
        $chassi = $_POST['inputChassi'];
        $cor = $_POST['inputCor'];
        $criador = $user;
        $data_recebimento = $_POST['inputDataRecebimento'];
        $km = $_POST['inputKMRecebimento'];
        $proprietario = $_POST['inputProprietario'];


        //PASSANDO OS PARÂMETROS PARA O PDO
        try{
            $query = $bd->prepare('INSERT INTO tb_veiculo (id_uf, id_cidade, montadora, modelo, alias, ano_fab, modelo_fab, placa, renavam, chassi, cor, resp_criacao, data_criacao, data_recebimento, kilometragem, proprietario, status, ativo) VALUES (:id_uf, :id_cidade, :montadora, :modelo, :alias, :ano_fab, :modelo_fab, :placa, :renavam, :chassi, :cor, :resp_criacao, NOW(), :data_recebimento, :kilometragem, :proprietario, 1, 1)');
            $query->bindParam(':id_uf',$uf);
            $query->bindParam(':id_cidade',$cidade);
            $query->bindParam(':montadora',$montadora);
            $query->bindParam(':modelo',$modelo);
            $query->bindParam(':alias',$alias);
            $query->bindParam(':ano_fab',$ano_fab);
            $query->bindParam(':modelo_fab',$ano_mod);
            $query->bindParam(':placa',$placa);
            $query->bindParam(':renavam',$renavam);
            $query->bindParam(':chassi',$chassi);
            $query->bindParam(':cor',$cor);
            $query->bindParam(':resp_criacao',$criador);
            $query->bindParam(':data_recebimento',$data_recebimento);
            $query->bindParam(':kilometragem',$km);
            $query->bindParam(':proprietario',$proprietario);
            $query->execute();
        } catch(PDOException $e){
            echo $e->getMessage();
        }
        // Dedo Duro Mode: ON
        $user_agents = array("iPhone","iPad","Android","webOS","BlackBerry","iPod","Symbian","IsGeneric");
        $model = "Desktop";
        foreach($user_agents as $user_agent){
            if (strpos($_SERVER['HTTP_USER_AGENT'], $user_agent) !== FALSE) {
                $model	= $user_agent;
                break;
            }
        }
        try{
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
        } catch(PDOException $e){
            echo $e->getMessage();
        }

        header('Location: veiculos.php?add=1');

    } else{
        echo 'Ocorreu um erro interno';
        header('Location: veiculos.php?add=0');
    }
?>