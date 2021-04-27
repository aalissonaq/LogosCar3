$(document).ready(function(){    
    $('#salvarNovoColab').hide();
});
$('#inputMatricula').on('blur', function(){
    $.ajax({
        method: "POST",
        url: 'consultaMatr.php',
        data: {act: "matr", num: $("#inputMatricula").val()},
        dataType: 'json',
        success: function(resposta){
            if(resposta.data.matr == $('#inputMatricula').val()){
                alert('Essa matrícula já foi cadastrada. Por favor verifique e, persistindo, entre em contato com o NTI.');
                $('#salvarNovoColab').fadeOut(250);
                document.getElementById('inputMatricula').value='';
                $('#inputNomeCompleto').focus();
            } else{
                $('#salvarNovoColab').fadeIn(250);
            }
        },
        error: function(resposta){
            console.log(resposta.data);
            alert('Nossa base de dados está indisponível. Favor atualizar a página e/ou tentar novamente em breve.');
        }
    });
});
$('#inputNumCNH').on('blur', function(){
    if($(this).val()==''){
        alert('O número da CNH é um campo obrigatório. Por favor preencha-o para prosseguir.');
        $('#motoraSim').focus();
    } else{
        $.ajax({
            method: "POST",
            url: 'consultaMatr.php',
            data: {act: "cnh", num: $("#inputNumCNH").val()},
            dataType: 'json',
            success: function(resposta){
                if(resposta.data.cnh == $('#inputNumCNH').val()){
                    alert('Essa CNH já foi cadastrada. Por favor verifique e, persistindo, entre em contato com o NTI.');
                    $('#salvarNovoColab').fadeOut(250);
                    document.getElementById('inputNumCNH').value='';
                    $('#motoraSim').focus();
                } else{
                    $('#salvarNovoColab').fadeIn(250);
                }
            },
            error: function(resposta){
                console.log(resposta.data);
                alert('Nossa base de dados está indisponível. Favor atualizar a página e/ou tentar novamente em breve.');
            }
        });
    }
});