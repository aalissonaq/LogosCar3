
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
    
    $qr='';
    // listar todos os motoristas
    try{
        if($level=='MTR')
            $query = $bd->prepare(' SELECT nome_user, id_user FROM tb_users WHERE motorista = true');   
        if($level=='ADM'){
            $query = $bd->prepare(' SELECT nome_user, id_user FROM tb_users WHERE motorista = true AND id_uf = :myuf ORDER BY nome_user ASC');
            $query->bindParam(':myuf',$myuf);
        }
        $query->execute();
        $motoristas  = $query->fetchAll(PDO::FETCH_OBJ);
    } catch(PDOException $e){
        echo $e->getMessage();
    }
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
        //filtro de multas
            $veiculo = $_POST['selectVeiculo'];
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
            $qr .= " AND m.data_multa BETWEEN '".$dataDe."' AND '".$dataAte."' ";
            if($veiculo!='0'){
                $qr .= ' AND v.id_veiculo = '.$veiculo.' ';
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
            $query = $bd->prepare('SELECT u.nome_user, u.id_user, v.id_veiculo, v.montadora, v.modelo, v.placa, v.alias, v.renavam, m.* FROM tb_multas as m, tb_users as u, tb_veiculo as v WHERE m.id_motorista = u.id_user AND m.id_veiculo = v.id_veiculo'.$qr);
        if($level=='ADM'){
            $query = $bd->prepare('SELECT u.nome_user, u.id_user, v.id_veiculo, v.montadora, v.modelo, v.placa, v.alias, v.renavam, m.* FROM tb_multas as m, tb_users as u, tb_veiculo as v WHERE m.id_motorista = u.id_user AND m.id_veiculo = v.id_veiculo AND m.uf_multa = :myuf'.$qr);
            $query->bindParam(':myuf',$myuf);
        }
        $query->execute();
        $multas = $query->fetchAll(PDO::FETCH_OBJ);
    } catch(PDOException $e){
        echo $e->getMessage();
    }
?>
<head>
    <link rel="stylesheet" href="<?php echo BASE;?>/css/configura.css">
    <style>
        a#pesquisaMotoristaMulta{
            color: #17a2b8;
            
        }
        a#pesquisaMotoristaMulta:hover{
            color: white;
        }
        .control-space{
            margin-right: 7px;
            margin-left: 7px;
        }
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
                        echo '<div id="avisoGet" class="alert alert-success" role="alert"> Multa cadastrada com sucesso! </div>';
                    } else{
                        echo '<div id="avisoGet" class="alert alert-danger" role="alert"> Ops! Não foi possível efetuar o cadastro da multa... </div>';
                    }
                }
                if(!@$_GET['edt']==''){
                    if($_GET['edt']==1){
                        echo '<div id="avisoGet" class="alert alert-success" role="alert"> Multa editada com sucesso! </div>';
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
                                        foreach($motoristas as $rm){
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
                                        foreach($veiculos as $rc){
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
                            <td> <?php echo $multa->montadora.' '.$multa->modelo.' ('.$multa->alias.')'; ?> </td>
                            <td> <?php echo $multa->placa; ?> </td>
                            <td> <label style="display: none;"><?php echo $multa->data_multa; ?></label><?php echo date('d/m/Y', strtotime($multa->data_multa)); ?> </td>
                            <td> <?php echo $multa->cidade_multa.' / '.$multa->uf_multa; ?> </td>
                            <td> <?php echo $multa->local_multa; ?> </td>
                            <td> R$ <?php echo $multa->valor_multa; ?> </td>
                            <?php if($multa->pago==1){?>
                            <td style="color: green"><i class="fas fa-check-square fa-2x" title="Pago: <?php if($multa->condicao_pgto!=NULL) echo $multa->condicao_pgto; else echo 'À vista'; ?>"></i></td>
                            <?php } else if($multa->pago==0){ ?>
                            <td style="color: red"><i class="fas fa-times-circle fa-2x" title="Não Pago"></i></td>
                            <?php } else if($multa->pago==2){ ?>
                            <td style="color: #dbd33b"><i class="fas fa-bullhorn fa-2x" title="Contestada"></i></td>
                            <?php }?>
                            <td>
                                <button class="btn btn-info btn-sm" title="Alterar Status" onclick="editMulta(<?php echo $multa->id_multa;?>)"><i class="fas fa-file-invoice-dollar"></i></button>
                                <?php if($multa->termo_assinado!=NULL){?>
                                <button class="btn btn-secondary btn-sm" title="Ver Doc Assinado" onclick="verDocSigned(<?php echo $multa->id_multa;?>)"><i class="far fa-eye"></i></button>
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
    <div class="modal fade" id="modalAddMulta" tabindex="-1" role="dialog" aria-labelledby="modalAddMulta" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
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
                    <form id="formMulta" action="addmulta.php" method="post">
                    <div class="control-form justify-content-center text-center">
                        <label class="col-lg-5" for="inputDataOcorrência">Qual a data da multa?</label>
                        <input class="col-lg-5" type="date" name="inputDataOcorrencia" id="inputDataOcorrencia" value="<?php echo date('Y-m-d');?>" max="<?php echo date('Y-m-d');?>">
                    </div>
                    <div class="control-form justify-content-center text-center">
                        <label class="col-lg-5" for="inputHoraOcorrência">Qual a hora da multa?</label>
                        <input class="col-lg-5" type="time" value="<?php echo date('H:i:s');?>" name="inputHoraOcorrencia" id="inputHoraOcorrencia" step="1">
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
                        <a style="margin-top: 3%; margin-bottom: 3%;" id="pesquisaMotoristaMulta" class="btn btn-outline-info col-lg-10"><i class="fas fa-search-location"></i> Pesquisar</a>
                    </div>
                    <div id="dados-motorista">
                        <hr>
                        <div class="control-form text-center justify-content-center">
                            <label class="col-lg-5" for="inputNomeCondutor">Nome do condutor:</label>
                            <input class="col-lg-5" type="text" name="inputNomeCondutor" id="inputNomeCondutor" readonly>
                            <input type="hidden" id="inputIDCondutor" name="inputIDCondutor">
                            <input type="hidden" id="inputIDVeiculo" name="inputIDVeiculo">
                        </div>
                        <div class="control-form text-center justify-content-center">
                            <label class="col-lg-5" for="inputRotaEfetuada">Rota Efetuada:</label>
                            <input class="col-lg-5" type="text" name="inputRotaEfetuada" id="inputRotaEfetuada" readonly>
                        </div>
                        <div class="control-form text-center justify-content-center">
                            <label class="col-lg-5" for="inputIDViagem">ID da viagem:</label>
                            <input class="col-lg-5" type="text" name="inputIDViagem" id="inputIDViagem" readonly>
                        </div>
                        <div class="control-form justify-content-center text-center">
                            <label class="col-lg-5" for="inputUF inputCidade">Local da multa:</label>
                            <select class="col-lg-2" name="inputUF" id="inputUF">
                            <!-- Cidades -->
                            </select>
                            <select class="col-lg-3" name="inputCidade" id="inputCidade">
                            <!-- Cidades -->
                            </select>
                        </div>
                        <div class="control-form justify-content-center text-center">
                            <label class="col-lg-5" for="inputUF inputTrecho">Trecho da Ocorrência:</label>
                            <input class="col-lg-5" type="text" name="inputTrecho" id="inputTrecho" required>
                            <label class="col-lg-5" for="inputUF inputTrecho">Valor (R$):</label>
                            <input class="col-lg-5" type="text" name="inputValorMulta" id="inputValorMulta" onkeydown="fMasc(this,mCash)" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" onclick="hideData()" class="btn btn-secondary">Limpar</button>
                    <button type="submit" id="salvarNovoColab" class="btn btn-primary">Adicionar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- fim modal add multa -->
    <!-- inicio modal editar multa -->
    <div class="modal fade" id="modalEditaMulta" tabindex="-1" role="dialog" aria-labelledby="modalEditaMulta" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-exclamation-triangle"></i> Editar Status: Multa </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="control-form text-center justify-content-center">
                        <div class="control-form text-center justify-content-center">
                            <label class="col-lg-5" for="inputEditInfrator">Nome do condutor:</label>
                            <input class="col-lg-5" type="text" name="inputEditInfrator" id="inputEditInfrator" readonly>
                        </div>
                        <div class="control-form text-center justify-content-center">
                            <label class="col-lg-5" for="inputEditLocal">Local da Multa:</label>
                            <input class="col-lg-5" type="text" name="inputEditLocal" id="inputEditLocal" readonly>
                        </div>
                        <div class="control-form text-center justify-content-center">
                            <label class="col-lg-5" for="inputEditVeiculo">Veículo:</label>
                            <input class="col-lg-5" type="text" name="inputEditVeiculo" id="inputEditVeiculo" readonly>
                        </div>
                        <div class="control-form justify-content-center text-center">
                            <label class="col-lg-5" for="inputEditValor">Valor a descontar (RS):</label>
                            <input class="col-lg-5" type="text" name="inputEditValor" id="inputEditValor" onkeydown="fMasc(this,mCash)" required>
                        </div>
                    </div><hr>
                    <form id="formEditMulta" action="edtmulta.php" method="post">
                    <div class="control-form text-center justify-content-center">
                        <label for="radioAceite">O motorista confirma a multa?</label>
                    </div>
                    <div class="control-form text-center justify-content-center">
                        <input class="control-space" type="radio" name="aceitaMulta" id="aceitaSim" value="1" checked>
                        <label class="control-space" for="aceitaSim">Sim, aceita a multa.</label>
                        <input class="control-space" type="radio" name="aceitaMulta" id="aceitaNao" value="0">
                        <label class="control-space" for="aceitaNao">Não, pretende contestar.</label>
                    </div>
                    <div id="dados-multa">
                        <hr>
                        <input type="hidden" name="inputIDMulta" id="inputIDMulta">
                        <div class="row justify-content-center text-center">
                            <div class="col-lg-5 control-form justify-content-center text-center">
                                <label for="inputFormaPgto">Forma Pgto:</label>
                                <select name="inputFormaPgto" id="inputFormaPgto" style="margin-left: 7px;">
                                    <option value="av">À vista (1x)</option>
                                    <option value="pc">Parcelado</option>
                                </select>
                            </div>
                            <div class="col-lg-5 control-form justify-content-center text-center">
                                <label class="pgtoParcelado" for="inputParcelamento">Parcelas:</label>
                                <select class="pgtoParcelado" name="inputParcelamento" id="inputParcelamento" style="margin-left: 7px;">
                                    <!-- opções de parcelamento -->
                                </select>
                            </div>
                        </div>
                        <div class="row justify-content-center text-center" style="margin-top: 14px;">
                            <div class="col-lg-5 control-form justify-content-center text-center">
                                <a class="btn btn-primary" href="data/doc.pdf" target="_blank"><i class="fas fa-file-invoice-dollar"></i> Imprimir Termo de Aceite</a>
                            </div>
                            <div class="col-lg-5 control-form justify-content-center text-center">
                                <input type="file" name="inputDocSign" id="inputDocSign" accept="application/pdf" onchange="converteBase64();" required>
                                <input type="hidden" name="docSign" id="docSign">
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary">Limpar</button>
                    <button type="submit" id="saveEdtMulta" class="btn btn-primary">Editar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- fim modal editar multa-->
    <!-- inicio modal verDoc-->
    <div class="modal fade" id="modalVerDoc" tabindex="-1" role="dialog" aria-labelledby="modalVerDoc" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-file-invoice-dollar" ></i> Documento Assinado: </h5>
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
    <script src="<?php echo BASE;?>/sw.js"></script>
    <script src="<?php echo BASE;?>/js/multas.js"></script>
    </body>
    <script>
        $(document).ready(function(){
            var cidadeAtual = '<?php echo $mycidade;?>';
            var estadoAtual = '<?php echo $myuf;?>';
            $.getJSON("data/brasil.json", function(data){
                var linha = '';
                var coluna = '';
                for(var i=0; i<data.estados.length; i++){
                    if(estadoAtual == data.estados[i].sigla){
                        linha += '<option value="' + data.estados[i].sigla + '" selected>' + data.estados[i].sigla + '</option>';
                        cidadeId = i;
                    } else{
                        linha += '<option value="' + data.estados[i].sigla + '">' + data.estados[i].sigla + '</option>';
                    }
                }
                for(var j=0; j<data.estados[cidadeId].cidades.length;j++){
                    if(cidadeAtual == data.estados[cidadeId].cidades[j]){
                        coluna += '<option value="'+ data.estados[cidadeId].cidades[j] +'" selected>' + data.estados[cidadeId].cidades[j] + '</option>';
                    } else{
                        coluna += '<option value="'+ data.estados[cidadeId].cidades[j] +'">' + data.estados[cidadeId].cidades[j] + '</option>';
                    }
                }
                $('#inputUF').html(linha);
                $('#inputCidade').html(coluna);
                console.log('Está selecionada a cidade de '+ $('#inputCidade').val() +'/'+$('#inputUF').val());
            })
            .fail(function(){
                console.log("Ops! Algo de errado ocorreu na busca das informações...");
            });
        });
        $('#inputUF').change(function(){
            $.getJSON("data/brasil.json", function(data){
                var mont = $('#inputUF').val();
                var a=0;
                var linha = '';
                while(mont!=data.estados[a].sigla){
                    a++;
                }
                for(var i=0; i<data.estados[a].cidades.length; i++){
                    linha += '<option value="' + data.estados[a].cidades[i] + '">' + data.estados[a].cidades[i] + '</option>';
                }
                $('#inputCidade').html(linha);
                console.log('Você selecionou a cidade de ' +$('#inputCidade').val() +'/'+$('#inputUF').val());
            })
                .fail(function(){
                    console.log("Ops! Não foi possível processar a requisição...");
                });
        });
        $("#inputCidade").change(function(){
            console.log('Você selecionou a cidade de ' +$('#inputCidade').val() +'/'+$('#inputUF').val());
        });
        $('input[name=aceitaMulta]').on("change", function(){
            if($(this).val()==0){
                $('#inputDocSign').prop("required",false);
            } else{
                $('#inputDocSign').prop("required",true);
            }
        });
    </script>
</html>