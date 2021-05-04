
<!DOCTYPE html>
<html>
<?php
    require_once('config.php');
    require_once('session.php');
    require_once('isMobile.php');
    require_once('header.php');

    if(isset($_GET)||($_GET!='')){
        $id=$_GET['id'];
        //$id = 4;
        $query = $bd->prepare('SELECT * FROM tb_users WHERE id_user = :id');
        $query->bindParam(':id', $id);
        $query->execute();
        $user = $query->fetch();
    } else{
        header('Location: usuarios.php?erro=1');
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
        <div class="container">
            <div class="modal-content">
                <div class="modal-header justify-content-center">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Usuário</h5>
                </div>
                <div class="modal-body">
                    <form id="formEdtUsuario" action="edtusuario.php" method="post">
                    <input type="hidden" name="inputID" id="inputID" value="<?php echo $id;?>">
                    <div class="form-group justify-content-center text-center">
                        <label for="inputNomeCompleto inputMatrícula">Colaborador:</label></br>
                        <input class="col-lg-8" type="text" name="inputNomeCompleto" id="inputNomeCompleto" placeholder="Nome Completo" value="<?php echo $user['nome_user'];?>" disabled>
                        <input class="col-lg-3" type="text" name="inputMatrícula" id="inputMatrícula" placeholder="Matrícula" value="<?php echo $user['matr_user'];?>" disabled>
                    </div>
                    <div class="row">
                        <div class="form-group justify-content-center text-center col-lg">
                            <labelfor="inputNivel">Nível:</label></br>
                            <select class="col-lg-10" name="inputNivel" id="inputNivel" <?php if($user['nivel']=='MTR' && $level!='MTR') echo 'disabled title="Você não tem permissão de alterar o nível desse usuário!"';?>>
                                <option value="OPR" <?php if($user['nivel']=='OPR') echo 'selected';?>>Operação</option>
                                <option value="ADM" <?php if($user['nivel']=='ADM') echo 'selected';?>>Administrador</option>
                            <?php if($level=='MTR'){ ?>
                                <option value="MTR" <?php if($user['nivel']=='MTR') echo 'selected';?>>Master</option>
                            <?php } ?>
                            </select>
                        </div>
                        <div class="form-group justify-content-center text-center col-lg">
                            <labelfor="inputNivel">Status:</label></br>
                            <select class="col-lg-10" name="inputStatus" id="inputStatus" <?php if($user['nivel']=='MTR' && $level!='MTR') echo 'disabled title="Você não tem permissão de alterar o status desse usuário!"';?>>
                                <option value="1" <?php if($user['ativo']=='1') echo 'selected';?>>Ativo</option>
                                <option value="0" <?php if($user['ativo']=='0') echo 'selected';?>>Inativo</option>
                            </select>
                        </div>
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
                            <input class="form-check-input" type="radio" name="radioMotorista" id="motoraSim" value="1" <?php if($user['motorista']=='1') echo 'checked';?>>
                            <label class="form-check-label" for="exampleRadios1">
                                Sim
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="radioMotorista" id="motoraNao" value="0" <?php if($user['motorista']=='0') echo 'checked';?>>
                            <label class="form-check-label" for="exampleRadios2">
                                Não
                            </label>
                        </div>
                    </div>
                    <div class="form-group justify-content-center text-center" id="dadosMotorista">
                        <hr/>
                        <label class="col-lg-5" for="inputNumCNH">Nº da CNH:</label>
                        <input class="col-lg-5" type="tel" name="inputNumCNH" id="inputNumCNH" value="<?php echo $user['cnh'];?>" onkeydown="fMasc(this,mNum)" <?php if($user['motorista']=='1') echo 'disabled';?>>
                        <label class="col-lg-5" for="inputDataEmissao">Categoria:</label>
                        <select class="col-lg-5" name="inputCategoria" id="inputCategoria">
                            <option value="A" <?php if($user['cat_cnh']=='A') echo 'selected';?>>A</option>
                            <option value="B" <?php if($user['cat_cnh']=='B') echo 'selected';?>>B</option>
                            <option value="AB" <?php if($user['cat_cnh']=='AB') echo 'selected';?>>AB</option>
                            <option value="C" <?php if($user['cat_cnh']=='C') echo 'selected';?>>C</option>
                            <option value="AC <?php if($user['cat_cnh']=='AC') echo 'selected';?>">AC</option>
                            <option value="D" <?php if($user['cat_cnh']=='D') echo 'selected';?>>D</option>
                            <option value="AD" <?php if($user['cat_cnh']=='AD') echo 'selected';?>>AD</option>
                        </select></br>
                        <label class="col-lg-5" for="inputDataEmissao">Data de Emissão:</label>
                        <input class="col-lg-5" type="date" name="inputDataEmissao" value="<?php if($user['data_emissao']!='') echo str_replace(" ","T",$user['data_emissao']);?>" id="inputDataEmissao">
                        <label class="col-lg-5" for="inputDataValidade">Data de Validade:</label>
                        <input class="col-lg-5" type="date" name="inputDataValidade" value="<?php if($user['data_validade']!='') echo str_replace(" ","T",$user['data_validade']);?>" id="inputDataValidade">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" id="voltar">Voltar</a>
                    <button type="reset" class="btn btn-secondary">Limpar</button>
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                    </form>
                </div>
            </div></br>
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
            showDadosMotorista();
            
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

            var cidadeAtual = '<?php echo $user['cidade'];?>';
            var estadoAtual = '<?php echo $user['uf'];?>';
            var cidadeId = 0;
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
        $('#dadosMotorista').hide();
        $('input[name="radioMotorista"]').change(function () {
            if ($('input[name="radioMotorista"]:checked').val() === '1') {
                $('#dadosMotorista').fadeIn(260);
            }
            if ($('input[name="radioMotorista"]:checked').val() === '0') {
                $('#dadosMotorista').fadeOut(250);
            }
        });
        function showDadosMotorista(){
            if ($('input[name="radioMotorista"]:checked').val() === '1') {
                $('#dadosMotorista').fadeIn(260);
            }
            if ($('input[name="radioMotorista"]:checked').val() === '0') {
                $('#dadosMotorista').fadeOut(250);
            }
        }
        $('#voltar').on('click', function(){
            window.location.href = 'usuarios.php';
        });
        $('#inputNumCNH').on('blur', function(){
            if(($('#inputNumCNH').val()=='') && $('input[name="radioMotorista"]:checked').val() === '1'){
                alert('O número da CNH é um campo obrigatório. Por favor preencha-o para prosseguir.');
                $('#motoraSim').focus();
            } else{
                $.ajax({
                    url: 'consultaMatr.php?act=cnh&num='+$("#inputNumCNH").val(),
                    dataType: 'json',
                    success: function(resposta){
                        if(resposta.data.cnh == $('#inputNumCNH').val()){
                            alert('Essa CNH já foi cadastrada. Por favor verifique e, persistindo, entre em contato com o NTI.');
                            document.getElementById('inputNumCNH').value='';
                            $('#motoraSim').focus();
                        }
                    },
                    error: function(resposta){
                        console.log(resposta.data);
                        alert('Nossa base de dados está indisponível. Favor atualizar a página e/ou tentar novamente em breve.');
                    }
                });
            }
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
    <!-- <script src="<?php echo BASE;?>/js/editusuarios.js"></script> -->
    <script src="<?php echo BASE;?>/js/masks.js"></script>
    <script src="<?php echo BASE;?>/sw.js"></script>
    </body>
</html>