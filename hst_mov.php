
<!DOCTYPE html>
<html>
<?php
    require_once('config.php');
    require_once('session.php');
    require_once('isMobile.php');
    require_once('header.php');
    
    if($level=='MTR')
        $query = $bd->prepare('SELECT l.*, u.matr_user, u.nome_user, v.id_veiculo, v.proprietario, v.montadora, v.placa, v.modelo, v.status FROM tb_viagem as l, tb_users as u, tb_veiculo as v WHERE u.matr_user = l.id_user AND v.id_veiculo = l.id_veiculo ORDER BY l.id_viagem DESC');
    else{
        $query = $bd->prepare('SELECT l.*, u.matr_user, u.nome_user, v.id_veiculo, v.proprietario, v.montadora, v.placa, v.modelo, v.status FROM tb_viagem as l, tb_users as u, tb_veiculo as v WHERE u.matr_user = l.id_user AND v.id_veiculo = l.id_veiculo AND v.id_uf = :uf ORDER BY l.id_viagem DESC');
        $query->bindParam(':uf',$myuf);
    }
    $query->execute();
    $rotas = $query->fetchAll(PDO::FETCH_OBJ);

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
                <h4>Histórico de Movimentação dos Veículos</h4>
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
                                if($rota->em_andamento == 0){
                                    echo '<td>'.$kmtotal.'</td>';
                                    echo '<td><p style="display:none">Finalizado</p><abbr title="Rota Finalizada" class="initialism"><i class="fas fa-check-circle fa-2x" style="color: green"></i></abbr></td>';
                                } else{
                                    echo '<td> -- </td>';
                                    echo '<td><p style="display:none">Em Andamento</p><abbr title="Em andamento" class="initialism"><i class="fas fa-route fa-2x" style="color: blue"></i></abbr></td>';
                                }
                                echo '<td> teste </td>';
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