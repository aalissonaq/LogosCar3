$(document).ready(function(){
    $('#listaVeiculos').DataTable();
    var montadoras = $("selectCar").val();
    $.getJSON("../data/carros.json", function(data){
        console.log("data access OK!");
        var linha = '';
        var coluna = '';
        for(var i=0; i<data.montadoras.length; i++){
            linha += '<option value="' + data.montadoras[i].nome + '">' + data.montadoras[i].nome + '</option>';
        }
        for(var j=0; j<data.montadoras[0].modelos.length;j++){
            coluna += '<option value="' + data.montadoras[0].modelos[j] + '">' + data.montadoras[0].modelos[j] + '</option>';
        }
        $('#selectCar').html(linha);
        $('#selectModel').html(coluna);
        console.log('Está selecionado o ' + $("#selectModel").val() + ' da montadora ' +$('#selectCar').val());
    })
        .fail(function(){
            console.log("Ops! Algo de errado ocorreu na busca das informações...");
        });
});
$('#selectCar').change(function(){
    $.getJSON("../data/carros.json", function(data){
        var mont = $('#selectCar').val();
        var a=0;
        var linha = '';
        while(mont!=data.montadoras[a].nome){
            a++;
        }
        for(var i=0; i<data.montadoras[a].modelos.length; i++){
            linha += '<option value="' + data.montadoras[a].modelos[i] + '">' + data.montadoras[a].modelos[i] + '</option>';
        }
        $('#selectModel').html(linha);
        console.log('Você selecionou a montadora ' +$('#selectCar').val());
    })
        .fail(function(){
            console.log("Ops! Algo de errado ocorreu na busca das informações de modelos da marca...");
        });
});
$("#selectModel").change(function(){
    console.log('Você selecionou o ' + $("#selectModel").val() + ' da montadora ' +$('#selectCar').val());
});
$('#placa').keyup(function(){
    var placa = $('#placa').val();
    if(placa.length<=8){
        placa = placa.toUpperCase();
        document.getElementById('placa').value = placa;
    }
});
$('#submitAddVeiculo').click(function(){
    $('#submitAddVeiculo').setAttribute('disabled');
    $('#submitAddVeiculo').val()='<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Carregando... ';
    $('#formAddVeiculo').submit();
});
$('#submitAlocMotorista').click(function(){
    $('#submitAlocMotorista').setAttribute('disabled');
    $('#submitAlocMotorista').val()='<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Carregando... ';
    $('#formAlocMotorista').submit();
});