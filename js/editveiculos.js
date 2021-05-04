$("#inputCor").change(function(){
    if($(this).val() != ''){
        document.getElementById('Cor').style.display = "inline";
        document.getElementById('Cor').value = $(this).val();
    }
    else{
        document.getElementById('Cor').style.display = "none";
    }
});
