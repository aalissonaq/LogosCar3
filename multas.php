
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
    <style>
        a#pesquisaMotoristaMulta{
            color: #17a2b8;
            
        }
        a#pesquisaMotoristaMulta:hover{
            color: white;
        }
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
                    <form action="salvarmulta.php" method="post">
                    <div class="control-form justify-content-center text-center">
                        <label class="col-lg-5" for="inputDataOcorrência">Qual a data da multa?</label>
                        <input class="col-lg-5" type="date" name="inputDataOcorrencia" id="inputDataOcorrencia" value="<?php echo date('Y-m-d');?>" max="<?php echo date('Y-m-d');?>">
                    </div>
                    <div class="control-form justify-content-center text-center">
                        <label class="col-lg-5" for="inputHoraOcorrência">Qual a hora da multa?</label>
                        <input class="col-lg-5" type="time" value="<?php echo date('H:i:s');?>" name="inputHoraOcorrencia" id="inputHoraOcorrencia" step="2">
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
                            <input class="col-lg-5" type="text" name="inputTrecho" id="inputTrecho">
                            <label class="col-lg-5" for="inputUF inputTrecho">Valor (R$):</label>
                            <input class="col-lg-5" type="text" name="inputValorMulta" id="inputValorMulta" onkeydown="fMasc(this,mCash)">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" onclick="hideData()" class="btn btn-secondary">Limpar</button>
                    <button type="submit" id="salvarNovoColab" class="btn btn-primary">Salvar</button>
                    </form>
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
                    console.log("Ops! Algo de errado ocorreu na busca das informações de modelos da marca...");
                });
        });
        $("#inputCidade").change(function(){
            console.log('Você selecionou a cidade de ' +$('#inputCidade').val() +'/'+$('#inputUF').val());
        });
    </script>
</html>