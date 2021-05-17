
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
    $qr1='';
    $qr2='';
    require_once('config.php');
    require_once('session.php');
    require_once('isMobile.php');
    require_once('header.php');
    // listar todos os veículos
    $query = $bd->prepare(' SELECT alias, modelo, montadora, placa, id_uf, id_cidade FROM tb_veiculo');
    $query->execute();
    $cars  = $query->fetchAll(PDO::FETCH_OBJ);
    if(count($_POST)>0){
        //filtro da aba Histórico
        if($_POST['id_search']=='historico'){
            $veiculo = $_POST['selectVeiculo'];
            if($_POST['selectTipoManut']!=''){
                $tipoManutencao = $_POST['selectTipoManut'];
                $qr1 .= ' AND m.tipo_manut = '.$tipoManutencao;
            }
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
                $qr1 .= ' AND v.placa = '.$veiculo.' ';
            }
            if($_POST['inputValorDe']!=''){
                $valorDe = $_POST['inputValorDe'];
            } else{
                $valorDe = '0.00';
            }
            if($_POST['inputValorAte']!=''){
                $valorAte = $_POST['inputValorAte'];
            } else{
                $valorAte = '999999.00';
            }
            $qr1 .= " AND m.valor_manut BETWEEN '".$valorDe."' AND '".$valorAte."' ";
            //echo 'de: '.$dataDe.'até: '.$dataAte;
            //echo '<br>id:'.$_POST['id_search'].' & query: '.$qr1;
        }
        //filtro da aba Veículos - Geral
        if($_POST['id_search']=='geral'){
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
            $qr2 .= " AND m.data_manut BETWEEN '".$dataDe."' AND '".$dataAte."' ";
            //echo 'de: '.$dataDe.'até: '.$dataAte;
            echo '<br>id:'.$_POST['id_search'].' & query: '.$qr2;
        }
    }
    try{
        if($level=='MTR'){
            $query = $bd->prepare('SELECT v.modelo,v.montadora,v.id_veiculo,v.alias,v.id_uf,m.* FROM `tb_veiculo` as v, `tb_manutencao` as m WHERE v.id_veiculo = m.id_veiculo '.$qr1);
        } else{
            $query = $bd->prepare('SELECT v.modelo,v.montadora,v.id_veiculo,v.alias,v.id_uf,m.* FROM `tb_veiculo` as v, `tb_manutencao` as m WHERE v.id_veiculo = m.id_veiculo AND v.id_uf = :uf '.$qr1);
            $query->bindParam(':uf',$myuf);
        }
        $query->execute();
        $manutencoes = $query->fetchAll(PDO::FETCH_OBJ);
    }  catch(PDOException $e){
        echo $e->getMessage();
    }
    $manut_prog=0;
    $manut_nao_prog=0;
    $valor_total=0;
    $valor_medio=0;
    if($level=='MTR'){
        $query = $bd->prepare('SELECT v.alias as alias_carro, v.placa as placa_carro, count(m.id_veiculo) as total_manut, sum(CASE WHEN m.tipo_manut = 0 THEN 1 ELSE 0 END) as nprog, sum(CASE WHEN m.tipo_manut = 1 THEN 1 ELSE 0 END) as prog, sum(m.valor_manut) as valor_gasto FROM `tb_veiculo` as v, `tb_manutencao` as m WHERE m.id_veiculo = v.id_veiculo '.$qr2.' GROUP BY v.alias, v.placa;');
    } else{
        $query = $bd->prepare('SELECT v.alias as alias_carro, v.placa as placa_carro, count(m.id_veiculo) as total_manut, sum(CASE WHEN m.tipo_manut = 0 THEN 1 ELSE 0 END) as nprog, sum(CASE WHEN m.tipo_manut = 1 THEN 1 ELSE 0 END) as prog, sum(m.valor_manut) as valor_gasto FROM `tb_veiculo` as v, `tb_manutencao` as m WHERE m.id_veiculo = v.id_veiculo '.$qr2.' AND v.id_uf = :uf GROUP BY v.alias, v.placa;');
        $query->bindParam(':uf',$myuf);
    }
    $query->execute();
    $man_geral = $query->fetchAll(PDO::FETCH_OBJ);
?>
<head>
    <style>
        .filtro-card{
            min-width: 30px;
            align-items: center;
            display: flex;
            margin: 3px !important;
        }
        #ctrl_filtros1, #ctrl_filtros2{
            width: 75vw !important;
        }
        <?php if($isMobile){ ?>
        #show1, #show2{
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
                <a class="nav-link <?php if($_POST['id_search']=='historico' || $_POST['id_search']==''){ echo 'active'; }?>" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="true">Histórico</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if($_POST['id_search']=='geral'){ echo 'active'; }?>" id="dados-tab" data-toggle="tab" href="#dados" role="tab" aria-controls="dados" aria-selected="false">Dados Gerais</a>
            </li>
        </ul>
        <div class="tab-content" id="tabs">
            <!-- tabulação de histórico -->
            <div class="card tab-pane fade <?php if($_POST['id_search']=='historico' || $_POST['id_search']==''){ echo 'show active'; }?>" id="history" role="tabpanel" aria-labelledby="history-tab">
                <div class="card-header">
                    <h4>Histórico de Manutenções dos Veículos</h4>
                </div>
                <div class="card-body justify-content-center row">
                    <div class="card">
                        <div class="card-header btn" id="ctrl_filtros1">
                            <h6>Filtros</h6>
                        </div>
                        <form action="hst_manut.php#history" method="post">
                            <input type="hidden" name="id_search" value="historico">
                            <div class="card-body justify-content-center row" id="show1">
                                <div class="control-form card filtro-card">
                                    <label for="selectVeiculo">Veículo:</label>
                                    <select style="width: 160px;" name="selectVeiculo" id="selectVeiculo1">
                                        <option value="0">Selecione...</option>;
                                    <?php
                                        foreach($cars as $rc){
                                            echo '<option value="'.$rc->placa.'">'.$rc->alias.' ('.$rc->placa.')</option>';
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="control-form card filtro-card">
                                    <label for="">Tipo:</label>
                                    <select style="width: 160px;" name="selectTipoManut" id="selectTipoManut1">
                                        <option value="s">Selecione...</option>
                                        <option value="1">Programada</option>
                                        <option value="0">Não Programada</option>
                                    </select>
                                </div>
                                <div class="control-form card filtro-card">
                                    <label for="">De:</label>
                                    <input style="width: 200px;" type="datetime-local" name="dataDe" id="dataDe1">
                                </div>
                                <div class="control-form card filtro-card">
                                    <label for="">Até:</label>
                                    <input style="width: 200px;" type="datetime-local" name="dataAte" id="dataAte1">
                                </div>
                                <div class="control-form card filtro-card">
                                    <label for="">Valor entre:</label>
                                    <input type="text" name="inputValorDe" id="inputValorDe1" placeholder="Valor Minimo (R$)" onkeydown="fMasc(this,mCash)" title="Valor Minimo (R$)">
                                    <input type="text" name="inputValorAte" id="inputValorAte1" placeholder="Valor Máximo (R$)" onkeydown="fMasc(this,mCash)" title="Valor Máximo (R$)">
                                </div>
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
                                <th style="display:none;">ID</th>
                                <th>Veículo</th>
                                <th>Localidade</th>
                                <th>Oficina</th>
                                <th>Programada?</th>
                                <th>KM ida / KM volta</th>
                                <th>Valor (R$)</th>
                                <th>Data ida - Data Volta</th>
                                <th>Status Manutenção</th>
                                <th class="text-truncate">Descrição</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($manutencoes as $manutencao){
                                    echo '<tr>';
                                    echo '<td style="display:none;">'.$manutencao->id_manut.'</td>';
                                    echo '<td>'.$manutencao->montadora.' '.$manutencao->modelo.' ('.$manutencao->alias.') </td>';
                                    echo '<td>'.$manutencao->cidade.'/'.$manutencao->uf.'</td>';
                                    echo '<td>'.$manutencao->local_manut.'</td>';
                                    if($manutencao->tipo_manut){
                                        echo '<td><p style="display:none">Programada</p><abbr title="Sim" class="initialism"><i class="fas fa-check-circle fa-2x" style="color: green"></i></abbr></td>';
                                        $manut_prog++;
                                    } else{
                                        echo '<td><p style="display:none">Nao Programada</p><abbr title="Não" class="initialism"><i class="fas fa-times-circle fa-2x" style="color: red"></i></abbr></td>';
                                        $manut_nao_prog++;
                                    }
                                    if(!$manutencao->status_manut){
                                        echo '<td>'.$manutencao->km_ida.' km / '.$manutencao->km_retorno.' km</td>';
                                    } else{
                                        echo '<td>'.$manutencao->km_ida.' km / --- </td>';
                                    }
                                    if(!$manutencao->status_manut){
                                        echo '<td>'.date("d/m/Y H:i", strtotime($manutencao->data_manut)).' - '.date("d/m/Y H:i", strtotime($manutencao->data_retorno)).'</td>';
                                    } else{
                                        echo '<td>'.date("d/m/Y H:i", strtotime($manutencao->data_manut)).' / --- </td>';
                                    }
                                    if($manutencao->valor_manut==''){
                                        echo '<td> --- </td>';
                                    } else{
                                        echo '<td>R$ '.$manutencao->valor_manut.'</td>';
                                        $valor_total+=$manutencao->valor_manut;
                                    }
                                    if($manutencao->status_manut){
                                        echo '<td><p style="display:none">Em manutenção</p><abbr title="Andamento" class="initialism"><i class="fas fa-tools fa-2x" style="color: red"></i></abbr></td>';
                                    } else{
                                        echo '<td><p style="display:none">Manutenção Finalizada</p><abbr title="Finalizada" class="initialism"><i class="fas fa-check-circle fa-2x" style="color: green"></i></abbr></td>';
                                    }
                                    echo '<td>'.$manutencao->descricao_manut.'</td>';
                                    echo '</tr>';
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- fim da tabulação de histórico -->
            <!-- tabulação de dados gerais -->
            <div class="card tab-pane fade <?php if($_POST['id_search']=='geral'){ echo 'show active';}?>" id="dados" role="tabpanel" aria-labelledby="dados-tab">
                <div class="card-header">
                    <h4>Dados Gerais</h4>
                </div>
                <div class="card-body justify-content-center row">
                    <div class="card">
                        <div class="card-header btn" id="ctrl_filtros2">
                            <h6>Filtros</h6>
                        </div>
                        <form action="hst_manut.php#dados" method="post">
                            <input type="hidden" name="id_search" value="geral">
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
                            <div class="card-body justify-content-center row" id="but2">
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
                                <th>Qtde. Manut</th>
                                <th>Manut. Programadas</th>
                                <th>Manut. Não Programadas</th>
                                <th>Gasto Total(R$)</th>
                                <th>Média (R$/Manutenção))</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($man_geral as $man){
                                    echo '<tr>';
                                    echo '<td>'.$man->alias_carro.' ('.$man->placa_carro.')</td>';
                                    echo '<td>'.$man->total_manut.'</td>';
                                    echo '<td>'.$man->prog.'</td>';
                                    echo '<td>'.$man->nprog.'</td>';
                                    echo '<td>'.$man->valor_gasto.'</td>';
                                    $media = $man->valor_gasto / $man->total_manut;
                                    echo '<td>R$ '.number_format((float)$media,2,'.','').'</td>';
                                    echo '</tr>';
                                }
                                echo '<tr style="background-color: #bd1708; color: white;">';
                                echo '<td> TOTAL </td>';
                                echo '<td>'.count($manutencoes).'</td>';
                                echo '<td>'.$manut_prog.'</td>';
                                echo '<td>'.$manut_nao_prog.'</td>';
                                echo '<td>R$ '.$valor_total.'</td>';
                                $valor_medio = $valor_total/count($manutencoes);
                                echo '<td>R$ '.number_format((float)$valor_medio,2,'.','').'</td>';
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
    <script src="<?php echo BASE;?>/js/masks.js"></script>
    <script src="<?php echo BASE;?>/sw.js"></script>

    <!-- DataTables -->
    <script type="text/javascript" src="vendor/datatables.min.js"></script>

    </body>
</html>