<?php
    require_once('config.php');
    require_once('session.php');
    //capturando a data de hoje para converter pra objeto DateTime
    $hoje = date("d/m/Y");
    $d1 = DateTime::createFromFormat('d/m/Y', $hoje);
    $verificador = 0;

    
    if($level != 'MTR'){
        $query = $bd->prepare('SELECT * from tb_users WHERE motorista = 1 AND uf = :uf');
        $query->bindParam(':uf',$myuf);
    } else {
        $query = $bd->prepare('SELECT * from tb_users WHERE motorista = 1');
    }
    $query->execute();
    $motoristas = $query->fetchAll(PDO::FETCH_OBJ);
    foreach($motoristas as $motorista){
        // capturando data de validade como objeto DateTIme
        $data_validade = date('d/m/Y', strtotime($motorista->data_validade));
        $d2 = DateTime::createFromFormat('d/m/Y', $data_validade);
        //calculando a diferença entre as datas
        $diff=date_diff($d2,$d1);
        //checar se há condutores com carteira a vencer
        if(($d2->format("Y-m-d") < $d1->format("Y-m-d"))){
            if($diff->format("%a")<=30){
                echo '<div class="alert alert-logoscar alert-warning" role="alert" width="60"> A CNH de '.$motorista->nome_user.' venceu '.$diff->format("%a").' dia(s) atrás. Providencie a renovação.</div>';
            } else{
                echo '<div class="alert alert-logoscar alert-danger" role="alert" width="60v> A CNH de '.$motorista->nome_user.' está vencida. Verificar renovação para permanecer dirigindo.</div>';
                $inactiveDriver = $bd->prepare('UPDATE tb_users SET motorista = 0 WHERE id_user = :user');
                $inactiveDriver->bindParam(':user',$motorista->id_user);
                $inactiveDriver->execute();
                $verificador++;
            }
        } else{
            if($diff->format("%a")<=30){
                echo '<div class="alert alert-logoscar alert-warning" role="alert" width="60"> A CNH de '.$motorista->nome_user.' vencerá em '.$diff->format("%a").' dia(s).</div>';
            }
        }
    } if($verificador==0){
        echo '<script> console.log("Sem pendências com motoristas!");</script>';
    } else{
        echo '<script> console.log("Existem pendências com motoristas!");</script>';
    }
?>
