$(document).ready(function(){
    $('.pgtoParcelado').hide();
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
    $('div[id*=show]').hide();
    $('div[id*=but]').hide();
});
$('div[id*=ctrl_filtros]').on('click',function(){
    if($('div[id*=show]').is(':visible')){
        $('div[id*=show]').fadeOut(250);
        $('div[id*=but]').fadeOut(250);
    } else{
        $('div[id*=show]').fadeIn(250);
        $('div[id*=but]').fadeIn(250);
    }
});
$('#dados-motorista').hide();
$('#dados-multa').hide();
$('#salvarNovoColab').hide();
$('#pesquisaMotoristaMulta').on("click", function(){
    var dataOcorrencia = $('#inputDataOcorrencia').val();
    var horaOcorrencia = $('#inputHoraOcorrencia').val();
    var periodo =  dataOcorrencia+' '+horaOcorrencia;
    var veiculo = $('#inputVeiculo').val();
    $.ajax({
        method: "POST",
        url: 'checkviagem.php',
        data: {act: "check", data: periodo, carro:veiculo},
        dataType: 'json',
        success: function(resposta){
            if(resposta.data.result){
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
                    $('#inputFormaPgto').selectedIndex = 0;
                    $('#inputParcelamento').selectedIndex = 0;
                }
            });
            var linha = '';
            var parc;
            var carro = resposta.data.montadora+' '+resposta.data.modelo+' ('+resposta.data.placa+')';
            document.getElementById('inputIDMulta').value = resposta.data.idmulta;
            document.getElementById('inputEditInfrator').value = resposta.data.infrator;
            document.getElementById('inputEditLocal').value = resposta.data.localMulta;
            document.getElementById('inputEditVeiculo').value = carro;
            document.getElementById('inputEditValor').value = resposta.data.valor;
            for(parc=2;parc<=5;parc++){
                var valor = resposta.data.valor/parc;
                linha += '<option value="'+parc+'x R$'+valor.toFixed(2)+'">'+parc+'x de R$'+valor.toFixed(2)+'</option>';
            }
            $('#inputParcelamento').html(linha);
        },
        error: function(resposta){
            alert(resposta.status);
            alert('Nossa base de dados está indisponível. Favor atualizar a página e/ou tentar novamente em breve.');
        }
    });
}
function verDocSigned(idM){
    $('#modalVerDoc').modal();
    $.ajax({
        method: "POST",
        url: 'checkmulta.php',
        data: {act: 'verDoc', idmulta: idM},
        dataType: 'json',
        success: function(resposta){
            $('#divDoc').html('<iframe width="100%" style="height: 65vh !important;" src="'+resposta.data.valor+'" frameborder="1" type="application/pdf"></iframe>')
        },
        error: function(resposta){
            $('#divDoc').html('<p><center><h4>Não foi possível carregar o documento. Tente novamente ou contacte o NTI Logos.</h4></center></p>');
        }
    });
}
$('#inputFormaPgto').on("change", function(){
    if($(this).val()=='pc'){
        $('.pgtoParcelado').fadeIn(250);
    } else{
        $('.pgtoParcelado').fadeOut(250);
    }
});
$('input[name="aceitaMulta"]').on("change", function () {
    if ($('input[name="aceitaMulta"]:checked').val() === '0') {
        setTimeout(function() { 
            $('#inputFormaPgto').prop("selectedIndex", 0);
            $('#inputParcelamento').prop("selectedIndex", 0);
        }, 250);
    }
});
function converteBase64() {
    var filesSelected = document.getElementById("inputDocSign").files;
    if (filesSelected.length > 0) {
        var fileToLoad = filesSelected[0];
        var fileReader = new FileReader();
        fileReader.onload = function(fileLoadedEvent) {
            var srcData = fileLoadedEvent.target.result; // <--- data: base64
            var newImage = document.getElementById("docSign");
            newImage.value = srcData;
            console.log(srcData);
        }
        fileReader.readAsDataURL(fileToLoad);
    }
}