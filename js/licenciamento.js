$(document).ready(function(){
    setTimeout(function() { 
        $('#avisoGet').hide();
    }, 7000);
    $('#saveEdtLic').hide();
    $('#dados-pgto').hide();
    $('#formaPgtoParc').hide();
    $('table').DataTable( {
        order:[[2, 'desc'],[3, 'asc']],
        dom: 'Bfrtip',
        language: {
            "decimal":        "",
            "emptyTable":     "Não há licenciamentos cadastrados!",
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
$('input[class*=valoreslic]').on('change', function(){
    var ipva;
    var dpvat;
    var lic;
    if($('#inputEditValorIPVA').val()==''){
        ipva = 0.00;
    } else{
        ipva = $('#inputEditValorIPVA').val()
    }
    if($('#inputEditValorDPVAT').val()==''){
        dpvat = 0.00;
    } else{
        dpvat = $('#inputEditValorDPVAT').val()
    }
    if($('#inputEditValorLic').val()==''){
        lic = 0.00;
    } else{
        lic = $('#inputEditValorLic').val()
    }
    var soma = parseFloat(ipva)+parseFloat(dpvat)+parseFloat(lic);
    document.getElementById('inputEditValorTotal').value = soma;
    var linha;
    var vt = $('#inputEditValorTotal').val();
    for(parc=2;parc<=5;parc++){
        var valor = vt/parc;
        linha += '<option value="'+parc+'x R$'+valor.toFixed(2)+'">'+parc+'x de R$'+valor.toFixed(2)+'</option>';
    }
    $('#inputParcelamento').html(linha);
});
$('#modalEditaLic').on('hide.bs.modal', function(){
    $('#saveEdtLic').hide();
});
$('#inputFormaPgto').on('change', function(){
    if($(this).val()=='av'){
        $('#formaPgtoParc').fadeOut(250);
    } else{
        $('#formaPgtoParc').fadeIn(250);
    }
});
$('#switchPgto').change(function () {
    if($('#dados-pgto').is(":hidden")){
        var linha;
        var vt = $('#inputEditValorTotal').val();
        for(parc=2;parc<=5;parc++){
            var valor = vt/parc;
            linha += '<option value="'+parc+'x R$'+valor.toFixed(2)+'">'+parc+'x de R$'+valor.toFixed(2)+'</option>';
        }
        $('#inputParcelamento').html(linha);
        $('#saveEdtLic').fadeIn(250);
        $('#dados-pgto').fadeIn(250);
    } else{
        $('#dados-pgto').fadeOut(250);
        $('#saveEdtLic').fadeOut(250);
        $('#inputFormaPgto').selectedIndex = 0;
        $('#inputParcelamento').selectedIndex = 0;
    }
});
$('#cleanEdtLic').on('click', function(){
    $('#switchPgto').click();
});
// functions
function editLic(idM){
    $('#modalEditaLic').modal();
    $.ajax({
        method: "POST",
        url: 'checklicenciamento.php',
        data: {act: 'lics', idlic: idM},
        dataType: 'json',
        success: function(resposta){
            $('#dados-multa').fadeIn(250);
            var linha = '';
            var parc;
            var carro = resposta.data.detailCar;
            $('#veiculo-string').html(carro);
            $('#modalLabelLic').html('&nbsp;<i class="fas fa-exclamation-triangle"></i> Licenciamento '+resposta.data.anoLic+':');
            document.getElementById('inputIDLic').value = resposta.data.idLic;
            document.getElementById('inputEditValorIPVA').value = resposta.data.valorIPVA;
            document.getElementById('inputEditValorDPVAT').value = resposta.data.valorDPVAT;
            document.getElementById('inputEditValorLic').value = resposta.data.valorLic;
            document.getElementById('inputEditValorTotal').value = resposta.data.valorTotal;
            if(resposta.data.doc!=null){
                document.getElementById('inputDocSign').required = false;
            } else{
                document.getElementById('inputDocSign').required = true;
            }
            for(parc=2;parc<=5;parc++){
                var valor = resposta.data.valorTotal/parc;
                linha += '<option value="'+parc+'x R$'+valor.toFixed(2)+'">'+parc+'x de R$'+valor.toFixed(2)+'</option>';
            }
            if(valor>0)
                $('#inputParcelamento').html(linha);
        },
        error: function(resposta){
            alert(resposta.status);
            alert('Nossa base de dados está indisponível. Favor atualizar a página e/ou tentar novamente em breve.');
        }
    });
}
function viewDocPaid(idM){
    $('#modalVerDoc').modal();
    $.ajax({
        method: "POST",
        url: 'checklicenciamento.php',
        data: {act: 'verDoc', idlic: idM},
        dataType: 'json',
        success: function(resposta){
            $('#divDoc').html('<iframe width="100%" style="height: 65vh !important;" src="'+resposta.data.valor+'" frameborder="1" type="application/pdf"></iframe>')
        },
        error: function(resposta){
            $('#divDoc').html('<p><center><h4>Não foi possível carregar o documento. Tente novamente ou contacte o NTI Logos.</h4></center></p>');
        }
    });
}
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