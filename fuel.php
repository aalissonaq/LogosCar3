<!DOCTYPE html>
<html>
<?php
    require_once('config.php');
    require_once('session.php');
    require_once('isMobile.php');
    require_once('header.php');
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
    $qr='';
    // listar todos os motoristas
    $query = $bd->prepare(' SELECT nome_user, id_user FROM tb_users WHERE motorista = true');
    $query->execute();
    $drivers  = $query->fetchAll(PDO::FETCH_OBJ);
    // listar todos os veículos
    $query = $bd->prepare(' SELECT alias, modelo, montadora, id_veiculo, placa, id_uf, id_cidade FROM tb_veiculo');
    $query->execute();
    $cars  = $query->fetchAll(PDO::FETCH_OBJ);
    
    if(count($_POST)>0){
        var_dump($_POST);
        //filtro de multas
            $veiculo = $_POST['selectVeiculo'];
            $motorista = $_POST['selectMotorista'];
            if($_POST['selectTipoManut']!=''){
                $tipoManutencao = $_POST['selectTipoManut'];
                $qr .= ' AND m.tipo_manut = '.$tipoManutencao;
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
            $qr .= " AND m.data_multa BETWEEN '".$dataDe."' AND '".$dataAte."' ";
            if($veiculo!='0'){
                $qr .= ' AND v.placa = '.$veiculo.' ';
            }
            if($motorista!='0'){
                $qr .= ' AND m.id_motorista = '.$motorista.' ';
            }
            if($_POST['valorDe']!=''){
                $valorDe = $_POST['inputValorDe'];
            } else{
                $valorDe = '0.00';
            }
            if($_POST['valorAte']!=''){
                $valorAte = $_POST['inputValorAte'];
            } else{
                $valorAte = '999999.00';
            }
            $qr .= " AND m.valor_multa BETWEEN '".$valorDe."' AND '".$valorAte."' ";
            //echo 'de: '.$dataDe.'até: '.$dataAte;
            //echo '<br>id:'.$_POST['id_search'].' & query: '.$qr1;
    }
    try{
        if($level=='OPR')
            header('Location: sys.php');
        if($level=='MTR')
            $query = $bd->prepare('SELECT u.nome_user, u.id_user, v.id_veiculo, v.montadora, v.modelo, v.placa, v.alias, v.renavam, a.* FROM tb_abastecimento as a, tb_users as u, tb_veiculo as v WHERE a.id_motorista = u.id_user AND a.id_veiculo = v.id_veiculo'.$qr);
        if($level=='ADM'){
            $query = $bd->prepare('SELECT u.nome_user, u.id_user, v.id_veiculo, v.montadora, v.modelo, v.placa, v.alias, v.renavam, a.* FROM tb_abastecimento as a, tb_users as u, tb_veiculo as v WHERE a.id_motorista = u.id_user AND a.id_veiculo = v.id_veiculo AND v.id_uf = :myuf'.$qr);
            $query->bindParam(':myuf',$myuf);
        }
        $query->execute();
        $abastecimentos = $query->fetchAll(PDO::FETCH_OBJ);
    } catch(PDOException $e){
        echo $e->getMessage();
    }
?>
<head>
    <style>
        .control-space{
            margin-right: 7px;
            margin-left: 7px;
        }
        .filtro-card{
            min-width: 60px;
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
        <div class="container">
            <?php
                if(!@$_GET['add']==''){
                    if($_GET['add']==1){
                        echo '<div id="avisoGet" class="alert alert-success" role="alert"> Abastecimento cadastrado com sucesso </div>';
                    } else{
                        echo '<div id="avisoGet" class="alert alert-danger" role="alert"> Ops! Não foi possível efetuar o cadastro do abastecimento... </div>';
                    }
                }
                if(!@$_GET['edt']==''){
                    if($_GET['edt']==1){
                        echo '<div id="avisoGet" class="alert alert-success" role="alert"> Abastecimento editado com sucesso </div>';
                    } else{
                        echo '<div id="avisoGet" class="alert alert-danger" role="alert"> Ops! Não foi possível editar o cadastro do abastecimento... </div>';
                    }
                }
            ?>
            <div class="card">
                <div class="card-header">
                    <h4>Abastecimento</h4>
                </div>
                <div class="card-body justify-content-center row">
                    <div class="card">
                        <div class="card-header btn" id="ctrl_filtros1">
                            <h6>Filtros</h6>
                        </div>
                        <form action="multas.php" method="post">
                            <input type="hidden" name="id_search" value="geral">
                            <div class="card-body justify-content-center align-center row" id="show1">
                                <div class="control-form card filtro-card">
                                    <label for="selectMotorista">Motorista:</label>
                                    <select name="selectMotorista" id="selectMotorista">
                                        <option value="0">Selecione...</option>;
                                    <?php
                                        foreach($drivers as $rm){
                                            echo '<option value="'.$rm->id_user.'">'.$rm->nome_user.'</option>';
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="control-form card filtro-card">
                                    <label for="selectVeiculo">Veículo:</label>
                                    <select name="selectVeiculo" id="selectVeiculo">
                                        <option value="0">Selecione...</option>;
                                    <?php
                                        foreach($cars as $rc){
                                            echo '<option value="'.$rc->id_veiculo.'">'.$rc->alias.' ('.$rc->placa.')</option>';
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="control-form card filtro-card">
                                    <label for="">Período entre:</label>
                                    <input type="date" name="dataDe" id="dataDe">
                                    <input type="date" name="dataAte" id="dataAte">
                                </div>
                                <div class="control-form card filtro-card">
                                    <label for="">Valor(R$) entre:</label>
                                    <input type="text" name="valorDe" id="valorDe" onkeydown="fMasc(this,mCash)">
                                    <input type="text" name="valorAte" id="valorAte" onkeydown="fMasc(this,mCash)">
                                </div><br>
                            </div>
                            <div class="card-body justify-content-center row" id="but1">
                                <button type="submit" class="btn btn-outline-primary col-lg-6"><i class="fas fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body badge-info">
                    <button class="btn btn-outline-light col-lg-5" id="btnAddAbastecimento" data-toggle="modal" data-target="#modalAddAbastecimento"><i class="fas fa-gas-pump"></i> Novo Abastecimento </button>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Lista de Abastecimentos</h5>
                    <table id="listaAbastecimentos" class="table table-striped table-bordered <?php if($isMobile){ echo 'table-responsive';}?>" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Motorista</th>
                                <th>Veiculo</th> <!-- Montadora + Modelo -->
                                <th>Data</th>
                                <th>KM do Abastecimento</th>
                                <th>Valor (R$)</th>
                                <th>Litros</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($abastecimentos as $ab){?>
                            <tr>
                                <td><?php echo $ab->id_abastecimento;?></td>
                                <td><?php echo $ab->nome_user;?></td>
                                <td><?php echo $ab->montadora.' '.$ab->modelo.' ('.$ab->placa.')';?></td>
                                <td><?php echo date('d/m/Y', strtotime($ab->data_abastecimento));?></td>
                                <td><?php echo $ab->km_abastecimento?></td>
                                <td><?php echo $ab->valor_abastecimento?></td>
                                <td><?php echo $ab->litros?></td>
                                <td>
                                <button class="btn btn-info btn-sm" title="Alterar Status" onclick="editAbast(<?php echo $ab->id_abastecimento;?>)"><i class="fas fa-file-invoice-dollar"></i></button>
                                <?php if($ab->comprovante_abastecimento!=NULL){?>
                                <button class="btn btn-secondary btn-sm" title="Ver Comprovante" onclick="verDoc(<?php echo $ab->id_abastecimento;?>)"><i class="far fa-eye"></i></button>
                                <?php }?>
                                </td>
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
    <!-- Modal  Add Abastecimento -->
    <div class="modal fade" id="modalAddAbastecimento" tabindex="-1" role="dialog" aria-labelledby="modalAddAbastecimento" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="labelNovoAbast"><i class="fas fa-plus-circle" style='margin-right: 15px;'></i> Novo Abastecimento</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formAddAbast" action="addabastecimento.php" method="post">
                        <div class="form-group justify-content-center text-center">
                            <label class="col-lg-4" for="novaSenha">Motorista:</label>
                            <select class="col-lg-6" name="inputAddMotorista" id="inputAddMotorista"> 
                                <?php
                                    foreach($drivers as $d){
                                        echo '<option value="'.$d->id_user.'">'.$d->nome_user.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group justify-content-center text-center">
                            <label class="col-lg-4" for="novaSenha">Veículo:</label>
                            <select class="col-lg-6" name="inputAddVeiculo" id="inputAddVeiculo"> 
                                <?php
                                    foreach($cars as $c){
                                        echo '<option value="'.$c->id_veiculo.'">'.$c->montadora.' '.$c->modelo.' ('.$c->alias.')</option>';
                                    }
                                ?>
                            </select>
                        </div>
                </div>
                <div class="modal-body justify-content-center text-center"><hr>
                    <h5>Detalhes:</h5>
                    <div class="control-form">
                        <label class="col-lg-4" for="inputAddLitros">Litragem (L):</label>
                        <input class="col-lg-6" type="text" name="inputAddLitros" id="inputAddLitros" onkeydown="fMasc(this,mCash)">
                    </div>
                    <div class="control-form">
                        <label class="col-lg-4" for="inputAddValorTotal">Valor Total (R$):</label>
                        <input class="col-lg-6" type="text" name="inputAddValorTotal" id="inputAddValorTotal" onkeydown="fMasc(this,mCash)" required>
                    </div>
                    <div class="control-form">
                        <label class="col-lg-4" for="inputAddDataAbastecimento">Abasteceu em:</label>
                        <input class="col-lg-6" type="datetime-local" name="inputAddDataAbastecimento" id="inputAddDataAbastecimento" required>
                    </div>
                    <div class="control-form">
                        <label class="col-lg-4" for="inputAddKM">KM (Odômetro):</label>
                        <input class="col-lg-6" type="text" name="inputAddKM" id="inputAddKM" required>
                    </div>
                </div><hr>
                <div class="modal-body justify-content-center text-center">
                    <label for="inputDocComprovante">Adicionar Comprovante Digitalizado:</label>
                    <input type="file" name="inputDocComprovante" id="inputAddDocComprovante" onchange="addDocBase64()">
                    <input type="hidden" name="inputComprovante" id="inputAddComprovante">
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary">Limpar</button>
                    <button type="submit" id="submitAddAbast" class="btn btn-primary">Adicionar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Fim Modal Add Abastecimento -->
    <!-- Modal  Editar Abastecimento -->
    <div class="modal fade" id="modalEditAbastecimento" tabindex="-1" role="dialog" aria-labelledby="modalEditAbastecimento" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="labelEdtAbast"></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEdtAbast" action="edtabastecimento.php" method="post">
                        <input type="hidden" name="inputEdtID" id="inputEdtID">
                        <div class="form-group justify-content-center text-center">
                            <label class="col-lg-4" for="inputEdtMotorista">Motorista:</label>
                            <select class="col-lg-6" name="inputEdtMotorista" id="inputEdtMotorista"> 
                                <?php
                                    foreach($drivers as $d){
                                        echo '<option value="'.$d->id_user.'">'.$d->nome_user.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group justify-content-center text-center">
                            <label class="col-lg-4" for="inputEdtVeiculo">Veículo:</label>
                            <select class="col-lg-6" name="inputEdtVeiculo" id="inputEdtVeiculo"> 
                                <?php
                                    foreach($cars as $c){
                                        echo '<option value="'.$c->id_veiculo.'">'.$c->montadora.' '.$c->modelo.' ('.$c->alias.')</option>';
                                    }
                                ?>
                            </select>
                        </div>
                </div>
                <div class="modal-body justify-content-center text-center"><hr>
                    <h5>Detalhes:</h5>
                    <div class="control-form">
                        <label class="col-lg-4" for="inputEdtLitros">Litragem (L):</label>
                        <input class="col-lg-6" type="text" name="inputEdtLitros" id="inputEdtLitros" onkeydown="fMasc(this,mCash)">
                    </div>
                    <div class="control-form">
                        <label class="col-lg-4" for="inputEdtValorTotal">Valor Total (R$):</label>
                        <input class="col-lg-6" type="text" name="inputEdtValorTotal" id="inputEdtValorTotal" onkeydown="fMasc(this,mCash)" required>
                    </div>
                    <div class="control-form">
                        <label class="col-lg-4" for="inputEdtDataAbastecimento">Abasteceu em:</label>
                        <input class="col-lg-6" type="date" name="inputEdtDataAbastecimento" id="inputEdtDataAbastecimento" required>
                    </div>
                    <div class="control-form">
                        <label class="col-lg-4" for="inputEdtKM">KM (Odômetro):</label>
                        <input class="col-lg-6" type="text" name="inputEdtKM" id="inputEdtKM" required>
                    </div>
                </div><hr>
                <div class="modal-body justify-content-center text-center row">
                    <label for="inputDocComprovante">Adicionar Comprovante Digitalizado:</label>
                </div>
                <div class="modal-body justify-content-center text-center row">
                    <div class="col-lg">
                        <input type="file" name="inputEdtDocComprovante" id="inputEdtDocComprovante" accept="application/pdf, image/*" onchange="edtDocBase64()">
                        <input type="hidden" name="inputEdtComprovante" id="inputEdtComprovante">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary">Limpar</button>
                    <button type="submit" id="submitEditAbast" class="btn btn-primary">Editar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Fim Modal Editar Abastecimento -->
    <!-- Modal  Ver Doc -->
    <div class="modal fade" id="verDoc" tabindex="-1" role="dialog" aria-labelledby="verDoc" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="labelEdtAbast"></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="viewDoc">
                    <!-- view of docs-->
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-secondary">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Fim Modal Ver Doc -->
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
    <script src="<?php echo BASE;?>/js/masks.js"></script>
    <script src="<?php echo BASE;?>/js/fuel.js"></script>
    <script src="<?php echo BASE;?>/sw.js"></script>
    </body>
</html>