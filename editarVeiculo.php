
<!DOCTYPE html>
<html>
<?php
    require_once('config.php');
    require_once('session.php');
    require_once('isMobile.php');
    require_once('header.php');

    if(isset($_GET)||($_GET!='')){
        $id=$_GET['aZhDelkuQw'];
        $query = $bd->prepare('SELECT * FROM tb_veiculo WHERE id_veiculo = :id');
        $query->bindParam(':id', $id);
        $query->execute();
        $veiculo = $query->fetch();
    } else{
        header('Location: veiculos.php?erro=1');
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
        <div class="modal-header justify-content-center text-center">
            <h5 class="modal-title" id="exampleModalLabel">Editar Veículo</h5>
        </div>
        <div class="modal-body">
            <label for="sobre"><h6>Sobre o veiculo:</h6></label><hr/>
            <form id="formEdtVeiculo" action="edtveiculo.php" method="post">
            <input type="hidden" name="inputID" id="inputID" value="<?php echo $id;?>">
            <div class="form-group justify-content-center text-center">
                <select class="col-lg-5" name="inputMontadora" id="inputMontadora" <?php if($level!='MTR'){ echo 'title="Você não tem permissão para alterar este dado!" disabled';} else{ echo 'required';}?>>
                </select>
                <select class="col-lg-5" name="inputModelo" id="inputModelo" <?php if($level!='MTR'){ echo 'title="Você não tem permissão para alterar este dado!" disabled';} else{ echo 'required';}?>>
                </select>
            </div>
            <div class="form-group justify-content-center text-center">
                <input class="col-lg-4" type="text" name="inputPlaca" id="inputPlaca" placeholder="Placa do Veiculo" minlength="7" maxlength="7" value="<?php echo $veiculo['placa'];?>" <?php if($level!='MTR'){ echo 'title="Você não tem permissão para alterar este dado!" disabled';} else{ echo 'required';}?>>
                <input class="col-lg-3" type="text" name="inputAnoFab" id="inputAnoFab" placeholder="Ano Fab." minlength="4" maxlength="4" onkeyup="fMasc(this,mNum)" value="<?php echo $veiculo['ano_fab'];?>" <?php if($level!='MTR'){ echo 'title="Você não tem permissão para alterar este dado!" disabled';} else{ echo 'required';}?>>
                <input class="col-lg-3" type="text" name="inputAnoMod" id="inputAnoMod" placeholder="Ano Mod." minlength="4" maxlength="4" onkeyup="fMasc(this,mNum)" value="<?php echo $veiculo['modelo_fab'];?>" <?php if($level!='MTR'){ echo 'title="Você não tem permissão para alterar este dado!" disabled';} else{ echo 'required';}?>>
            </div>
            <div class="form-group justify-content-center text-center">
                <input class="col-lg-5" type="text" name="inputRENAVAM" id="inputRENAVAM" placeholder="RENAVAM" maxlength="11" value="<?php echo $veiculo['renavam'];?>" onkeydown="fMasc(this,mNum)" <?php if($level!='MTR'){ echo 'title="Você não tem permissão para alterar este dado!" disabled';} else{ echo 'required';}?>>
                <input class="col-lg-5" type="text" name="inputChassi" id="inputChassi" placeholder="Nº. Chassi" maxlength="17" value="<?php echo $veiculo['chassi'];?>" <?php if($level!='MTR'){ echo 'title="Você não tem permissão para alterar este dado!" disabled';} else{ echo 'required';}?>>
            </div>
            <div class="form-group justify-content-center text-center">
                <label for="inputCor">Cor Predominante</label></br>
                <select class="col-lg-5" name="inputCor" id="inputCor">
                    <option value="#000000" <?php  if($veiculo['cor']=='#000000') echo 'selected';?>>Preto</option>
                    <option value="#FFFFFF" <?php  if($veiculo['cor']=='#FFFFFF') echo 'selected';?>>Branco</option>
                    <option value="#6B6B6B" <?php  if($veiculo['cor']=='#6B6B6B') echo 'selected';?>>Cinza</option>
                    <option value="#FF0000" <?php  if($veiculo['cor']=='#FF0000') echo 'selected';?>>Vermelho</option>
                    <option value="#0000FF" <?php  if($veiculo['cor']=='#0000FF') echo 'selected';?>>Azul</option>
                    <option value="#FFFF00" <?php  if($veiculo['cor']=='#FFFF00') echo 'selected';?>>Amarelo</option>
                    <option value="#653608" <?php  if($veiculo['cor']=='#653608') echo 'selected';?>>Marrom</option>
                    <option value="">Outra...</option>
                </select>
                <input class="col-lg-5" type="color" name="Cor" id="Cor" value="<?php echo $veiculo['cor'];?>" disabled>
            </div>
        </div>
        <div class="modal-body">
            <label for="sobre"><h6>Dados do veiculo:</h6></label><hr/>
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
                <input class="col-lg-5" type="text" name="inputAlias" id="inputAlias" value="<?php echo $veiculo['alias'];?>" required>
            </div>
            <div class="form-group justify-content-center text-center">
                <label class="col-lg-5" for="inputDataRecebimento">Data Recebimento: </label>
                <input class="col-lg-5" type="date" name="inputDataRecebimento" id="inputDataRecebimento" value="<?php echo $veiculo['data_recebimento'];?>" required>
                <label class="col-lg-5" for="inputDataRecebimento">KM de recebimento:</label>
                <input class="col-lg-5" type="tel" name="inputKMRecebimento" id="inputKMRecebimento" maxlength="7" value="<?php echo $veiculo['kilometragem'];?>" onkeydown="javascript: fMasc( this, mNum );" required>
            </div>
            <div class="form-group justify-content-center text-center">
                <label for="inputProprietario">Proprietário:</label></br>
                <select class="col-lg-10" name="inputProprietario" id="inputProprietario" <?php if($level!='MTR'){ echo 'title="Você não tem permissão para alterar este dado!" disabled';} else{ echo 'required';}?>>
                    <option value="Logos" <?php  if($veiculo['proprietario']=='Logos') echo 'selected';?>>Logos</option>
                    <option value="Avis" <?php  if($veiculo['proprietario']=='Avis') echo 'selected';?>>Avis</option>
                    <option value="Hertz" <?php  if($veiculo['proprietario']=='Hertz') echo 'selected';?>>Hertz</option>
                    <option value="Localiza" <?php  if($veiculo['proprietario']=='Localiza') echo 'selected';?>>Localiza</option>
                    <option value="Movida" <?php  if($veiculo['proprietario']=='Movida') echo 'selected';?>>Movida</option>
                    <option value="Unidas" <?php  if($veiculo['proprietario']=='Unidas') echo 'selected';?>>Unidas</option>
                    <option value="Outra..." <?php  if($veiculo['proprietario']=='Outra...') echo 'selected';?>>Outra...</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-warning" id="voltar">Voltar</a>
            <button type="reset" class="btn btn-secondary">Limpar</button>
            <button type="submit" class="btn btn-primary">Salvar</button>
            </form>
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
    <script>
        $(document).ready(function(){
            setTimeout(function() { 
                $('#avisoGet').hide();
            }, 5000);
            
            $('#listaUsuarios').DataTable( {
                dom: 'Bfrtip',
                language: {
                    "decimal":        "",
                    "emptyTable":     "No data available in table",
                    "info":           "Mostrando _START_ a _END_ de um total de _TOTAL_ resultados",
                    "infoEmpty":      "Mostrando 0 a 0 de 0 resultados",
                    "infoFiltered":   "(filtrado de _MAX_ resultados totais)",
                    "infoPostFix":    "",
                    "thousands":      ".",
                    "lengthMenu":     "Mostrar _MENU_ resultados",
                    "loadingRecords": "Carregando...",
                    "processing":     "Processando...",
                    "search":         "Pesquisa:",
                    "zeroRecords":    "Sem resultados encontrados",
                    "paginate": {
                        "first":      "Primeiro",
                        "last":       "Último",
                        "next":       "Próximo",
                        "previous":   "Anterior"
                    },
                    "aria": {
                        "sortAscending":  ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    },
                    "aria": {
                        "sortAscending": ": Ordenar colunas de forma ascendente",
                        "sortDescending": ": Ordenar colunas de forma descendente"
                    },
                    "select": {
                        "rows": {
                            "_": "Selecionado %d linhas",
                            "0": "Nenhuma linha selecionada",
                            "1": "Selecionado 1 linha"
                        },
                        "1": "%d linha selecionada",
                        "_": "%d linhas selecionadas",
                        "cells": {
                            "1": "1 célula selecionada",
                            "_": "%d células selecionadas"
                        },
                        "columns": {
                            "1": "1 coluna selecionada",
                            "_": "%d colunas selecionadas"
                        }
                    },
                    "buttons": {
                        "copySuccess": {
                            "1": "Uma linha copiada com sucesso",
                            "_": "%d linhas copiadas com sucesso"
                        },
                        "collection": "Coleção  <span class=\"ui-button-icon-primary ui-icon ui-icon-triangle-1-s\"><\/span>",
                        "colvis": "Visibilidade da Coluna",
                        "colvisRestore": "Restaurar Visibilidade",
                        "copy": "Copiar",
                        "copyKeys": "Pressione ctrl ou u2318 + C para copiar os dados da tabela para a área de transferência do sistema. Para cancelar, clique nesta mensagem ou pressione Esc..",
                        "copyTitle": "Copiar para a Área de Transferência",
                        "csv": "CSV",
                        "excel": "Excel",
                        "pageLength": {
                            "-1": "Mostrar todos os registros",
                            "1": "Mostrar 1 registro",
                            "_": "Mostrar %d registros"
                        },
                        "pdf": "PDF",
                        "print": "Imprimir"
                    },
                },
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            } );

            var cidadeAtual = '<?php if($veiculo['id_cidade']!='') echo $veiculo['id_cidade'];?>';
            var estadoAtual = '<?php if($veiculo['id_uf']!='') echo $veiculo['id_uf'];?>';
            var cidadeId = 0;
            var montadoraAtual = '<?php if($veiculo['montadora']!='') echo $veiculo['montadora'];?>';
            var modeloAtual = '<?php if($veiculo['modelo']!='') echo $veiculo['modelo'];?>';
            var modeloId = 0;
            //  Carregando a lista de cidades de arquivo JSON
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
            $.getJSON("data/carros.json", function(data){
                console.log("data access OK!");
                var linha = '';
                var coluna = '';
                for(var i=0; i<data.montadoras.length; i++){
                    if(data.montadoras[i].nome == montadoraAtual){
                        modeloId = i;
                        linha += '<option value="' + data.montadoras[i].nome + '" selected>' + data.montadoras[i].nome + '</option>';
                    } else{
                        linha += '<option value="' + data.montadoras[i].nome + '">' + data.montadoras[i].nome + '</option>';
                    }
                }
                for(var j=0; j<data.montadoras[modeloId].modelos.length;j++){
                    if(data.montadoras[modeloId].modelos[j] == modeloAtual){
                        coluna += '<option value="' + data.montadoras[modeloId].modelos[j] + '" selected>' + data.montadoras[modeloId].modelos[j] + '</option>';
                    } else{
                        coluna += '<option value="' + data.montadoras[modeloId].modelos[j] + '">' + data.montadoras[modeloId].modelos[j] + '</option>';
                    }
                        
                }
                $('#inputMontadora').html(linha);
                $('#inputModelo').html(coluna);
                console.log('Está selecionado o ' + $("#inputModelo").val() + ' da montadora ' +$('#inputMontadora').val());
            })
            .fail(function(){
                console.log("Ops! Algo de errado ocorreu na busca das informações...");
            });
        });
        $('#voltar').on('click', function(){
            window.location.href = 'veiculos.php';
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
    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo BASE;?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo BASE;?>/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- DataTables -->
    <script type="text/javascript" src="vendor/datatables.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo BASE;?>/vendor/font-awesome/js/all.min.js"></script>
    <script src="<?php echo BASE;?>/vendor/font-awesome/js/fontawesome.min.js"></script>
    <script src="<?php echo BASE;?>/js/register.js"></script>
    <script src="<?php echo BASE;?>/js/editveiculos.js"></script>
    <script src="<?php echo BASE;?>/js/masks.js"></script>
    <script src="<?php echo BASE;?>/sw.js"></script>
    </body>
</html>