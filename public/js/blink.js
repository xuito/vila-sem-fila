//PISCAR CONTAGEM REGRESSIVA QUANDO ATINGE TEMPO MENOR QUE 1 MINUTO

var targets = document.querySelectorAll('.countdown');

targets.forEach(function(target) {

    var observer = new MutationObserver(function(mutations) {

        if(($(target).text().split(":")[1]) < 1)
        {
            $(target).fadeOut('slow');
            $(target).fadeIn('slow');
        }
    });

    var config = {
            attributes: true,
            childList: true,
            characterData: true
        };

    observer.observe(target, config);

});

function blink(selector)
{
$(selector).fadeOut('slow', function()
    {
        $(this).fadeIn('slow', function()
        {
            blink(this);
        });
    });
}

blink('.blink');
