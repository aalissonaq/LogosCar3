
<!DOCTYPE html>
<html>
<?php
    require_once('config.php');
    require_once('session.php');
    require_once('isMobile.php');
    require_once('header.php');
    // função que trata datas quebradas, que não vem no padrão Y-m-d H:i:s
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
    $qr='';
    if(count($_POST)>0){
        //filtro da aba Histórico
        if($_POST['id_search']=='abast'){
            $motorista = $_POST['selectMotorista'];
            $veiculo = $_POST['selectVeiculo'];
            // se a data for vazia, ele calcula automaticamente 1 ano atrás a partir de hoje
            if($_POST['dataDe']==''){
                $dataDe = date('Y-m-d 00:00:00', strtotime('-1 year'));
            } else{
                $dataDe = tratarData($_POST['dataDe']);
            }
            // se a data for vazia, ele calcula automaticamente como hoje. Senão, trata a data recebida
            if($_POST['dataAte']==''){
                $dataAte = date('Y-m-d H:i:s');
            } else{
                $dataAte = tratarData($_POST['dataAte']);
            }
            // se a ID for diferente de 0, informa o motorista desejado. Senão, traz todos
            if($motorista!='0'){
                $qr .= ' AND u.matr_user = '.$motorista.' ';
            }
            // se a ID for diferente de 0, informa o veículo desejado. Senão, traz todos
            if($veiculo!='0'){
                $qr .= ' AND v.placa = '.$veiculo.' ';
            }
            // se o valor do abastecimento for vazio, ele calcula como 0.00 (zero). Senão, o valor informado
            if($_POST['inputValorDe']==''){
                $valorDe = '0.00'
            } else{
                $valorDe = $_POST['inputValorDe'];
            }
            // se a data for vazia, ele calcula automaticamente como 999999,99. Senão, o valor informado
            if($_POST['inputValorAte']==''){
                $valorAte = '999999.99';
            } else{
                $valorAte = $_POST['inputValorAte'];
            }
            $qr .= " AND l.data_retorno BETWEEN '".$valorDe."' AND '".$valorAte."' ";
            $qr .= " AND l.data_retorno BETWEEN '".$dataDe."' AND '".$dataAte."' ";
            //echo 'de: '.$dataDe.'até: '.$dataAte;
            //echo '<br> query: '.$qr1;
        }
    }
    try{
    if($level=='MTR')
        $query = $bd->prepare('SELECT a.*, u.nome_user, u.id_user, v.id_veiculo, v.montadora, v.modelo, v.alias, v.placa FROM tb_abastecimento as a, tb_users as u, tb_veiculo as v WHERE a.id_motorista = u.id_user AND v.id_veiculo = a.id_veiculo '.$qr.' ORDER BY a.id_abastecimento DESC');
    else{
        $query = $bd->prepare('SELECT a.*, u.nome_user, u.id_user, v.id_veiculo, v.montadora, v.modelo, v.alias, v.placa FROM tb_abastecimento as a, tb_users as u, tb_veiculo as v WHERE a.id_motorista = u.id_user AND v.id_veiculo = a.id_veiculo '.$qr.' AND v.id_uf = :uf ORDER BY a.id_abastecimento DESC');
        $query->bindParam(':uf',$myuf);
    }
    $query->execute();
    $abastecimentos = $query->fetchAll(PDO::FETCH_OBJ);
    } catch(PDOException $e){
        echo $e->getMessage();
    }
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
                <h4>Abastecimentos</h4>
            </div>
            <div class="card-body">
            <div class="card">
                <div class="card-header btn" id="ctrl_filtros1">
                    <h6>Filtros</h6>
                </div>
                <form action="fuel.php" method="post">
                    <input type="hidden" name="id_search" value="abast">
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
                <h5 class="card-title">Lista de Abastecimentos</h5>
                <div class="card-body badge-info">
                    <button class="btn btn-outline-light col-lg-5" id="btnAddFuel" data-toggle="modal" data-target="#modalAddFuel"><i class="fas fa-plus"></i> Adicionar Abastecimento </button>
                </div>
                <table id="listaUsuarios" class="table table-striped table-bordered <?php if($isMobile){ echo 'table-responsive';}?>" style="width:100%">
                    <thead>
                        <tr>
                            <th style="display:none;">ID</th>
                            <th>Veículo</th>
                            <th>Motorista</th>
                            <th>Data</th>
                            <th>KM Abastecimento</th>
                            <th>Valor (R$)</th>
                            <th>Litros</th>
                            <th>Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($abastecimentos as $ab){
                                echo '<tr>';
                                // ID
                                echo '<td style="display:none;">'.$ab->id_user.'</td>';
                                // NOME DO USUÁRIO
                                echo '<td>'.$ab->montadora.' '.$ab->modelo.' ('.$ab->placa.') </td>';
                                echo '<td>'.$ab->nome_user.'</td>';
                                echo '<td>'.date('d/m/Y H:i:s', strtotime($ab->data_abastecimento)).'</td>';
                                echo '<td>'.$ab->km_abastecimento.'</td>';
                                echo '<td>'.$ab->valor_abastecimento.'</td>';
                                echo '<td>'.$ab->litros.'</td>';
                                // OPÇÕES
                                echo '<td> <button class="btn btn-warning btn-sm" onclick="editAbast('.$ab->id_.')"><i class="fas fa-user-edit"></i></button>';
                                //echo '<button class="btn btn-info btn-sm" onclick="editPassword('.$usuario->id_user.')"><i class="fas fa-key"></i></button></td>';
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
    <!-- Modal Add Veículo -->
    <div class="modal fade" id="modalAddFuel" tabindex="-1" role="dialog" aria-labelledby="modalAddFuel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Novo Abastecimento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formAddFuel" action="addfuel.php" method="post">
                    <div class="form-group justify-content-center text-center">
                        <label for="inputNomeCompleto inputMatricula">Colaborador:</label></br>
                        <input class="col-lg-8" type="text" name="inputNomeCompleto" id="inputNomeCompleto" placeholder="Nome Completo" required>
                        <input class="col-lg-3" type="text" name="inputMatricula" id="inputMatricula" placeholder="Matrícula" onkeydown="fMasc(this,mNum)" maxlength="5" required>
                    </div>
                    <div class="form-group justify-content-center text-center">
                        <labelfor="inputNivel">Nível:</label></br>
                        <select class="col-lg-11" name="inputNivel" id="inputNivel">
                            <option value="OPR">Operação</option>
                            <option value="ADM">Administrador</option>
                        <?php if($level=='MTR'){ ?>
                            <option value="MTR">Máster</option>
                        <?php } ?>
                        </select>
                    </div>
                    <div class="form-group justify-content-center text-center">
                        <label for="inputUF inputCidade">Lotação:</label></br>
                        <select class="col-lg-3" name="inputUF" id="inputUF">
                        <!-- Cidades -->
                        </select>
                        <select class="col-lg-8" name="inputCidade" id="inputCidade">
                        <!-- Cidades -->
                        </select>
                    </div>
                    <div class="form-group justify-content-center text-center">
                        <label class="col-lg-5" for="radioMotorista">Será motorista?</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="radioMotorista" id="motoraSim" value="1">
                            <label class="form-check-label" for="exampleRadios1">
                                Sim
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="radioMotorista" id="motoraNao" value="0" checked>
                            <label class="form-check-label" for="exampleRadios2">
                                Não
                            </label>
                        </div>
                    </div>
                    <div class="form-group justify-content-center text-center" id="dadosMotorista">
                        <hr/>
                        <label class="col-lg-5" for="inputNumCNH">Nº da CNH:</label>
                        <input class="col-lg-5" type="tel" name="inputNumCNH" id="inputNumCNH" onkeydown="fMasc(this,mNum)">
                        <label class="col-lg-5" for="inputDataEmissao">Categoria:</label>
                        <select class="col-lg-5" name="inputCategoria" id="inputCategoria">
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                            <option value="C">C</option>
                            <option value="AC">AC</option>
                            <option value="D">D</option>
                            <option value="AD">AD</option>
                        </select></br>
                        <label class="col-lg-5" for="inputDataEmissao">Data de Emissão:</label>
                        <input class="col-lg-5" type="date" name="inputDataEmissao" id="inputDataEmissao">
                        <label class="col-lg-5" for="inputDataValidade">Data de Validade:</label>
                        <input class="col-lg-5" type="date" name="inputDataValidade" id="inputDataValidade">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary">Limpar</button>
                    <button type="submit" id="salvarNovoColab" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- END OF CODE -->
    <!-- MODAIS -->
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