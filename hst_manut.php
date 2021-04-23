
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
    if($level=='MTR')
        $query = $bd->prepare('SELECT v.modelo,v.montadora,v.id_veiculo,v.alias,v.id_uf,m.* FROM `tb_veiculo` as v, `tb_manutencao` as m WHERE v.id_veiculo = m.id_veiculo');
    else{
        $query = $bd->prepare('SELECT v.modelo,v.montadora,v.id_veiculo,v.alias,v.id_uf,m.* FROM `tb_veiculo` as v, `tb_manutencao` as m WHERE v.id_veiculo = m.id_veiculo AND v.id_uf = :uf');
        $query->bindParam(':uf',$myuf);
    }
    $query->execute();
    $manutencoes = $query->fetchAll(PDO::FETCH_OBJ);

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
        <div class="card">
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
                                } else{
                                    echo '<td><p style="display:none">Nao Programada</p><abbr title="Não" class="initialism"><i class="fas fa-times-circle fa-2x" style="color: red"></i></abbr></td>';
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