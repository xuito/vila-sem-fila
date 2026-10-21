$(function ()
{
    $('.ajax').submit(function(event) //Busca qualquer formulario com a classe .ajax
    {
        event.preventDefault();

        $('#loading_container').css('display', 'table');

        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize(),
            dataType: "json",
            success: function(response)
            {
                if(response.success === true)
                {
                    //redirect
                    if(!response.redirect)
                    {
                        response.redirect = '';
                    }
                    
                    window.location.href = response.redirect;
                    //window.location.href = response.redirect+'?message='+response.message;

                } else
                {
                    $('#loading_container').css('display', 'none');
                    $('.message_box').removeClass('d-none').html(response.message);
                }						
            }
        });
    });
});
