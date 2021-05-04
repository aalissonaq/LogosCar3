function converteFoto() {
    var filesSelected = document.getElementById("inicioPic").files;
    if (filesSelected.length > 0) {
        var fileToLoad = filesSelected[0];
        var fileReader = new FileReader();
        fileReader.onload = function(fileLoadedEvent) {
            var srcData = fileLoadedEvent.target.result; // <--- data: base64
            var newImage = document.getElementById("picinicio64");
            newImage.value = srcData;
        }
        fileReader.readAsDataURL(fileToLoad);
    }
}
$(document).ready(function(){
    setTimeout(function() { 
        $('#avisoGet').hide();
    }, 5000);
    $('#listaVeiculos').DataTable( {
        dom: 'Bfrtip',
        language: {
            "decimal":        "",
            "emptyTable":     "Não há veículos cadastrados!",
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
    var montadoras = $("inputMontadora").val();
    // Carregando a lista de veículos de arquivo JSON
    $.getJSON("data/carros.json", function(data){
        console.log("data access OK!");
        var linha = '';
        var coluna = '';
        for(var i=0; i<data.montadoras.length; i++){
            linha += '<option value="' + data.montadoras[i].nome + '">' + data.montadoras[i].nome + '</option>';
        }
        for(var j=0; j<data.montadoras[0].modelos.length;j++){
            coluna += '<option value="' + data.montadoras[0].modelos[j] + '">' + data.montadoras[0].modelos[j] + '</option>';
        }
        $('#inputMontadora').html(linha);
        $('#inputModelo').html(coluna);
        console.log('Está selecionado o ' + $("#inputModelo").val() + ' da montadora ' +$('#inputMontadora').val());
    })
    .fail(function(){
        console.log("Ops! Algo de errado ocorreu na busca das informações...");
    });
    //  Carregando a lista de cidades de arquivo JSON
    $.getJSON("data/brasil.json", function(data){
        var linha = '';
        var coluna = '';
        for(var i=0; i<data.estados.length; i++){
            linha += '<option value="' + data.estados[i].sigla + '">' + data.estados[i].sigla + '</option>';
        }
        for(var j=0; j<data.estados[0].cidades.length;j++){
            coluna += '<option value="' + data.estados[0].cidades[j] + '">' + data.estados[0].cidades[j] + '</option>';
        }
        $('#inputUF').html(linha);
        $('#inputCidade').html(coluna);
        console.log('Está selecionada a cidade de '+ $('#inputCidade').val() +'/'+$('#inputUF').val());
    })
    .fail(function(){
        console.log("Ops! Algo de errado ocorreu na busca das informações...");
    });
});
function upperCaser(texto){
    var txt = texto.value
    var idtag = texto.id
    var res = txt.toUpperCase()
    document.getElementById(idtag).value = res;
}
$('#inputMontadora').change(function(){
    $.getJSON("data/carros.json", function(data){
        var mont = $('#inputMontadora').val();
        var a=0;
        var linha = '';
        while(mont!=data.montadoras[a].nome){
            a++;
        }
        for(var i=0; i<data.montadoras[a].modelos.length; i++){
            linha += '<option value="' + data.montadoras[a].modelos[i] + '">' + data.montadoras[a].modelos[i] + '</option>';
        }
        $('#inputModelo').html(linha);
        console.log('Você selecionou a montadora ' +$('#inputMontadora').val());
    })
        .fail(function(){
            console.log("Ops! Algo de errado ocorreu na busca das informações de modelos da marca...");
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
$("#inputModelo").change(function(){
    console.log('Você selecionou o ' + $("#inputModelo").val() + ' da montadora ' +$('#inputMontadora').val());
});
$("#inputCidade").change(function(){
    console.log('Você selecionou a cidade de ' +$('#inputCidade').val() +'/'+$('#inputUF').val());
});
$("#inputCor").change(function(){
    if($(this).val() != ''){
        document.getElementById('Cor').style.display = "inline";
        document.getElementById('Cor').value = $(this).val();
    }
    else{
        document.getElementById('Cor').style.display = "none";
    }
});
$('#inputPlaca').on('blur', function(){
    $.ajax({
        method: "POST",
        url: 'consultaMatr.php',
        data: {act: "placa", placa: $("#inputPlaca").val()},
        dataType: 'json',
        success: function(resposta){
            console.log(resposta.data);
            if(resposta.data == true){
                alert('Essa placa já foi cadastrada. Por favor verifique e, persistindo, entre em contato com o NTI.');
                document.getElementById('inputPlaca').value='';
            }
        },
        error: function(resposta){
            console.log(resposta.data);
            alert('Nossa base de dados está indisponível. Favor atualizar a página e/ou tentar novamente em breve.');
            $('#inputMontadora').focus();
        }
    });
});
$('#inputRENAVAM').on('blur', function(){
    $.ajax({
        method: "POST",
        url: 'consultaMatr.php',
        data: {act: "renavam", renavam: $("#inputRENAVAM").val()},
        dataType: 'json',
        success: function(resposta){
            console.log(resposta.data);
            if(resposta.data == true){
                alert('Esse RENAVAM já foi cadastrado. Por favor verifique e, persistindo, entre em contato com o NTI.');
                document.getElementById('inputRENAVAM').value='';
            }
        },
        error: function(resposta){
            console.log(resposta.data);
            alert('Nossa base de dados está indisponível. Favor atualizar a página e/ou tentar novamente em breve.');
            $('#inputMontadora').focus();
        }
    });
});
$('#inputChassi').on('blur', function(){
    $.ajax({
        method: "POST",
        url: 'consultaMatr.php',
        data: {act: "chassi", chassi: $("#inputChassi").val()},
        dataType: 'json',
        success: function(resposta){
            console.log(resposta.data);
            if(resposta.data == true){
                alert('Esse chassi já foi cadastrado. Por favor verifique e, persistindo, entre em contato com o NTI.');
                document.getElementById('inputChassi').value='';
            }
        },
        error: function(resposta){
            console.log(resposta.data);
            alert('Nossa base de dados está indisponível. Favor atualizar a página e/ou tentar novamente em breve.');
            $('#inputMontadora').focus();
        }
    });
});
$('#inputAlias').on('blur', function(){
    $.ajax({
        method: "POST",
        url: 'consultaMatr.php',
        data: {act: "alias", alias: $("#inputAlias").val()},
        dataType: 'json',
        success: function(resposta){
            console.log(resposta.data);
            if(resposta.data == true){
                alert('Esse Alias já foi cadastrado. Por favor verifique e, persistindo, entre em contato com o NTI.');
                document.getElementById('inputAlias').value='';
            }
        },
        error: function(resposta){
            console.log(resposta.data);
            alert('Nossa base de dados está indisponível. Favor atualizar a página e/ou tentar novamente em breve.');
            $('#inputMontadora').focus();
        }
    });
});
