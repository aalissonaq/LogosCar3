$(document).ready(function(){
    setTimeout(function() { 
        $('#avisoGet').hide();
    }, 5000);
    setTimeout(function() { 
        $('.alert-logoscar').hide();
    }, 5000);
  var width = 0;
  var progressbar = document.getElementById('progress-bar');
  if(progressbar!=null){
    window.onload = function(e){
        setInterval(function () {
            width = width >= 100 ? 0 : width+0.1;
            document.getElementById('progress-bar').style.width = width + '%'; }, 180);
        }
  }
});
function alteraRota(id){
    const checkbox = document.getElementById('radioAlteracao'+id);
    if(checkbox.checked === true){
        $('#alterado'+id).fadeIn(250);
    } else{
        $('#alterado'+id).fadeOut(250);
    }
}

//itens que devem estar OK para liberar botão de Finalizar Corrida
// primeiro item: km
// segundo item: hora
$('[id*=fimRota]').hide();
$('[id*=btnRetornoManut]').hide();
var itensrota = [0,0];
var itensmanut = [0,0];
//função de controle de retorno de viagens
function testarKmRetorno(id){
    var retorno = $('#inputKMretornoEmRota'+id).val();
    var ida = $('#inputKMatualEmRota'+id).val();
    var sub = retorno - ida;
    if(sub > 100){
        alert('A diferença entre os KMs de saída e de volta está alta. Por favor verifique se está correto.');
        $('#fimRota'+id).fadeIn(250);
    }
    if(sub == 0){
        alert('Opa! O KM de retorno está igual ao KM de saída...');
        document.getElementById('inputKMretornoEmRota'+id).value = '';
        $('#fimRota'+id).fadeOut(250);
        itensrota[0]=0;
        document.getElementById('inputKMatualEmRota'+id).focus();
    }
    if(sub < 0){
        alert('Opa! O KM de retorno não pode ser menor que o KM de saída...');
        document.getElementById('inputKMretornoEmRota'+id).value = '';
        $('#fimRota'+id).fadeOut(250);
        itensrota[0]=0;
        document.getElementById('inputKMatualEmRota'+id).focus();
    } else{
        itensrota[0]=1;
        if(itensrota[0]==1 && itensrota[1]==1)
            $('#fimRota'+id).fadeIn(250);
    }
    console.log(itensrota);
}
function testarMomentoRetorno(id){
    var retorno = $('#inputHoraRetornoEmRota'+id).val();
    var ida = $('#inputHoraSaidaEmRota'+id).val();
    if(retorno == ida){
        alert('Opa! Os momentos de saída e de retorno estão iguais!');
        document.getElementById('inputHoraRetornoEmRota'+id).value = '';
        $('#fimRota'+id).fadeOut(250);
        itensrota[1]=0;
        document.getElementById('inputKMatualEmRota'+id).focus();
    }
    if(retorno < ida){
        alert('Opa! O momento do retorno não pode ser menor que o de saída...');
        document.getElementById('inputHoraRetornoEmRota'+id).value = '';
        $('#fimRota'+id).fadeOut(250);
        itensrota[1]=0;
        document.getElementById('inputKMatualEmRota'+id).focus();
    } else{
        itensrota[1]=1;
        if(itensrota[0]==1 && itensrota[1]==1)
            $('#fimRota'+id).fadeIn(250);
    }
    console.log(itensrota);
}
// função de controle de retorno de manutenção
function testarKmManut(id){
    var retorno = $('#inputKMRetornoManut'+id).val();
    var ida = $('#inputKMIdaManut'+id).val();
    var sub = retorno - ida;
    if(sub > 100){
        alert('A diferença entre os KMs de saída e de volta está alta. Por favor verifique se está correto.');
        $('#btnRetornoManut'+id).fadeIn(250);
    }
    if(sub == 0){
        alert('Opa! O KM de retorno está igual ao KM de saída...');
        document.getElementById('inputKMRetornoManut'+id).value = '';
        $('#btnRetornoManut'+id).fadeOut(250);
        itensmanut[0]=0;
        document.getElementById('inputKMIdaManut'+id).focus();
    }
    if(sub < 0){
        alert('Opa! O KM de retorno não pode ser menor que o KM de saída...');
        document.getElementById('inputKMRetornoManut'+id).value = '';
        $('#btnRetornoManut'+id).fadeOut(250);
        itensmanut[0]=0;
        document.getElementById('inputKMIdaManut'+id).focus();
    } else{
        itensmanut[0]=1;
        if(itensmanut[0]==1 && itensmanut[1]==1)
            $('#btnRetornoManut'+id).fadeIn(250);
    }
    console.log(itensmanut);
}
function testarMomentoManut(id){
    var retorno = $('#inputDataRetornoManut'+id).val();
    var ida = $('#inputDataIdaManut'+id).val();
    if(retorno == ida){
        alert('Opa! Os momentos de saída e de retorno estão iguais!');
        document.getElementById('inputDataRetornoManut'+id).value = '';
        $('#btnRetornoManut'+id).fadeOut(250);
        itensmanut[1]=0;
        document.getElementById('inputDataIdaManut'+id).focus();
    }
    if(retorno < ida){
        alert('Opa! O momento do retorno não pode ser menor que o de saída...');
        document.getElementById('inputDataRetornoManut'+id).value = '';
        $('#btnRetornoManut'+id).fadeOut(250);
        itensmanut[1]=0;
        document.getElementById('inputDataIdaManut'+id).focus();
    } else{
        itensmanut[1]=1;
        if(itensmanut[0]==1 && itensmanut[1]==1)
            $('#btnRetornoManut'+id).fadeIn(250);
    }
    console.log(itensmanut);
}

$('[id*=inputTipoManutencao]').on("change", function(){
    if ($(this).val() == '1'){
        $('[id*=lblinputKMProg]').fadeIn(250);
        $('[id*=inputKMProg]').fadeIn(250);
    } else{
        $('[id*=lblinputKMProg]').fadeOut(250);
        $('[id*=inputKMProg]').fadeOut(250);
    }
});
$("[id*=modalManut]").on("hidden.bs.modal", function () {
    $('[id*=lblinputKMProg]').show();
    $('[id*=inputKMProg]').show();
    $('[id*=irManut]').each (function(){
        this.reset();
      });
});