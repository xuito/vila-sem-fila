$(function ()
{
    $('.editable_field').blur(function() //Busca qualquer formulario com a classe
    {
        $('#loading_container').css('display', 'table');

        $.ajax({
            url: $(this).closest('form').attr('action'),
            type: $(this).closest('form').attr('method'),
            data: $(this).closest('form').serialize(),
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
