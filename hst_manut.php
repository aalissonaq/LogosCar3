
<!DOCTYPE html>
<html>
<?php
    require_once('config.php');
    require_once('session.php');
    require_once('isMobile.php');
    require_once('header.php');
    if($level=='OPR'){
        header('Location: sys.php');
    }
    if($level=='MTR'){
        $query = $bd->prepare('SELECT v.modelo,v.montadora,v.id_veiculo,v.alias,v.id_uf,m.* FROM `tb_veiculo` as v, `tb_manutencao` as m WHERE v.id_veiculo = m.id_veiculo');
    } else{
        $query = $bd->prepare('SELECT v.modelo,v.montadora,v.id_veiculo,v.alias,v.id_uf,m.* FROM `tb_veiculo` as v, `tb_manutencao` as m WHERE v.id_veiculo = m.id_veiculo AND v.id_uf = :uf');
        $query->bindParam(':uf',$myuf);
    }
    $query->execute();
    $manutencoes = $query->fetchAll(PDO::FETCH_OBJ);
    $manut_prog=0;
    $manut_nao_prog=0;
    $valor_total=0;
    $valor_medio=0;
    if($level=='MTR'){
        $query = $bd->prepare('SELECT v.alias as alias_carro, v.placa as placa_carro, count(m.id_veiculo) as total_manut, sum(CASE WHEN m.tipo_manut = 0 THEN 1 ELSE 0 END) as nprog, sum(CASE WHEN m.tipo_manut = 1 THEN 1 ELSE 0 END) as prog, sum(m.valor_manut) as valor_gasto FROM `tb_veiculo` as v, `tb_manutencao` as m WHERE m.id_veiculo = v.id_veiculo GROUP BY v.alias, v.placa;');
    } else{
        $query = $bd->prepare('SELECT v.alias as alias_carro, v.placa as placa_carro, count(m.id_veiculo) as total_manut, sum(CASE WHEN m.tipo_manut = 0 THEN 1 ELSE 0 END) as nprog, sum(CASE WHEN m.tipo_manut = 1 THEN 1 ELSE 0 END) as prog, sum(m.valor_manut) as valor_gasto FROM `tb_veiculo` as v, `tb_manutencao` as m WHERE m.id_veiculo = v.id_veiculo AND v.id_uf = :uf GROUP BY v.alias, v.placa;');
        $query->bindParam(':uf',$myuf);
    }
    $query->execute();
    $man_geral = $query->fetchAll(PDO::FETCH_OBJ);
?>
<head>
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
                <a class="nav-link active" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="true">Histórico</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="dados-tab" data-toggle="tab" href="#dados" role="tab" aria-controls="dados" aria-selected="false">Dados Gerais</a>
            </li>
        </ul>
        <div class="tab-content" id="tabs">
            <!-- tabulação de histórico -->
            <div class="card tab-pane fade show active" id="history" role="tabpanel" aria-labelledby="history-tab">
                <div class="card-header">
                    <h4>Histórico de Manutenções dos Veículos</h4>
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
            <div class="card tab-pane fade" id="dados" role="tabpanel" aria-labelledby="dados-tab" id="general-data">
                <div class="card-header">
                    <h4>Dados Gerais</h4>
                </div>
                <div class="card-body justify-content-center row">
                    
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
    <script src="<?php echo BASE;?>/sw.js"></script>

    <!-- DataTables -->
    <script type="text/javascript" src="vendor/datatables.min.js"></script>

    </body>
</html>