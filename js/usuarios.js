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
function editUser(idUSer){
    window.location.href = 'editarUsuario.php?id='+idUSer;
}
function editPassword(idUSer){
    $('#modalPassword').modal();
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
$('#submitPasswd').on("click", function(){
    if($('#novaSenha').val()=='' || $('#confirmaSenha').val()==''){
        alert('Por favor digite uma senha...');
        document.getElementById('novaSenha').value = '';
        document.getElementById('confirmaSenha').value = '';
    } else{
        if($('#novaSenha').val()==$('#confirmaSenha').val()){
            document.getElementById("modalEdtPasswd").submit();
        } else{
            document.getElementById('novaSenha').value = '';
            document.getElementById('confirmaSenha').value = '';
            alert('As senhas não combinam!');
        }
    }
});
function testeComplexidadeSenha(senha){
    const upperc = new RegExp('[A-Z]');
    const lowerc = new RegExp('[a-z]');
    const number = RegExp('[0-9]');
    const spchar = RegExp(/([^a-zA-Z0-9])+/g);
    if(upperc.test(senha) && lowerc.test(senha) && number.test(senha) && spchar.test(senha)){
        console.log('senha ok!')
        return true;
    } else{
        console.log('senha fora dos padrões!')
        return false;
    }
}
$('#novaSenha').on("blur", function(){
    if(testeComplexidadeSenha($(this).val())==false){
        document.getElementById('novaSenha').value = '';
        alert('A sua senha precisa ter pelo menos:\n 1(uma) letra maiúscula\n 1(uma) letra minúscula\n 1(um) numeral; e \n 1(um) caractere especial.\n Por favor tente novamente.');
        $('#cleanPasswd').focus();
    }
});
