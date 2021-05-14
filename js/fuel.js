$(document).ready(function() {
    
    $('table').DataTable( {
        order:[],
        dom: 'Bfrtip',
        language: {
            "decimal":        "",
            "Copy": "Copiar",
            "emptyTable":     "Sem dados suficientes para traçar demonstrativo",
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
            }
        },
        buttons: [
            'copy', 'excel', 'pdf', 'print'
        ]
    } );
    $('div[id*=show]').hide();
    $('div[id*=but]').hide();
} );
$('div[id*=ctrl_filtros]').on('click',function(){
    if($('div[id*=show]').is(':visible')){
        $('div[id*=show]').fadeOut(250);
        $('div[id*=but]').fadeOut(250);
    } else{
        $('div[id*=show]').fadeIn(250);
        $('div[id*=but]').fadeIn(250);
    }
});
$('form').on("submit", function(){
    $('#submitAddAbast').innerHTML = '<div class="spinner-border" role="status"><span class="sr-only">Carregando...</span></div>';
    $(this).submit();
});
// funções
function editPassword(idUSer){
    $('#modalAddAbastecimento').modal();
    $.ajax({
        method: "POST",
        url: 'consultaMatr.php',
        data: {act: "id_psw", id: idUSer},
        dataType: 'json',
        success: function(resposta){
            if(resposta.data.nome != null && resposta.data.matr !=null){
                $('#modalPasswordLabel').html('<i class="fas fa-key"></i>&nbsp Alterar: '+resposta.data.nome+' (mat.: '+resposta.data.matr+')');
                document.getElementById('inputID').value = idUSer;
            }
            else{
                alert('Nossa base de dados está indisponível. Favor atualizar a página e/ou tentar novamente em breve.');
                setTimeout(function(){
                    window.location.href='usuarios.php';
                }, 5000);
            }
        },
        error: function(resposta){
            console.log(resposta.data);
            alert('Nossa base de dados está indisponível. Favor atualizar a página e/ou tentar novamente em breve.');
        }
    });
}
$('#formAddAbast').on("submit", function(){
    document.getElementById('submitAddAbast').disabled = true;
    document.getElementById('submitAddAbast').innerHTML = '<div class="spinner-border" role="status"><span class="sr-only">Carregando...</span></div>';
    $(this).submit();
});
$('#formEdtAbast').on("submit", function(){
    document.getElementById('submitEditAbast').disabled = true;
    document.getElementById('submitEditAbast').innerHTML = '<div class="spinner-border" role="status"><span class="sr-only">Carregando...</span></div>';
    $(this).submit();
});
function addDocBase64() {
    var filesSelected = document.getElementById("inputAddDocComprovante").files;
    if (filesSelected.length > 0) {
        var fileToLoad = filesSelected[0];
        var fileReader = new FileReader();
        fileReader.onload = function(fileLoadedEvent) {
            var srcData = fileLoadedEvent.target.result; // <--- data: base64
            var newImage = document.getElementById("inputAddComprovante");
            newImage.value = srcData;
        }
        fileReader.readAsDataURL(fileToLoad);
    }
}
function editDocBase64() {
    var filesSelected = document.getElementById("inputEdtDocComprovante").files;
    if (filesSelected.length > 0) {
        var fileToLoad = filesSelected[0];
        var fileReader = new FileReader();
        fileReader.onload = function(fileLoadedEvent) {
            var srcData = fileLoadedEvent.target.result; // <--- data: base64
            var newImage = document.getElementById("inputEdtComprovante");
            newImage.value = srcData;
        }
        fileReader.readAsDataURL(fileToLoad);
    }
}
function editAbast(idUSer){
    $('#modalEditAbastecimento').modal();
    $.ajax({
        method: "POST",
        url: 'checkabastecimento.php',
        data: {act: "check", id: idUSer},
        dataType: 'json',
        success: function(resposta){
            if(resposta.data.nome != null && resposta.data.matr !=null){
                $('#modalPasswordLabel').html('<i class="fas fa-key"></i>&nbsp Alterar: '+resposta.data.nome+' (mat.: '+resposta.data.matr+')');
                document.getElementById('inputID').value = idUSer;
            }
        },
        error: function(resposta){
            console.log(resposta.data);
            alert('Nossa base de dados está indisponível. Favor atualizar a página e/ou tentar novamente em breve.');
        }
    });
}