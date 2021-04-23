<!DOCTYPE html>
<html>
<?php
    require_once('config.php');
    require_once('session.php');
    require_once('isMobile.php');
    require_once('header.php');
    require_once('src/conn/connect.php');
    //puxando os logs do BD
    $stmt = $bd->prepare('SELECT u.id_user, u.matr_user, u.nome_user, l.* FROM tb_users as u, tb_log as l WHERE l.id_user = u.id_user ORDER BY l.id_login DESC');
    $stmt->execute();
    $lista_log = $stmt->fetchAll(PDO::FETCH_OBJ);

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
            <div id="teste">
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Log do Sistema</h4>
                </div>
                <div class="card-body">
                    <table id="listaLogs" class="table table-striped table-bordered <?php if($isMobile){ echo 'table-responsive';}?>" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuário</th>
                                <th>Localidade</th>
                                <th>IP</th>
                                <th>Dispositivo</th>
                                <th>Data do Evento</th>
                                <th>Ocorrência</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($lista_log as $log){
                                    echo '<tr>';
                                    echo '<td>'.$log->id_login.'</td>';
                                    echo '<td>'.$log->nome_user.'</td>';
                                    echo '<td>'.$log->cidade.' / '.$log->uf.'</td>';
                                    echo '<td>'.$log->ip.'</td>';
                                    echo '<td>'.$log->dispositivo.'</td>';
                                    echo '<td>'.date('d/m/Y H:i:s', strtotime($log->data_login)).'</td>';
                                    echo '<td>'.$log->acao.'</td>';
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
    
    <!-- END OF CODE -->
    <!-- Modais -->
    <?php
        require_once('modaisMenu.php');
    ?>  
    <script type="text/javascript">
        $(document).ready(function() {
            $('#listaLogs').DataTable({
        order:[],
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
            });
        } );
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
    <script src="<?php echo BASE;?>/sw.js"></script>
    </body>
</html>