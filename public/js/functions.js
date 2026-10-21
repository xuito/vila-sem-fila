function inputFocus(fieldId)
{
    document.getElementById(fieldId).focus();
}

$('.upper_case').keyup(function()
{
    this.value = this.value.toUpperCase();
});

$('.lower_case').keyup(function()
{
    this.value = this.value.toLowerCase();
});

function parseMoney(number)
{
    var parts = number.toString().split('.');
    return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.') + (parts[1] ? ',' + parts[1] : '');
}

function numberFormat(number)
{
    number = number.replace('.','');
    number = number.replace(',','.');
    return parseFloat(number);
}
