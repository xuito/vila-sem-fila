@extends('site.layouts.default')

@section('view_title', 'Vila Sem Fila')

@section('description', '')
@section('keywords', '')

@section('css')
@endsection

@section('scripts')
    <script src="{{ asset('js/jquery.maskedinput.js') }}"></script>
    <script src="{{ asset('js/jquery.maskMoney.min.js') }}"></script>
    <script src="https://assets.pagseguro.com.br/checkout-sdk-js/rc/dist/browser/pagseguro.min.js"></script>

    <script>
        function checkStatus(paymentHash)
        {
            var statusURL = 'https://vilasemfila.com.br/api/payment/check-status/'+paymentHash;

            //console.log(statusURL);

            $.ajax(
            {
                url: statusURL,
                method: 'GET',
                success: function(response) {
                    //console.log(response);
                    if(response === 'Pago')
                    {
                        var url = "{{ route('site.payment.response', ['status' => 'Pago', 'paymentHash' => '"+paymentHash+"', 'message' => 'Conta encerrada com sucesso!']) }}";
                        //var url = "https://vilasemfila.com.br/franca/retorno/Pago/"+paymentHash+"/Conta encerrada com sucesso!";

                        window.location.href = url;
                    }
                    else
                    {
                        setTimeout(checkStatus, 10000, paymentHash);
                    }
                },
                error: function()
                {
                    setTimeout(checkStatus, 10000, paymentHash);
                }
            });
        }

        $(function()
        {
            $('#credit_card_form').on('submit', function(event)
            {
                event.preventDefault();

                $('#loading_container').css('display', 'table');

                var ticket = $('#ticket').val(); // NUMERO DA PULSEIRA DO CLIENTE
                var paymentId = $('#payment_id').val(); // NUMERO DO PEDIDO
                var holder = $('#frmNameCC').val(); // PORTADOR
                var clientName = $('#client_name').val(); // NOME DO CLIENTE
                var clientCPF = $('#client_cpf').val(); // CPF
                var email = $('#email').val(); // CPF
                var cpf = $('#holder_cpf').val(); // CPF

                var expiryDate = $('#frmCCExp').val(); //DATA DE EXPIRAÇÃO
                expiryDate = expiryDate.split('/');
                var expMonth = expiryDate[0]; // MÊS DE EXPIRAÇÃO
                var expYear = '20' + expiryDate[1]; // ANO DE EXPIRAÇÃO

                var number = $('#frmCCNum').val(); // NÚMERO DO CARTÃO
                number = number.replace(/\s+/g, '').replace(/_/g, '');

                var securityCode = $('#frmCCCVC').val(); // CÓDIGO DE SEGURANÇA

                var card = PagSeguro.encryptCard({
                    publicKey: "{{ config('constants.GATEWAY_PUBLIC_KEY') }}",
                    holder: holder,
                    number: number,
                    expMonth: expMonth,
                    expYear: expYear,
                    securityCode: securityCode
                });

                if(card.hasErrors)
                {
                    var errors = card.errors;
                    var message = '';

                    for(i = 0; i < errors.length; i++)
                    {
                        switch(errors[i].code)
                        {
                            case 'INVALID_NUMBER':
                                message += 'Número de cartão inválido.<br>';
                                break;

                            case 'INVALID_SECURITY_CODE':
                                message += 'Código de segurança inválido.<br>';
                                break;

                            case 'INVALID_EXPIRATION_MONTH':
                                message += 'Mês de expiração inválido.<br>';
                                break;

                            case 'INVALID_EXPIRATION_YEAR':
                                message += 'Ano de expiração inválido.<br>';
                                break;

                            case 'INVALID_PUBLIC_KEY':
                                message += 'Chave pública inválida.<br>';
                                break;

                            case 'INVALID_HOLDER':
                                message += 'Titular inválido.<br>';
                                break;
                        }
                    }

                    $('#loading_container').css('display', 'none');
                    $('.error_box').removeClass('d-none').html(message);

                    setTimeout(function()
                    {
                        $(".error_box:not([class*='d-none'])").addClass('d-none');
                    }, 5000);

                }
                else if(card.encryptedCard != null)
                {
                    var encrypted = card.encryptedCard; //CARTÃO CRIPTOGRAFADO

                    $.post($('#credit_card_form').attr('action'),
                    {
                        _token: "{{ csrf_token() }}",
                        ticket: ticket,
                        paymentId: paymentId,
                        encrypted: encrypted,
                        holder: holder,
                        clientName: clientName,
                        clientCPF: clientCPF,
                        cpf: cpf,
                        email: email,
                        securityCode: securityCode
                    },
                    function(result)
                    {
                        //$('#loading_container').css('display', 'none');
                        //console.log(result);

                        var url = "{{ route('site.payment.response', ['slug'  => session('storeSlug'), 'status' => ':status', 'paymentHash' => ':paymentHash', 'message' => ':message']) }}";
                        url = url.replace(':status', result.status);
                        url = url.replace(':paymentHash', result.paymentHash);
                        url = url.replace(':message', result.message);

                        window.location.href = url;
                    }, 'json');
                }
            });

            $('#pix_form').on('submit', function(event)
            {
                event.preventDefault();

                $('#loading_container').css('display', 'table');
                var ticket = $('#pix_ticket').val(); // NUMERO DA PULSEIRA DO CLIENTE
                var paymentId = $('#pix_payment_id').val(); // NUMERO DO PEDIDO
                var clientName = $('#pix_client_name').val(); // NOME DO CLIENTE
                var cpf = $('#pix_client_cpf').val(); // CPF

                $.post($('#pix_form').attr('action'),
                {
                    _token: "{{ csrf_token() }}",
                    ticket: ticket,
                    paymentId: paymentId,
                    clientName: clientName,
                    cpf: cpf
                }, function(result)
                {
                    //console.log(result);
                    pixData = result.pixData;
                    paymentHash = result.paymentHash;

                    //EXIBE A IMAGEM E CODIGO PIX
                    $('#loading_container').css('display', 'none');
                    $("#pix").html(pixData);

                    //APÓS 30 SEGUNDOS, CHECA SE O PIX FOI PAGO
                    setTimeout(checkStatus, 30000, paymentHash);
                });
            });
        });
    </script>
@endsection

@section('content')
    <div id="loading_container">
        <div id="loading">
            <img src="{{ asset('img/loading.svg') }}" alt="Carregando...">
        </div>
    </div>
    <div class="container pt-4 pb-5">
        <div class="row justify-content-center mb-4">
            <div class="col col-sm-10 col-md-8 col-lg-6">
                <div class="card border-0">
                    <!--
                    <h4 class="card-header bg-dark text-light text-center">Escolha entre as formas de pagamento disponíveis e conclua a operação!</h4>
                    -->
                    <h4 class="card-header bg-dark text-light text-center">Preencha os dados para pagamento e conclua a operação!</h4>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active text-dark fw-bold" id="credit-card-tab" data-bs-toggle="tab" data-bs-target="#credit-card-body" type="button" role="tab" aria-controls="credit-card-body" aria-selected="true">Cartão de Crédito</button>
                            </li>
                            <!--
                            <li class="nav-item" role="presentation">
                                <button class="nav-link text-dark fw-bold" id="pix-tab" data-bs-toggle="tab" data-bs-target="#pix-body" type="button" role="tab" aria-controls="pix-body" aria-selected="false">Pagar com PIX</button>
                            </li>
                            -->
                        </ul>
                        <div class="tab-content w-100 m-auto d-flex justify-content-center py-4" id="myTabContent">
                            <div class="tab-pane fade show active w-100" id="credit-card-body" role="tabpanel" aria-labelledby="credit-card-tab">
                                <div class="alert alert-danger error_box d-none" role="alert">
                                </div>
                                <form name="credit_card_form" id="credit_card_form" class="mb-3" action="{{ route('site.payment.store') }}" method="post">
                                    @csrf
                                    <div class="form-floating my-2">
                                        <input type="text" name="cardnumber" class="form-control @error('cardnumber') is-invalid @enderror" id="frmCCNum" autocomplete="cc-number" required autofocus>
                                        <label for="frmCCNum">Número do cartão</label>
                                        @error('cardnumber')
                                            <div class="invalid-feedback text-end">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-floating my-2">
                                        <div class="form-floating">
                                            <input type="text" name="ccname" class="form-control upper_case @error('ccname') is-invalid @enderror" id="frmNameCC" autocomplete="cc-name" required>
                                            <label for="frmNameCC">Nome (EXATAMENTE igual ao do cartão)</label>
                                            @error('ccname')
                                                <div class="invalid-feedback text-end">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-floating">
                                                <input type="text" name="cc-exp" class="form-control" id="frmCCExp" autocomplete="cc-exp" required>
                                                <label for="frmCCExp">Validade (MM/YY)</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-floating mt-2 mt-sm-0">
                                                <input type="text" name="cvc" class="form-control" id="frmCCCVC" autocomplete="cc-csc" required>
                                                <label for="frmCCCVC">Cód. de segurança</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row my-2">
                                        <div class="col-sm-8">
                                            <div class="form-floating">
                                                <input type="email" name="email" class="form-control lower_case @error('email') is-invalid @enderror" id="email" autocomplete="email" required>
                                                <label for="email">E-mail</label>
                                                @error('email')
                                                    <div class="invalid-feedback text-end">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-floating mt-2 mt-sm-0">
                                                <input type="text" name="holder_cpf" class="form-control upper_case @error('holder_cpf') is-invalid @enderror cpf" id="holder_cpf" required autocomplete="off">
                                                <label for="holder_cpf">CPF do titular</label>
                                                @error('holder_cpf')
                                                    <div class="invalid-feedback text-end">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-floating my-2">
                                        <input type="text" name="amount" class="form-control" id="amount" value="R$ {{ number_format($amount, 2, ',', '.') }}" readonly>
                                        <label for="frmCCNum">Total cobrado</label>
                                    </div>

                                    <div class="d-flex justify-content-evenly align-items-center mt-3">
                                        <input type="hidden" name="ticket" id="ticket" value="{{ $ticket }}">
                                        <input type="hidden" name="payment_id" id="payment_id" value="{{ $paymentId }}">
                                        <input type="hidden" name="client_name" id="client_name" value="{{ $clientName }}">
                                        <input type="hidden" name="client_cpf" id="client_cpf" value="{{ $cpf }}">
                                        <button type="submit" class="btn btn-dark w-100">Efetuar pagamento</button>
                                    </div>
                                </form>
                            </div>
                            <!--
                            <div class="tab-pane fade w-100" id="pix-body" role="tabpanel" aria-labelledby="pix-tab">
                                <div id="pix">
                                    <div>
                                        &bull; Clique no botão abaixo para gerar o código para pagamento.<br>
                                        &bull; O código PIX tem validade de 5 minutos e neste período a sua comanda fica bloqueada para novos consumos.<br>
                                        &bull; Siga as instruções e caso não consiga completar o pagamento dentro deste prazo tente novamente após 5 minutos.<br>
                                        &bull; O Pix é seguro e rápido. Seu pedido é confirmado poucos minutos após o pagamento. Não é necessário atualizar esta página.
                                    </div>
                                    <form name="pix_form" id="pix_form" class="mb-3" action="{{ route('site.payment.pix') }}" method="post">
                                        <div class="d-flex justify-content-evenly align-items-center mt-3">
                                            <input type="hidden" name="ticket" id="pix_ticket" value="{{ $ticket }}">
                                            <input type="hidden" name="payment_id" id="pix_payment_id" value="{{ $paymentId }}">
                                            <input type="hidden" name="client_name" id="pix_client_name" value="{{ $clientName }}">
                                            <input type="hidden" name="client_cpf" id="pix_client_cpf" value="{{ $cpf }}">
                                            <button type="submit" class="btn btn-dark w-100">Gerar QR Code</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col col-sm-10 col-md-8 col-lg-6">
                <div class="card border-0">
                    <div class="card-body px-4 text-center fw-bold">
                        <i class="material-icons align-text-bottom">location_on</i> {{ mb_strtoupper(session()->get('storeCity')) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script>
        $(function() {
            $('#frmCCNum').mask('9999 9999 9999 9999', {autoclear: true});
            $('#frmCCExp').mask('m9/99', {autoclear: true});
            $('#frmCCCVC').mask('999', {autoclear: true});
            $('.cpf').mask('999.999.999-99', {autoclear: false});
        });

        $('.brl').maskMoney({prefix:'', allowNegative: false, allowZero: true, thousands:'.', decimal:',', affixesStay: false});

        $('.upper_case').keyup(function()
        {
            this.value = this.value.toUpperCase();
        });
    </script>
@endsection
