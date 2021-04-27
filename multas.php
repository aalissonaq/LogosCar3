
<!DOCTYPE html>
<html>
<?php
    require_once('config.php');
    require_once('session.php');
    require_once('isMobile.php');
    require_once('header.php');
    if($level=='OPR')
            header('Location: sys.php');
        if($level=='MTR')
            $query = $bd->prepare('SELECT u.nome_user, u.id_user, v.id_veiculo, v.montadora, v.modelo, v.placa, v.alias, v.renavam, m.* FROM tb_multas as m, tb_users as u, tb_veiculo as v WHERE m.id_motorista = u.id_user AND m.id_veiculo = v.id_veiculo');
        if($level=='ADM'){
            $query = $bd->prepare('SELECT u.nome_user, u.id_user, v.id_veiculo, v.montadora, v.modelo, v.placa, v.alias, v.renavam, m.* FROM tb_multas as m, tb_users as u, tb_veiculo as v WHERE m.id_motorista = u.id_user AND m.id_veiculo = v.id_veiculo AND m.uf_multa = :myuf');
            $query->bindParam(':myuf',$myuf);
        }
        $query->execute();
        $multas = $query->fetchAll(PDO::FETCH_OBJ);
        if($level=='MTR')
            $query = $bd->prepare('SELECT * FROM tb_veiculo ORDER BY placa ASC');
        if($level=='ADM'){
            $query = $bd->prepare('SELECT * FROM tb_veiculo WHERE id_uf = :myuf ORDER BY placa ASC');
            $query->bindParam(':myuf',$myuf);
        }
        $query->execute();
        $veiculos = $query->fetchAll(PDO::FETCH_OBJ);
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
        <div class="container">
            <?php
                if(!@$_GET['add']==''){
                    if($_GET['add']==1){
                        echo '<div id="avisoGet" class="alert alert-success" role="alert"> Multa cadastrada com sucesso! </div>';
                    } else{
                        echo '<div id="avisoGet" class="alert alert-danger" role="alert"> Ops! Não foi possível efetuar o cadastro da multa... </div>';
                    }
                }
                if(!@$_GET['edt']==''){
                    if($_GET['edt']==1){
                        echo '<div id="avisoGet" class="alert alert-success" role="alert"> Multa editado com sucesso! </div>';
                    } else{
                        echo '<div id="avisoGet" class="alert alert-danger" role="alert"> Ops! Não foi possível editar a multa... </div>';
                    }
                }
            ?>
            <div id="teste">
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Multas</h4>
                </div>
                <div class="card-body badge-info">
                    <button class="btn btn-outline-light col-lg-5" id="btnAddMulta" data-toggle="modal" data-target="#modalAddMulta"><i class="fas fa-exclamation-triangle"></i> Adicionar Multa </button>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Lista de Multas</h5>
                    <table id="listaMultas" class="table table-striped table-bordered <?php if($isMobile){ echo 'table-responsive';}?>" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Veiculo</th> <!-- Montadora + Modelo -->
                                <th>Placa</th>
                                <th>Data</th>
                                <th>Cidade / UF</th>
                                <th>Local Ocorrência</th>
                                <th>Valor (R$)</th>
                                <th>Status</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($multas as $multa){?>
                            <tr>
                            <td> <?php echo $multa->id_multa; ?> </td>
                            <td> <?php echo $multa->montadora.' '.$multa->modelo.'('.$multa->alias.')'; ?> </td>
                            <td> <?php echo $multa->placa; ?> </td>
                            <td> <?php echo $multa->data_multa; ?> </td>
                            <td> <?php echo $multa->cidade_multa.' / '.$multa->uf_multa; ?> </td>
                            <td> <?php echo $multa->local_multa; ?> </td>
                            <td> <?php echo $multa->valor_multa; ?> </td>
                            <td> <?php echo $multa->pago; ?> </td>
                            <td> Opções </td>
                            </tr>
                        <?php }?>
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
    <!-- MODAIS -->
    <div class="modal fade" id="modalAddMulta" tabindex="-1" role="dialog" aria-labelledby="modalAddVeiculo" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-exclamation-triangle"></i> Nova Ocorrência: Multa </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="control-form justify-content-center text-center">
                            <h6>Identificar Motorista</h6><hr>
                        </div></hr>
                        <div class="control-form justify-content-center text-center">
                            <label class="col-lg-5" for="inputDataHoraOcorrência">Qual a data da multa?</label>
                            <input class="col-lg-5" type="datetime-local" name="inputDataHoraOcorrência" id="inputDataHoraOcorrência" value="<?php echo date('Y-m-d').'T'.date('H:i:s');?>" max="<?php echo date('Y-m-d').'T'.date('H:i:s');?>">
                        </div>
                        <div class="control-form justify-content-center text-center">
                            <label class="col-lg-5" for="inputVeiculo">Qual o veículo que recebeu a multa?</label>
                            <select class="col-lg-5" name="inputVeiculo" id="inputVeiculo">
                            <?php
                                foreach($veiculos as $veiculo){
                                    echo '<option value="'.$veiculo->id_veiculo.'">'.$veiculo->placa.' ('.$veiculo->montadora.' '.$veiculo->modelo.')</option>';
                                }
                            ?> 
                            </select></hr>
                        </div>
                        <div class="control-form justify-content-center text-center">
                            <button style="margin-top: 3%; margin-bottom: 3%;" id="pesquisaMotoristaMulta" class="btn btn-outline-info col-lg-10"><i class="fas fa-search-location"></i> Pesquisar</button>
                        </div>
                        <div id="dados-motorista">
                            <hr>
                            <div class="control-form text-center justify-content-center">
                                <label class="col-lg-5" for="inputNomeCondutor">Nome do condutor:</label>
                                <input class="col-lg-5" type="text" name="inputNomeCondutor" id="inputNomeCondutor" readonly>
                            </div>
                            <div class="control-form text-center justify-content-center">
                                <label class="col-lg-5" for="inputRotaEfetuada">Rota Efetuada:</label>
                                <input class="col-lg-5" type="text" name="inputRotaEfetuada" id="inputRotaEfetuada" readonly>
                            </div>
                            <div class="control-form text-center justify-content-center">
                                <label class="col-lg-5" for="inputIDViagem">ID da viagem:</label>
                                <input class="col-lg-5" type="text" name="inputIDViagem" id="inputIDViagem" readonly>
                            </div>
                        </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary">Limpar</button>
                        <button type="submit" id="salvarNovoColab" class="btn btn-primary">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    <?php
        require_once('modaisMenu.php');
    ?>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo BASE;?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo BASE;?>/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Page level plugin JavaScript-->
    <script src="<?php echo BASE;?>/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo BASE;?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo BASE;?>/vendor/font-awesome/js/all.min.js"></script>
    <script src="<?php echo BASE;?>/vendor/font-awesome/js/fontawesome.min.js"></script>
    <script src="<?php echo BASE;?>/js/register.js"></script>
    <script src="<?php echo BASE;?>/sw.js"></script>
    <script src="<?php echo BASE;?>/js/multas.js"></script>
    </body>
</html>