@extends('site.layouts.default')

@section('view_title', 'Vila Sem Fila')

@section('description', '')
@section('keywords', '')

@section('css')
@endsection

@section('scripts')
    <script src="{{ asset('js/jquery.maskedinput.js') }}"></script>
@endsection

@section('content')
    @php
    $i = 0;
    @endphp
    <div class="container pt-4 pb-5">
        <div class="row justify-content-center mb-4">
            <div class="col col-sm-10 col-md-8 col-lg-6">
                <div class="card border-0">
                    <h4 class="card-header bg-dark text-light text-center">Confira seu consumo e pague sua conta sem entrar na fila!</h4>
                    <div class="card-body p-4">
                        <form name="bill_list_form" action="{{ route('site.payment.create') }}" method="post">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th colspan="3" class="text-center bg-dark text-light">PULSEIRA #{{ $ticket }} - {{ mb_strtoupper($clientName) }}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="text-center">ITENS DA SUA CONTA</th>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-center">Quant.</th>
                                            <th scope="col" class="text-start">Produto</th>
                                            <th scope="col" class="text-end">Subtotal (R$)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $item)
                                            @if(!empty($item) && $item->cancelado != true && $item->transferido != true)
                                                @php
                                                    $i++;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        {{ $item->quantidade }}
                                                    </td>
                                                    <td class="text-start">
                                                        {{ $item->descricao }}
                                                    </td>
                                                    <td class="text-end">
                                                        R${{ number_format($item->valor, 2, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        @if($i == 0)
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                Nenhum item lançado.
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-active">
                                            <th colspan="2" scope="col" class="text-end">Total consumo:</th>
                                            <th scope="col" class="text-end">R${{ number_format($productsTotal, 2, ',', '.') }}</th>
                                        </tr>
                                        <tr class="table-active">
                                            <th colspan="2" scope="col" class="text-end">Serviço:</th>
                                            <th scope="col" class="text-end">R${{ number_format($serviceTax, 2, ',', '.') }}</th>
                                        </tr>
                                        @if($entrance > 0)
                                            <tr class="table-active">
                                                <th colspan="2" scope="col" class="text-end">Portaria:</th>
                                                <th scope="col" class="text-end">R${{ number_format($entrance, 2, ',', '.') }}</th>
                                            </tr>
                                        @endif
                                        <tr class="table-active">
                                            <th colspan="2" scope="col" class="text-end">TOTAL GERAL:</th>
                                            <th scope="col" class="text-end">R${{ number_format($amount, 2, ',', '.') }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="row mb-2" style="margin-top: -1rem;">
                                <div class="col fst-italic text-end">
                                    Atualizado em {{ date('H:i:s') }}
                                </div>
                            </div>
                            <div class="col d-flex justify-content-evenly align-items-center my-3 w-100">
                                <input type="hidden" name="ticket" value="{{ intval($ticket) }}">
                                <input type="hidden" name="client_name" value="{{ $clientName }}">
                                <input type="hidden" name="cpf" value="{{ $cpf }}">
                                <input type="submit" name="submit" class="btn btn-lg btn-dark w-100" value="FECHAR CONTA">
                            </div>
                        </form>
                        @if($amount == 0)
                            <div class="col mb-3">
                                <div class="alert alert-danger" role="alert">
                                    <b>ATENÇÃO</b>: Ao clicar em <b>FECHAR CONTA</b> sua conta será encerrada, não havendo possibilidade de novos consumos a menos que você faça um novo checkin.
                                </div>
                            </div>
                        @endif
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
            $('.number').mask('9999', {placeholder:'', autoclear: true});
        });
    </script>
@endsection
