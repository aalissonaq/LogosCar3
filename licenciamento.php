
<!DOCTYPE html>
<html>
<?php
    require_once('config.php');
    require_once('session.php');
    require_once('isMobile.php');
    require_once('header.php');
    
    $qr='';
    // listar todos os veículos
    try{
        if($level=='MTR')
            $query = $bd->prepare('SELECT * FROM tb_veiculo ORDER BY placa ASC');
        if($level=='ADM'){
            $query = $bd->prepare('SELECT * FROM tb_veiculo WHERE id_uf = :myuf ORDER BY placa ASC');
            $query->bindParam(':myuf',$myuf);
        }
        $query->execute();
        $veiculos = $query->fetchAll(PDO::FETCH_OBJ);
    } catch(PDOException $e){
        echo $e->getMessage();
    }
    if(count($_POST)>0){
        //filtro de licenciamentos
            $veiculo = $_POST['selectVeiculo'];
            $anoLic = $_POST['selectAnoLic'];
            $pago = $_POST['statusPgto'];
            if($pago!='n'){
                $qr .= ' AND l.pago = '.$veiculo.' ';
            }
            if($veiculo!='0'){
                $qr .= ' AND l.id_veiculo = '.$veiculo.' ';
            }
            if($anoLic!='0'){
                $qr .= ' AND l.ano_lic = '.$veiculo.' ';
            }
            if($_POST['valorDe']!='' || $_POST['valorAte']!=''){
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
                $qr .= " AND l.valor_total BETWEEN '".$valorDe."' AND '".$valorAte."' ";
            }
    }
    try{
        if($level=='OPR')
            header('Location: sys.php');
        if($level=='MTR')
            $query = $bd->prepare('SELECT v.id_veiculo, v.montadora, v.modelo, v.placa, v.alias, l.* FROM tb_licenciamento as l, tb_veiculo as v WHERE v.id_veiculo = l.id_veiculo '.$qr);
        if($level=='ADM'){
            $query = $bd->prepare('SELECT v.id_veiculo, v.montadora, v.modelo, v.placa, v.alias, l.* FROM tb_licenciamento as l, tb_veiculo as v WHERE v.id_veiculo = l.id_veiculo AND m.uf_multa = :myuf'.$qr);
            $query->bindParam(':myuf',$myuf);
        }
        $query->execute();
        $lics = $query->fetchAll(PDO::FETCH_OBJ);
    } catch(PDOException $e){
        echo $e->getMessage();
    }
?>
<head>
    <link rel="stylesheet" href="<?php echo BASE;?>/css/configura.css">
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
        <?php } ?>
    </style>
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
                        echo '<div id="avisoGet" class="alert alert-success" role="alert"> Licenciamento lançado com sucesso! </div>';
                    } else{
                        echo '<div id="avisoGet" class="alert alert-danger" role="alert"> Ops! Não foi possível efetuar o lançamento do licenciamento... </div>';
                    }
                }
                if(!@$_GET['edt']==''){
                    if($_GET['edt']==1){
                        echo '<div id="avisoGet" class="alert alert-success" role="alert"> Licenciamento editad com sucesso! </div>';
                    } else{
                        echo '<div id="avisoGet" class="alert alert-danger" role="alert"> Ops! Não foi possível editar o licenciamento... </div>';
                    }
                }
            ?>
            <div id="teste">
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Licenciamento</h4>
                </div>
                <div class="card-body justify-content-center row">
                    <div class="card">
                        <div class="card-header btn" id="ctrl_filtros1">
                            <h6>Filtros</h6>
                        </div>
                        <form action="licenciamento.php" method="post">
                            <input type="hidden" name="id_search" value="geral">
                            <div class="card-body justify-content-center align-center row" id="show1">
                                <div class="control-form card filtro-card">
                                    <label for="selectVeiculo">Veículo:</label>
                                    <select name="selectVeiculo" id="selectVeiculo">
                                        <option value="0">Selecione...</option>;
                                    <?php
                                        $menorAno = date('Y');
                                        foreach($veiculos as $rc){
                                            echo '<option value="'.$rc->id_veiculo.'">'.$rc->alias.' ('.$rc->placa.')</option>';
                                            if($menorAno > date('Y', strtotime($rc->data_recebimento)))
                                                $menorAno = date('Y', strtotime($rc->data_recebimento));
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="control-form card filtro-card">
                                    <label for="selectVeiculo">Ano Licenciamento:</label>
                                    <select name="selectAnoLic" id="selectAnoLic">
                                        <option value="0">Selecione...</option>;
                                    <?php
                                        for($i=$menorAno;$i<=date(Y);$i++){
                                            echo '<option value="'.$i.'">'.$i.'</option>';
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="control-form card filtro-card">
                                    <label for="">Status:</label>
                                    <select name="statusPgto" id="statusPgto">
                                        <option value="n">Selecione...</option>
                                        <option value="0">Em Aberto</option>
                                        <option value="1">Pago</option>
                                    </select>
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
                <div class="card-body">
                    <h5 class="card-title">Lista de Licenciamentos</h5>
                    <table id="listaLics" class="table table-striped table-bordered <?php if($isMobile){ echo 'table-responsive';}?>" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ano</th>
                                <th>Veiculo</th> <!-- Montadora Modelo (Placa)-->
                                <th>Data Pgto</th>
                                <th>Valor (R$)</th>
                                <th>Status</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($lics as $lic){?>
                            <tr>
                                <td><?php echo $lic->id_licenciamento;?></td>
                                <td><?php echo $lic->ano_lic;?></td>
                                <td><?php echo $lic->montadora.' '.$lic->modelo.' ('.$lic->placa.')';?></td>
                                <?php if($lic->data_pgto !=''){?>
                                <td><?php echo date('d/m/Y', strtotime($lic->data_pgto));?></td>
                                <?php }else{?>
                                <td><span class="badge badge-pill badge-warning">EM ABERTO</span></td>
                                <?php }?>
                                <?php if($lic->valor_total!=''){?>
                                <td title="<?php echo 'IPVA: '.$lic->valor_ipva.' - DPVAT: '.$lic->valor_dpvat.' - Tx. Licenciamento: '.$lic->valor_lic;?>"><?php echo 'R$ '.$lic->valor_total;?></td>
                                <?php }else{?>
                                <td><span class="badge badge-pill badge-warning">NÃO INFORMADO</span></td>
                                <?php }?>
                                <?php if($lic->pago==1){?>
                                <td style="color: green"><i class="fas fa-check-square fa-2x" title="Pago: <?php if($lic->condicoes!=NULL) echo $lic->condicoes; else echo 'À vista'; ?>"></i></td>
                                <?php } else{ ?>
                                <td style="color: red"><i class="fas fa-times-circle fa-2x" title="Não Pago"></i></td>
                                <?php }?>
                                <td>
                                    <button class="btn btn-info btn-sm" title="Alterar Status" onclick="editLic(<?php echo $lic->id_licenciamento;?>)"><i class="fas fa-file-invoice-dollar"></i></button>
                                    <?php if($lic->doc_pgto!=NULL){?>
                                    <button class="btn btn-secondary btn-sm" title="Ver Doc Assinado" onclick="viewDocPaid(<?php echo $lic->id_licenciamento;?>)"><i class="far fa-eye"></i></button>
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
    <?php
        require_once('modaisMenu.php');
    ?>
    <!-- inicio modal editar licenciamento -->
    <div class="modal fade" id="modalEditaLic" tabindex="-1" role="dialog" aria-labelledby="modalEditaLic" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabelLic"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEditMulta" action="edtlicenciamento.php" method="post">
                        <input type="hidden" name="inputIDLic" id="inputIDLic">
                    <div class="control-form text-center justify-content-center">
                        <div class="control-form text-center justify-content-center">
                            <label class="col-lg-5" for="inputEditVeiculo"><h4 id="veiculo-string"></h4></label>
                        </div>
                        <div class="control-form justify-content-center text-center">
                            <label class="col-lg-5" for="inputEditValorIPVA">Valor IPVA (RS):</label>
                            <input class="col-lg-5 valoreslic" type="text" name="inputEditValorIPVA" placeholder="Valor (R$)" id="inputEditValorIPVA" onkeydown="fMasc(this,mCash)" required>
                        </div>
                        <div class="control-form justify-content-center text-center">
                            <label class="col-lg-5" for="inputEditValorDPVAT">Valor DPVAT (RS):</label>
                            <input class="col-lg-5 valoreslic" type="text" name="inputEditValorDPVAT" placeholder="Valor (R$)" id="inputEditValorDPVAT" onkeydown="fMasc(this,mCash)" required>
                        </div>
                        <div class="control-form justify-content-center text-center">
                            <label class="col-lg-5" for="inputEditValorLic">Valor Taxa Licenciamento (RS):</label>
                            <input class="col-lg-5 valoreslic" type="text" name="inputEditValorLic" placeholder="Valor (R$)" id="inputEditValorLic" onkeydown="fMasc(this,mCash)" required>
                        </div>
                        <div class="control-form justify-content-center text-center">
                            <label class="col-lg-5" for="inputEditValorTotal">Valor Total (RS):</label>
                            <input class="col-lg-5" type="text" name="inputEditValorTotal" placeholder="Valor (R$)" id="inputEditValorTotal" onkeydown="fMasc(this,mCash)" readonly>
                        </div>
                    </div><hr>
                    <div class="control-form justify-content-center text-center">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="switchPgto">
                            <label class="custom-control-label" for="switchPgto">Informar Pagamento do Licenciamento</label>
                        </div>
                    </div>
                    <div id="dados-pgto">
                        <hr>
                        <div class="row justify-content-center text-center">
                            <div class="col-lg-5 control-form justify-content-center text-center">
                                <label for="inputFormaPgto">Forma Pgto:</label>
                                <select name="inputFormaPgto" id="inputFormaPgto" style="margin-left: 7px;">
                                    <option value="av">À vista (1x)</option>
                                    <option value="pc">Parcelado</option>
                                </select>
                            </div>
                            <div class="col-lg-5 control-form justify-content-center text-center" id="formaPgtoParc">
                                <label class="pgtoParcelado" for="inputParcelamento">Parcelas:</label>
                                <select class="pgtoParcelado" name="inputParcelamento" id="inputParcelamento" style="margin-left: 7px;">
                                    <!-- opções de parcelamento -->
                                </select>
                            </div>
                        </div><hr>
                        <div class="row justify-content-center text-center" style="margin-top: 14px;">
                            <div class="control-form justify-content-center text-center col-lg-5">
                                <div>
                                <label class="col-lg-2" for="inputDataPgto">Data: </label>
                                </div>
                                <div>
                                <input class="col-lg-8" type="date" name="inputDataPgto" id="inputDataPgto" max="<?php echo date('Y-m-d');?>" required>
                                </div>
                            </div>
                            <div class="control-form justify-content-center text-center col-lg-5">
                                <div>
                                <label class="col-lg-3" for="inputDocSign">Comprovante:</label>
                                </div>
                                <div>
                                <input class="col-lg-7" type="file" name="inputDocSign" id="inputDocSign" accept="application/pdf" onchange="converteBase64();" required>
                                </div>
                                <input type="hidden" name="docSign" id="docSign">
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" id="cleanEdtLic" class="btn btn-secondary">Limpar</button>
                    <button type="submit" id="saveEdtLic" class="btn btn-primary">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- fim modal editar licenciamento-->
    <!-- inicio modal verDoc-->
    <div class="modal fade" id="modalVerDoc" tabindex="-1" role="dialog" aria-labelledby="modalVerDoc" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-file-invoice-dollar" ></i> Comprovante: </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" height="180px">
                    <div class="row justify-content-center text-center" id="divDoc">
                            <!-- carregar documento -->
                    </div>
                </div>
                <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-secondary">Fechar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- fim modal verDoc-->

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo BASE;?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo BASE;?>/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- DataTables -->
    <script type="text/javascript" src="vendor/datatables.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo BASE;?>/vendor/font-awesome/js/all.min.js"></script>
    <script src="<?php echo BASE;?>/vendor/font-awesome/js/fontawesome.min.js"></script>
    <script src="<?php echo BASE;?>/js/licenciamento.js"></script>
    <script src="<?php echo BASE;?>/js/register.js"></script>
    <script src="<?php echo BASE;?>/js/masks.js"></script>
    <script src="<?php echo BASE;?>/sw.js"></script>
    </body>
</html>