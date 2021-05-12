$(document).ready(function(){
    $('dados-multa').hide();
    setTimeout(function() { 
        $('#avisoGet').hide();
    }, 7000);
    $('#listaMultas').DataTable( {
        order:[],
        dom: 'Bfrtip',
        language: {
            "decimal":        "",
            "emptyTable":     "Não há multas cadastradas!",
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
});
$('#dados-motorista').hide();
$('#dados-multa').hide();
$('#salvarNovoColab').hide();
$('#pesquisaMotoristaMulta').on("click", function(){
    var dataOcorrencia = $('#inputDataOcorrencia').val();
    var horaOcorrencia = $('#inputHoraOcorrencia').val();
    var periodo =  dataOcorrencia+' '+horaOcorrencia;
    var veiculo = $('#inputVeiculo').val();
    console.log('periodo: '+periodo);
    $.ajax({
        method: "POST",
        url: 'checkviagem.php',
        data: {act: "check", data: periodo, carro:veiculo},
        dataType: 'json',
        success: function(resposta){
            if(resposta.data.result){
                console.log(resposta.data);
                $('#dados-motorista').fadeIn(250);
                document.getElementById('inputNomeCondutor').value = resposta.data.nome;
                document.getElementById('inputIDCondutor').value = resposta.data.user_id;
                document.getElementById('inputIDVeiculo').value = resposta.data.carro_id;
                if(resposta.data.alter_rota == null){
                    document.getElementById('inputRotaEfetuada').value = resposta.data.rota;
                } else{
                    document.getElementById('inputRotaEfetuada').value = resposta.data.alter_rota;
                }
                document.getElementById('inputIDViagem').value = resposta.data.id_viagem;
                document.getElementById('salvarNovoColab').style.display = 'inline';
            } else{
                alert('Não há nenhuma corrida cadastrada no período indicado!');
                document.getElementById('salvarNovoColab').style.display = 'none';
                document.getElementById('dados-motorista').style.display = 'none';
            }
        },
        error: function(resposta){
            console.log(resposta.data);
            alert('Nossa base de dados está indisponível. Favor atualizar a página e/ou tentar novamente em breve.');
        }
    });
});
function hideData(){
    $('#dados-motorista').fadeOut(250);
    $('#salvarNovoColab').fadeOut(250);
}
function editMulta(idM){
    $('#modalEditaMulta').modal();
    $.ajax({
        method: "POST",
        url: 'checkmulta.php',
        data: {act: 'multa', idmulta: idM},
        dataType: 'json',
        success: function(resposta){
            $('#dados-multa').fadeIn(250);
            $('input[name="aceitaMulta"]').change(function () {
                if ($('input[name="aceitaMulta"]:checked').val() === '1') {
                    $('#dados-multa').fadeIn(260);
                }
                if ($('input[name="aceitaMulta"]:checked').val() === '0') {
                    $('#dados-multa').fadeOut(250);
                }
            });
            console.log(resposta.data);
            var carro = resposta.data.montadora+' '+resposta.data.modelo+' ('+resposta.data.placa+')';
            $('#labelInfrator').html('Ato de Infração: '+resposta.data.infrator);
            document.getElementById('inputIDMulta').value = resposta.data.idmulta;
            document.getElementById('inputEditInfrator').value = resposta.data.infrator;
            document.getElementById('inputEditLocal').value = resposta.data.localMulta;
            document.getElementById('inputEditVeiculo').value = carro;
            document.getElementById('inputEditValor').value = resposta.data.valor;
            
        },
        error: function(resposta){
            alert(resposta.status);
            alert('Nossa base de dados está indisponível. Favor atualizar a página e/ou tentar novamente em breve.');
        }
    });
}