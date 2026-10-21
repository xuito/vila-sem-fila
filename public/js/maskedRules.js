$(function() {
    $('.cpf').mask('999.999.999-99', {autoclear: false});
    $('.cnpj').mask('99.999.999/9999-99', {autoclear: false});
    $('.celular').mask('(99) 99999-9999', {autoclear: false});
    $('.cep').mask('99999-999', {autoclear: false});
    $('#frmCCNum').mask('9999 9999 9999 9999', {autoclear: true});
    $('#frmCCExp').mask('m9/99', {autoclear: true});
    $('#frmCCCVC').mask('999', {autoclear: true});
});

$('.brl').maskMoney({prefix:'', allowNegative: false, allowZero: true, thousands:'.', decimal:',', affixesStay: false});
