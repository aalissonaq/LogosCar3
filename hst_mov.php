
<!DOCTYPE html>
<html>
<?php
    function tratarData($sendData){
        //recebe data cheia
        $dataFull = str_replace("T"," ", $sendData);
        //separa data de horário
        $separaDataEHora = explode(" ", $dataFull);
        //captura em $horario somente o horario repassado
        $horario = $separaDataEHora[1];
        //divide o horário em hora, minuto e segundo (se existir segundo)
        $dividoHorario = explode(":", $horario);
        //se for menor que 3, indica que só tem hora e minuto no datetime repassado
        if(count($dividoHorario)<3){
            //acrescenta-se os segundos
            return $dataF = str_replace("T"," ",$sendData).':00';
        } else{
            //deixa como está
            return $dataF = str_replace("T"," ",$sendData);
        }
    }
    require_once('config.php');
    require_once('session.php');
    require_once('isMobile.php');
    require_once('header.php');
    // listar todos os motoristas
    $query = $bd->prepare(' SELECT nome_user, matr_user FROM tb_users WHERE motorista = true');
    $query->execute();
    $drivers  = $query->fetchAll(PDO::FETCH_OBJ);
    // listar todos os veículos
    $query = $bd->prepare(' SELECT alias, modelo, montadora, placa, id_uf, id_cidade FROM tb_veiculo');
    $query->execute();
    $cars  = $query->fetchAll(PDO::FETCH_OBJ);
    /*echo count($_POST);
    echo '&nbsp;';
    var_dump($_POST);*/
    $qr1='';
    $qr2='';
    $qr3='';
    if(count($_POST)>0){
        //filtro da aba Histórico
        if($_POST['id_search']=='geral'){
            $motorista = $_POST['selectMotorista'];
            $veiculo = $_POST['selectVeiculo'];
            if($_POST['dataDe']==''){
                $dataDe = date('Y-m-d 00:00:00', strtotime('-1 year'));
            } else{
                $dataDe = tratarData($_POST['dataDe']);
            }
            if($_POST['dataAte']==''){
                $dataAte = date('Y-m-d H:i:s');
            } else{
                $dataAte = tratarData($_POST['dataAte']);
            }
            if($motorista!='0'){
                $qr1 .= ' AND u.matr_user = '.$motorista.' ';
            }
            if($veiculo!='0'){
                $qr1 .= ' AND v.placa = '.$veiculo.' ';
            }
            $qr1 .= " AND l.data_retorno BETWEEN '".$dataDe."' AND '".$dataAte."' ";
            //echo 'de: '.$dataDe.'até: '.$dataAte;
            //echo '<br> query: '.$qr1;
        }
        //filtro da aba Veículos - Geral
        if($_POST['id_search']=='vehicle'){
            $veiculo = $_POST['selectVeiculo'];
            if($_POST['dataDe']==''){
                $dataDe = date('Y-m-d 00:00:00', strtotime('-1 year'));
            } else{
                $dataDe = tratarData($_POST['dataDe']);
            }
            if($_POST['dataAte']==''){
                $dataAte = date('Y-m-d H:i:s');
            } else{
                $dataAte = tratarData($_POST['dataAte']);
            }
            if($veiculo!='0'){
                $qr2 .= " AND v.placa = '".$veiculo."' ";
            }
            $qr2 .= " AND m.data_retorno BETWEEN '".$dataDe."' AND '".$dataAte."' ";
            //echo 'de: '.$dataDe.'até: '.$dataAte;
            //echo '<br> query: '.$qr2;
        }
        //filtro da aba Motoristas - Geral
        if($_POST['id_search']=='driver'){
            $motorista = $_POST['selectMotorista'];
            if($_POST['dataDe']==''){
                $dataDe = date('Y-m-d 00:00:00', strtotime('-1 year'));
            } else{
                $dataDe = tratarData($_POST['dataDe']);
            }
            if($_POST['dataAte']==''){
                $dataAte = date('Y-m-d H:i:s');
            } else{
                $dataAte = tratarData($_POST['dataAte']);
            }
            if($motorista!='0'){
                $qr3 .= ' AND u.matr_user = '.$motorista.' ';
            }
            $qr3 .= " AND m.data_retorno BETWEEN '".$dataDe."' AND '".$dataAte."' ";
            /*echo 'de: '.$dataDe.'até: '.$dataAte;
            echo '<br> query: '.$qr3;*/
        }
    }
    try{
    if($level=='MTR')
        $query = $bd->prepare('SELECT l.*, u.matr_user, u.nome_user, v.id_veiculo, v.proprietario, v.montadora, v.placa, v.modelo, v.status FROM tb_viagem as l, tb_users as u, tb_veiculo as v WHERE u.matr_user = l.id_user AND v.id_veiculo = l.id_veiculo '.$qr1.' ORDER BY l.id_viagem DESC');
    else{
        $query = $bd->prepare('SELECT l.*, u.matr_user, u.nome_user, v.id_veiculo, v.proprietario, v.montadora, v.placa, v.modelo, v.status FROM tb_viagem as l, tb_users as u, tb_veiculo as v WHERE u.matr_user = l.id_user AND v.id_veiculo = l.id_veiculo '.$qr1.'AND v.id_uf = :uf ORDER BY l.id_viagem DESC');
        $query->bindParam(':uf',$myuf);
    }
    $query->execute();
    $rotas = $query->fetchAll(PDO::FETCH_OBJ);
    } catch(PDOException $e){
        echo $e->getMessage();
    }
    $dataRota = array(
        "motorista" =>  "",
        "veiculo"   =>  "",
        "placa"     =>  "",
        "rota"      =>  "",
        "distancia" =>  "0",
        "id"        =>  ""
    );
    //SELECT v.alias as alias_carro, v.placa as placa_carro, count(m.id_veiculo) as total_viagem, sum(m.km_fim-m.km_inicio) as dist_total, max(m.km_fim-m.km_inicio) as dist_maior FROM `tb_veiculo` as v, `tb_viagem` as m WHERE m.id_veiculo = v.id_veiculo GROUP BY v.alias, v.placa
    if($level=='MTR')
        $query = $bd->prepare('SELECT v.alias as alias_carro, v.placa as placa_carro, count(m.id_veiculo) as total_viagem, sum(m.km_fim-m.km_inicio) as dist_total, max(m.km_fim-m.km_inicio) as dist_maior FROM `tb_veiculo` as v, `tb_viagem` as m WHERE m.id_veiculo = v.id_veiculo '.$qr2.' GROUP BY v.alias, v.placa');
    else{
        $query = $bd->prepare('SELECT v.alias as alias_carro, v.placa as placa_carro, count(m.id_veiculo) as total_viagem, sum(m.km_fim-m.km_inicio) as dist_total, max(m.km_fim-m.km_inicio) as dist_maior FROM `tb_veiculo` as v, `tb_viagem` as m WHERE m.id_veiculo = v.id_veiculo '.$qr2.' AND v.id_uf = :uf GROUP BY v.alias, v.placa');
        $query->bindParam(':uf',$myuf);
    }
    $query->execute();
    $rotasCarro = $query->fetchAll(PDO::FETCH_OBJ);
    //SELECT u.nome_user as motorista, u.matr_user as matricula, count(m.id_user) as total_viagem, sum(m.km_fim-m.km_inicio) as dist_total, max(m.km_fim-m.km_inicio) as dist_maior FROM `tb_viagem` as m, `tb_users` as u WHERE m.id_user = u.matr_user GROUP BY u.nome_user, u.matr_user
    if($level=='MTR')
        $query = $bd->prepare('SELECT u.nome_user as motorista, u.matr_user as matricula, count(m.id_user) as total_viagem, sum(m.km_fim-m.km_inicio) as dist_total, max(m.km_fim-m.km_inicio) as dist_maior FROM `tb_viagem` as m, `tb_users` as u WHERE m.id_user = u.matr_user '.$qr3.' GROUP BY u.nome_user, u.matr_user');
    else{
        $query = $bd->prepare('SELECT u.nome_user as motorista, u.matr_user as matricula, count(m.id_user) as total_viagem, sum(m.km_fim-m.km_inicio) as dist_total, max(m.km_fim-m.km_inicio) as dist_maior FROM `tb_viagem` as m, `tb_users` as u WHERE m.id_user = u.matr_user '.$qr3.' AND u.uf = :uf GROUP BY u.nome_user, u.matr_user');
        $query->bindParam(':uf',$myuf);
    }
    $query->execute();
    $rotasMotorista = $query->fetchAll(PDO::FETCH_OBJ);
    $somakm=0;
?>

<head>
    <style>
        .filtro-card{
            min-width: 30px;
            align-items: center;
            display: flex;
            margin: 3px !important;
        }
        #ctrl_filtros1, #ctrl_filtros2, #ctrl_filtros3{
            width: 75vw !important;
        }
        <?php if($isMobile){ ?>
        #show1, #show2, #show3{
            width: 75vw !important;
        }
        <?php }?>
    </style>
    <link rel="stylesheet" href="<?php echo BASE;?>/css/configura.css">
</head>
<body>
    <?php
        require_once('nav.php');
    ?>
    <div id="loading" style="display: block" class="loading" align="center">
		<img src="<?php echo BASE;?>/img/preloader.gif"><br>
	    Carregando...
	</div>
    <div id="content" class="content container-fluid justify-content-center text-center" style="display: none">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link <?php if($_POST['id_search']=='geral' || $_POST['id_search']==''){ echo 'active'; }?>" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="true">Histórico</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if($_POST['id_search']=='vehicle'){ echo 'active'; }?>" id="veiculos-tab" data-toggle="tab" href="#veiculos" role="tab" aria-controls="veiculos" aria-selected="false">Veículos - Geral</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if($_POST['id_search']=='driver'){ echo 'active'; }?>" id="motoristas-tab" data-toggle="tab" href="#motoristas" role="tab" aria-controls="motoristas" aria-selected="false">Motoristas - Geral</a>
            </li>
        </ul>
        <div class="tab-content" id="tabs">
            <div class="card tab-pane fade <?php if($_POST['id_search']=='geral' || $_POST['id_search']==''){ echo 'show active'; }?>" id="history" role="tabpanel" aria-labelledby="history-tab">
                <div class="card-header">
                    <h5>Histórico de Movimentação dos Veículos</h5>
                </div>
                <div class="card-body justify-content-center row">
                    <div class="card">
                        <div class="card-header btn" id="ctrl_filtros1">
                            <h6>Filtros</h6>
                        </div>
                        <form action="hst_mov.php" method="post">
                            <input type="hidden" name="id_search" value="geral">
                            <div class="card-body justify-content-center align-center row" id="show1">
                                <div class="control-form card filtro-card">
                                    <label for="selectMotorista">Motorista:</label>
                                    <select name="selectMotorista" id="selectMotorista1">
                                        <option value="0">Selecione...</option>;
                                    <?php
                                        foreach($drivers as $rm){
                                            echo '<option value="'.$rm->matr_user.'">'.$rm->nome_user.'</option>';
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="control-form card filtro-card">
                                    <label for="selectVeiculo">Veículo:</label>
                                    <select name="selectVeiculo" id="selectVeiculo1">
                                        <option value="0">Selecione...</option>;
                                    <?php
                                        foreach($cars as $rc){
                                            echo '<option value="'.$rc->placa.'">'.$rc->alias.' ('.$rc->placa.')</option>';
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="control-form card filtro-card">
                                    <label for="">De:</label>
                                    <input type="datetime-local" name="dataDe" id="dataDe1">
                                </div>
                                <div class="control-form card filtro-card">
                                    <label for="">Até:</label>
                                    <input type="datetime-local" name="dataAte" id="dataAte1">
                                </div><br>
                            </div>
                            <div class="card-body justify-content-center row" id="but1">
                                <button type="submit" class="btn btn-outline-primary col-lg-6"><i class="fas fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <table id="listaHstMov" class="table table-striped table-bordered <?php if($isMobile){ echo 'table-responsive';}?>" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Início Rota</th>
                                <th>Fim Rota</th>
                                <th>Veículo</th>
                                <th>Placa</th>
                                <th>Motorista</th>
                                <th>Rota</th>
                                <th>KM início</th>
                                <th>KM fim</th>
                                <th>KM rodado</th>
                                <th>Status</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($rotas as $rota){
                                    echo '<tr>';
                                    echo '<td>'.$rota->id_viagem.'</td>';
                                    echo '<td>'.date('d/m/Y H:i:s', strtotime($rota->data_lancamento)).'</td>';
                                    if($rota->data_retorno==NULL){
                                        echo '<td> -- </td>';
                                    } else{
                                        echo '<td>'.date('d/m/Y H:i:s', strtotime($rota->data_retorno)).'</td>';
                                    }
                                    echo '<td>'.$rota->montadora.' '.$rota->modelo.'</td>';
                                    echo '<td>'.$rota->placa.'</td>';
                                    echo '<td>'.$rota->nome_user.'</td>';
                                    if($rota->alter_rota == NULL){
                                        echo '<td>'.$rota->rota.'</td>';
                                    } else{
                                        echo '<td>'.$rota->alter_rota.'</td>';
                                    }
                                    echo '<td>'.$rota->km_inicio.'</td>';
                                    if($rota->km_fim==NULL){
                                        echo '<td> -- </td>';
                                    } else{
                                        echo '<td>'.$rota->km_fim.'</td>';
                                    }
                                    $kmtotal = $rota->km_fim - $rota->km_inicio;
                                    $somakm += $kmtotal;
                                    if($rota->em_andamento == 0){
                                        echo '<td>'.$kmtotal.'</td>';
                                        echo '<td><p style="display:none">Finalizado</p><abbr title="Rota Finalizada" class="initialism"><i class="fas fa-check-circle fa-2x" style="color: green"></i></abbr></td>';
                                    } else{
                                        echo '<td> -- </td>';
                                        echo '<td><p style="display:none">Em Andamento</p><abbr title="Em andamento" class="initialism"><i class="fas fa-route fa-2x" style="color: blue"></i></abbr></td>';
                                    }
                                    echo '<td> teste </td>';
                                    echo '</tr>';
                                    if($kmtotal > $dataRota["distancia"]){
                                        $dataRota["motorista"]  = $rota->nome_user;
                                        if($rota->alter_rota == NULL){
                                            $dataRota["rota"]       = $rota->rota;
                                        } else{
                                            $dataRota["rota"]       = $rota->alter_rota;
                                        }
                                        $dataRota["veiculo"]    =  $rota->montadora.' '.$rota->modelo;
                                        $dataRota["placa"]      = $rota->placa;
                                        $dataRota["distancia"]  = $kmtotal;
                                        $dataRota["id"]         = $rota->id_viagem;
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- fim da tabulação de histórico -->
            <!-- tabulação de dados gerais -->
            <div class="card tab-pane fade <?php if($_POST['id_search']=='vehicle'){ echo ' show active'; }?>" id="veiculos" role="tabpanel" aria-labelledby="veiculos-tab" id="general-data">
                <div class="card-header">
                    <h4>Veículos</h4>
                </div>
                <div class="card-body justify-content-center row">
                    <div class="card">
                        <div class="card-header btn" id="ctrl_filtros2">
                            <h6>Filtros</h6>
                        </div>
                        <form action="hst_mov.php#veiculos" method="post">
                            <input type="hidden" name="id_search" value="vehicle">
                            <div class="card-body justify-content-center row" id="show2">
                                <div class="control-form card filtro-card">
                                    <label for="selectVeiculo">Veículo:</label>
                                    <select name="selectVeiculo" id="selectVeiculo2">
                                        <option value="0">Selecione...</option>;
                                    <?php
                                        foreach($cars as $rc){
                                            echo '<option value="'.$rc->placa.'">'.$rc->alias.' ('.$rc->placa.')</option>';
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="control-form card filtro-card">
                                    <label for="">De:</label>
                                    <input type="datetime-local" name="dataDe" id="dataDe2">
                                </div>
                                <div class="control-form card filtro-card">
                                    <label for="">Até:</label>
                                    <input type="datetime-local" name="dataAte" id="dataAte2">
                                </div>
                            </div>
                            <div class="card-body justify-content-center row" id="but1">
                                <button type="submit" class="btn btn-outline-primary col-lg-6"><i class="fas fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <table id="listaDadosGerais" class="table table-striped table-bordered <?php if($isMobile){ echo 'table-responsive';}?>" style="width:100%">
                        <thead>
                            <tr>
                                <th>Veículo</th>
                                <th>Qtde. Viagens</th>
                                <th>Total Distância</th>
                                <th>Média Distância</th>
                                <th>Maior distância</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $totalViagem = 0;
                                $totalKM = 0;
                                $mediaViagemKM = 0;
                                $distMaior = 0;
                                foreach($rotasCarro as $rc){
                                    echo '<tr>';
                                    echo '<td>'.$rc->alias_carro.' ('.$rc->placa_carro.')</td>';
                                    echo '<td>'.$rc->total_viagem.' viagens</td>';
                                    $totalViagem += $rc->total_viagem;
                                    echo '<td>'.$rc->dist_total.' KMs rodados</td>';
                                    $totalKM += $rc->dist_total;
                                    $mediaViagemKM = $rc->dist_total/$rc->total_viagem;
                                    echo '<td>'.number_format((float)$mediaViagemKM,2,'.','').' KM/média</td>';
                                    if($rc->dist_maior > $distMaior){
                                        $distMaior = $rc->dist_maior;
                                    }
                                    echo '<td>'.$rc->dist_maior.' km</td>';
                                    echo '</tr>';
                                }
                                echo '<tr style="background-color: #bd1708; color: white;">';
                                echo '<td> TOTAL </td>';
                                echo '<td> '.$totalViagem.' viagens</td>';
                                echo '<td> '.$totalKM.' KMs rodados</td>';
                                $mediaViagemKM = $totalKM/$totalViagem;
                                $mediaViagemKM = number_format((float)$mediaViagemKM,2,'.','');
                                if($mediaViagemKM>0)
                                    echo '<td> '.$mediaViagemKM.' KM/média</td>';
                                else
                                echo '<td> 0 KM/média</td>';
                                echo '<td> '.$distMaior.' KM ('.$dataRota['rota'].')</td>';
                                echo '</tr>';
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- fim da tabulação de veiculos -->
            <!-- tabulação de dados motoristas -->
            <div class="card tab-pane fade <?php if($_POST['id_search']=='driver'){ echo ' show active'; }?>" id="motoristas" role="tabpanel" aria-labelledby="motoristas-tab" id="general-data">
                <div class="card-header">
                    <h4>Motoristas</h4>
                </div>
                <div class="card-body justify-content-center row">
                    <div class="card">
                        <div class="card-header btn" id="ctrl_filtros3">
                            <h6>Filtros</h6>
                        </div>
                        <form action="hst_mov.php#motoristas" method="post">
                            <input type="hidden" name="id_search" value="driver">
                            <div class="card-body justify-content-center row" id="show3">
                                <div class="control-form card filtro-card">
                                    <label for="selectMotorista">Motorista:</label>
                                    <select name="selectMotorista" id="selectMotorista3">
                                        <option value="0">Selecione...</option>;
                                    <?php
                                        foreach($drivers as $rm){
                                            echo '<option value="'.$rm->matr_user.'">'.$rm->nome_user.'</option>';
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="control-form card filtro-card">
                                    <label for="">De:</label>
                                    <input type="datetime-local" name="dataDe" id="dataDe3">
                                </div>
                                <div class="control-form card filtro-card">
                                    <label for="">Até:</label>
                                    <input type="datetime-local" name="dataAte" id="dataAte3">
                                </div>
                            </div>
                            <div class="card-body justify-content-center row" id="but1">
                                <button type="submit" class="btn btn-outline-primary col-lg-6"><i class="fas fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <table id="listaDadosGerais" class="table table-striped table-bordered <?php if($isMobile){ echo 'table-responsive';}?>" style="width:100%">
                        <thead>
                            <tr>
                                <th>Motorista</th>
                                <th>Total de Viagens</th>
                                <th>Total Distância Percorrida</th>
                                <th>Média Distância Percorrida</th>
                                <th>Maior Distância Percorrida</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $totalViagem = 0;
                                $totalKM = 0;
                                $mediaViagemKM = 0;
                                $distMaior = 0;
                                foreach($rotasMotorista as $rm){
                                    echo '<tr>';
                                    echo '<td>'.$rm->motorista.' ('.$rm->matricula.')</td>';
                                    echo '<td>'.$rm->total_viagem.' viagens</td>';
                                    $totalViagem = $rm->total_viagem;
                                    echo '<td>'.$rm->dist_total.' KMs rodados</td>';
                                    $totalKM = $rm->dist_total;
                                    $mediaViagemKM = $rm->dist_total/$rm->total_viagem;
                                    echo '<td>'.number_format((float)$mediaViagemKM,2,'.','').' KM/média</td>';
                                    if($rm->dist_maior > $distMaior){
                                        $distMaior = $rm->dist_maior;
                                    }
                                    echo '<td>'.$rm->dist_maior.' km</td>';

                                    echo '</tr>';
                                }
                                echo '<tr style="background-color: #bd1708; color: white;">';
                                echo '<td> TOTAL </td>';
                                echo '<td> '.$totalViagem.' viagens</td>';
                                echo '<td> '.$totalKM.' KMs rodados</td>';
                                $mediaViagemKM = $totalKM/$totalViagem;
                                $mediaViagemKM = number_format((float)$mediaViagemKM,2,'.','');
                                if($mediaViagemKM>0)
                                    echo '<td> '.$mediaViagemKM.' KM/média</td>';
                                else
                                    echo '<td> 0 KM/média</td>';
                                echo '<td> '.$distMaior.' KM ('.$dataRota['rota'].')</td>';
                                echo '</tr>';
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php
        require_once('footer.php');
    ?>
    
    <!-- END OF CODE -->
    <!-- Modais -->
    <?php
        require_once('modaisMenu.php');
    ?> 

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo BASE;?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo BASE;?>/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo BASE;?>/vendor/font-awesome/js/all.min.js"></script>
    <script src="<?php echo BASE;?>/vendor/font-awesome/js/fontawesome.min.js"></script>
    <script src="<?php echo BASE;?>/js/hst_mov.js"></script>
    <script src="<?php echo BASE;?>/sw.js"></script>

    <!-- DataTables -->
    <script type="text/javascript" src="vendor/datatables.min.js"></script>

    </body>
</html>