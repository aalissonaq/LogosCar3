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
        $query = $bd->prepare('SELECT * FROM tb_veiculo');
    if($level=='ADM'){
        $query = $bd->prepare('SELECT * FROM tb_veiculo WHERE id_uf = :myuf');
        $query->bindParam(':myuf',$myuf);
    }
    $query->execute();
    $veiculos = $query->fetchAll(PDO::FETCH_OBJ);
?>
<head>
    <link rel="stylesheet" href="<?php echo BASE;?>/css/sys.css">
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
                        echo '<div id="avisoGet" class="alert alert-success" role="alert"> Veículo cadastrado com sucesso! </div>';
                    } else{
                        echo '<div id="avisoGet" class="alert alert-danger" role="alert"> Ops! Não foi possível efetuar o cadastro... </div>';
                    }
                }
                if(!@$_GET['edt']==''){
                    if($_GET['edt']==1){
                        echo '<div id="avisoGet" class="alert alert-success" role="alert"> Veículo editado com sucesso! </div>';
                    } else{
                        echo '<div id="avisoGet" class="alert alert-danger" role="alert"> Ops! Não foi possível editar o veículo... </div>';
                    }
                }
            ?>
            <div id="teste">
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Veículos</h4>
                </div>
                <div class="card-body badge-info">
                    <button class="btn btn-outline-light col-lg-5" id="btnAddVeiculo" data-toggle="modal" data-target="#modalAddVeiculo"><i class="fas fa-plus"></i> Adicionar Veículo </button>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Lista de Veiculos</h5>
                    <table id="listaVeiculos" class="table table-striped table-bordered <?php if($isMobile){ echo 'table-responsive';}?>" style="width:100%">
                        <thead>
                            <tr>
                                <th style="display:none;">ID</th>
                                <th>Montadora</th>
                                <th>Modelo</th>
                                <th>Placa</th>
                                <th>Propriedade</th>
                                <th>Local </th>
                                <th>KM rodado</th>
                                <th>Prox. Manutenção</th>
                                <th>Status</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($veiculos as $veiculo){
                                    echo '<tr>';
                                    echo '<td style="display:none;">'.$veiculo->id_veiculo.'</td>'; 
                                    echo '<td>'.$veiculo->montadora.'</td>';
                                    echo '<td>'.$veiculo->modelo.'</td>'; 
                                    echo '<td>'.$veiculo->placa.'</td>';
                                    echo '<td>'.$veiculo->proprietario.'</td>';
                                    echo '<td>'.$veiculo->id_cidade.'/'.$veiculo->id_uf.'</td>';
                                    echo '<td>'.$veiculo->kilometragem.'</td>';
                                    switch($veiculo->kilometragem){
                                        case ($veiculo->kilometragem <=10000): 
                                            $proxmanut = 10000;
                                            break;
                                        case ($veiculo->kilometragem <=20000):
                                            $proxmanut = 20000;
                                            break;
                                        case ($veiculo->kilometragem <=30000):
                                            $proxmanut =30000;
                                            break;
                                        case ($veiculo->kilometragem <=40000):
                                            $proxmanut =40000;
                                            break;
                                        case ($veiculo->kilometragem <=50000):
                                            $proxmanut =50000;
                                            break;
                                        case ($veiculo->kilometragem <=60000):
                                            $proxmanut =60000;
                                            break;
                                        case ($veiculo->kilometragem <=70000):
                                            $proxmanut =70000;
                                            break;
                                        case ($veiculo->kilometragem <=80000):
                                            $proxmanut =80000;
                                            break;
                                        case ($veiculo->kilometragem <=90000):
                                            $proxmanut =90000;
                                            break;
                                        case ($veiculo->kilometragem <=100000):
                                            $proxmanut =100000;
                                            break;
                                        default:
                                            $proxmanut = 'Sem Manutenção Programada';
                                    }
                                    echo '<td>'.$proxmanut.'</td>';
                                    switch($veiculo->status){
                                        case 1:
                                            if($proxmanut - $veiculo->kilometragem > 1000){
                                                echo '<td><span style="display:none">A</span><abbr title="Operacional" class="initialism"><i class="fas fa-check-circle fa-2x" style="color: green"></i></abbr></td>';
                                            } else{
                                                echo '<td><span style="display:none">B</span><abbr title="Manutenção em Breve" class="initialism"><i class="fas fa-exclamation-triangle fa-2x" style="color: yellow"></i></abbr></td>';
                                            }
                                            break;
                                        case 2:
                                            if($proxmanut - $veiculo->kilometragem > 1000){
                                                echo '<td><span style="display:none">A</span><abbr title="Operacional" class="initialism"><i class="fas fa-check-circle fa-2x" style="color: green"></i></abbr></td>';
                                            } else{
                                                echo '<td><p style="display:none">B</p><abbr title="Em andamento" class="initialism"><i class="fas fa-route fa-2x" style="color: blue"></i></abbr></td>';
                                            }
                                            break;
                                        case 3:
                                            echo '<td><span style="display:none">C</span><abbr title="Na oficina" class="initialism"><i class="fas fa-tools fa-2x" style="color: red"></i></abbr></td>';
                                            break;
                                    }
                                    echo '<td><a class="btn btn-info" href="editarVeiculo.php?aZhDelkuQw='.$veiculo->id_veiculo.'"><i class="fas fa-edit"></i></a></td>';
                                    echo '</tr>';                                 
                                }
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
    <!-- Modal Add Veículo -->
    <div class="modal fade" id="modalAddVeiculo" tabindex="-1" role="dialog" aria-labelledby="modalAddVeiculo" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Adicionar Veículo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label for="sobre"><h5>Sobre o veiculo:</h5></label><hr/>
                <form id="formAddVeiculo" action="addveiculo.php" method="post">
                <div class="form-group justify-content-center text-center">
                    <select class="col-lg-5" name="inputMontadora" id="inputMontadora">
                    </select>
                    <select class="col-lg-5" name="inputModelo" id="inputModelo">
                    </select>
                </div>
                <div class="form-group justify-content-center text-center">
                    <input class="col-lg-4" type="text" name="inputPlaca" id="inputPlaca" placeholder="Placa do Veiculo" minlength="7" maxlength="7" onkeyup="upperCaser(this)" required>
                    <input class="col-lg-3" type="text" name="inputAnoFab" id="inputAnoFab" placeholder="Ano Fab." minlength="4" maxlength="4" onkeyup="fMasc(this,mNum)" required>
                    <input class="col-lg-3" type="text" name="inputAnoMod" id="inputAnoMod" placeholder="Ano Mod." minlength="4" maxlength="4" onkeyup="fMasc(this,mNum)" required>
                </div>
                <div class="form-group justify-content-center text-center">
                    <input class="col-lg-5" type="text" name="inputRENAVAM" id="inputRENAVAM" placeholder="RENAVAM" maxlength="11" onkeydown="fMasc(this,mNum)" required>
                    <input class="col-lg-5" type="text" name="inputChassi" id="inputChassi" placeholder="Nº. Chassi" maxlength="17" onkeyup="upperCaser(this)" required>
                </div>
                <div class="form-group justify-content-center text-center">
                    <label for="inputCor">Cor Predominante</label></br>
                    <select class="col-lg-5" name="inputCor" id="inputCor">
                        <option value="#000000">Preto</option>
                        <option value="#FFFFFF">Branco</option>
                        <option value="#6B6B6B">Cinza</option>
                        <option value="#FF0000">Vermelho</option>
                        <option value="#0000FF">Azul</option>
                        <option value="#FFFF00">Amarelo</option>
                        <option value="#653608">Marrom</option>
                        <option value="">Outra...</option>
                    </select>
                    <input class="col-lg-5" type="color" name="Cor" id="Cor" disabled>
                </div>
            </div>
            <div class="modal-body">
                <label for="sobre"><h5>Dados do veiculo:</h5></label><hr/>
                <h6><abbr title="A cidade-base onde o veículo estará lotado, passará maior parte do tempo." class="initialism">Localidade</abbr></h6>
                <div class="form-group justify-content-center text-center">
                    <select class="col-lg-5" name="inputUF" id="inputUF">
                    <!-- Cidades -->
                    </select>
                    <select class="col-lg-5" name="inputCidade" id="inputCidade">
                    <!-- Cidades -->
                    </select>
                </div>
                <div class="form-group justify-content-center text-center">
                    <label class="col-lg-5" for="inputAlias"><abbr title="O nome pelo qual o veículo será reconhecido internamente" class="initialism">Alias:</abbr></label>
                    <input class="col-lg-5" type="text" name="inputAlias" id="inputAlias" required>
                </div>
                <div class="form-group justify-content-center text-center">
                    <label class="col-lg-5" for="inputDataRecebimento">Data Recebimento: </label>
                    <input class="col-lg-5" type="date" name="inputDataRecebimento" id="inputDataRecebimento" required>
                    <label class="col-lg-5" for="inputDataRecebimento">KM de recebimento:</label>
                    <input class="col-lg-5" type="tel" name="inputKMRecebimento" id="inputKMRecebimento" maxlength="7" onkeydown="javascript: fMasc( this, mNum );" required>
                </div>
                <div class="form-group justify-content-center text-center">
                    <label for="inputProprietario">Proprietário:</label></br>
                    <select class="col-lg-10" name="inputProprietario" id="inputProprietario">
                        <option value="Logos">Logos</option>
                        <option value="Avis">Avis</option>
                        <option value="Hertz">Hertz</option>
                        <option value="Localiza">Localiza</option>
                        <option value="Movida">Movida</option>
                        <option value="Unidas">Unidas</option>
                        <option value="Outra...">Outra...</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary">Limpar</button>
                <button type="submit" id="salvarNovoCarro" class="btn btn-primary">Salvar</button>
                </form>
            </div>
        </div>
    </div>
    </div>
    
    <!-- END OF CODE -->
    <?php
        require_once('modaisMenu.php');
    ?>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo BASE;?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo BASE;?>/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- DataTables -->
    <script type="text/javascript" src="vendor/datatables.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo BASE;?>/vendor/font-awesome/js/all.min.js"></script>
    <script src="<?php echo BASE;?>/vendor/font-awesome/js/fontawesome.min.js"></script>
    <script src="<?php echo BASE;?>/js/register.js"></script>
    <script src="<?php echo BASE;?>/js/veiculos.js"></script>
    <script src="<?php echo BASE;?>/js/masks.js"></script>
    <script src="<?php echo BASE;?>/sw.js"></script>
    </body>
</html>