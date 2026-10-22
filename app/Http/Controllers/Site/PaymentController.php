<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use stdClass;
use Mail;
use App\Mail\SendMail;

use App\Models\Payment;

class PaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Protected Functions
    |--------------------------------------------------------------------------
    */

    protected function getTotals($ticket)
	{
        $serverIP = Config::get('constants.SERVER_IP');
        $serverPort = Config::get('constants.SERVER_PORT');
        $serverToken = Config::get('constants.SERVER_TOKEN');

        $url = 'http://' . $serverIP . ':' . $serverPort . '/api/sem-fila/totais';
        $headers = array('Accept: application/json', 'Authorization: Bearer ' . $serverToken);
        $postfields = array('ticket' => $ticket);

        $response = curlPost($url, $postfields, false, $headers);

        return $response;
    }

    protected function getOperation($ticket)
	{
        $serverIP = Config::get('constants.SERVER_IP');
        $serverPort = Config::get('constants.SERVER_PORT');
        $serverToken = Config::get('constants.SERVER_TOKEN');

        $url = 'http://' . $serverIP . ':' . $serverPort . '/api/sem-fila/operacao';
        $headers = array('Accept: application/json', 'Authorization: Bearer ' . $serverToken);
        $postfields = array('ticket' => $ticket);

        $response = curlPost($url, $postfields, false, $headers);

        return $response;
    }

    protected function getClient($clientId)
	{
        $serverIP = Config::get('constants.SERVER_IP');
        $serverPort = Config::get('constants.SERVER_PORT');
        $serverToken = Config::get('constants.SERVER_TOKEN');

        $url = 'http://' . $serverIP . ':' . $serverPort . '/api/sem-fila/cliente';
        $headers = array('Accept: application/json', 'Authorization: Bearer ' . $serverToken);
        $postfields = array('clientId' => $clientId);

        $response = curlPost($url, $postfields, false, $headers);

        return $response;
    }

    protected function closeBill($ticket)
	{
        $serverIP = Config::get('constants.SERVER_IP');
        $serverPort = Config::get('constants.SERVER_PORT');
        $serverToken = Config::get('constants.SERVER_TOKEN');

        $url = 'http://' . $serverIP . ':' . $serverPort . '/api/sem-fila/fechar-conta';
        $headers = array('Accept: application/json', 'Authorization: Bearer ' . $serverToken);
        $postfields = array('ticket' => $ticket);

        $response = curlPost($url, $postfields, false, $headers);

        return $response;
    }

    protected function blockReceipt($ticket)
	{
        $serverIP = Config::get('constants.SERVER_IP');
        $serverPort = Config::get('constants.SERVER_PORT');
        $serverToken = Config::get('constants.SERVER_TOKEN');

        $url = 'http://' . $serverIP . ':' . $serverPort . '/api/sem-fila/bloquear-recebimento';
        $headers = array('Accept: application/json', 'Authorization: Bearer ' . $serverToken);
        $postfields = array('ticket' => $ticket);

        $response = curlPost($url, $postfields, false, $headers);

        return $response;
    }

    protected function payment($ticket, $lockId, $amount)
    {
        $serverIP = Config::get('constants.SERVER_IP');
        $serverPort = Config::get('constants.SERVER_PORT');
        $serverToken = Config::get('constants.SERVER_TOKEN');

        $url = 'http://' . $serverIP . ':' . $serverPort . '/api/sem-fila/pagamento';
        $headers = array('Accept: application/json', 'Authorization: Bearer ' . $serverToken);
        $postfields = array('ticket' => $ticket, 'lockId' => $lockId, 'total' => $amount);

        $payment = curlPost($url, $postfields, false, $headers);

        return $payment;
    }

    protected function delReceiptBlock($ticket, $lockId)
    {
        $serverIP = Config::get('constants.SERVER_IP');
        $serverPort = Config::get('constants.SERVER_PORT');
        $serverToken = Config::get('constants.SERVER_TOKEN');

        $url = 'http://' . $serverIP . ':' . $serverPort . '/api/sem-fila/remover-bloqueio-recebimento';
        $headers = array('Accept: application/json', 'Authorization: Bearer ' . $serverToken);
        $postfields = array('ticket' => $ticket, 'lockId' => $lockId);

        $response = curlPost($url, $postfields, false, $headers);
    }

    protected function reopenBill($ticket)
    {
        $serverIP = Config::get('constants.SERVER_IP');
        $serverPort = Config::get('constants.SERVER_PORT');
        $serverToken = Config::get('constants.SERVER_TOKEN');

        $url = 'http://' . $serverIP . ':' . $serverPort . '/api/sem-fila/reabrir-conta';
        $headers = array('Accept: application/json', 'Authorization: Bearer ' . $serverToken);
        $postfields = array('ticket' => $ticket);

        $response = curlPost($url, $postfields, false, $headers);
    }

    protected function saveLocalTable(
            $paymentId,
            $paymentHash,
            $operationId,
            $paymentType,
            $lockId,
            $ticket,
            $clientName,
            $cpf,
            $amount,
            $change,
            $status,
            $recheck,
            $nfe
        )
    {
        $serverIP = Config::get('constants.SERVER_IP');
        $serverPort = Config::get('constants.SERVER_PORT');
        $serverToken = Config::get('constants.SERVER_TOKEN');

        $url = 'http://' . $serverIP . ':' . $serverPort . '/api/sem-fila/salvar-pagamento';
        $headers = array('Accept: application/json', 'Authorization: Bearer ' . $serverToken);
        $postfields = array(
                'orderId' => $paymentId,
                'orderHash' => $paymentHash,
                'operationId' => $operationId,
                'paymentType' => $paymentType,
                'lockId' => $lockId,
                'ticket' => $ticket,
                'clientName' => $clientName,
                'cpf' => $cpf,
                'total' => $amount,
                'change' => $change,
                'status' => $status,
                'recheck' => $recheck,
                'nfe' => $nfe
            );

        $response = curlPost($url, $postfields, false, $headers);
    }

    protected function updatePaymentStatus($paymentHash, $paymentType, $status)
    {
        $serverIP = Config::get('constants.SERVER_IP');
        $serverPort = Config::get('constants.SERVER_PORT');
        $serverToken = Config::get('constants.SERVER_TOKEN');

        $url = 'http://' . $serverIP . ':' . $serverPort . '/api/sem-fila/atualizar-status-pagamento';
        $headers = array('Accept: application/json', 'Authorization: Bearer ' . $serverToken);
        $postfields = array('orderHash' => $paymentHash, 'payment_type' => $paymentType, 'status' => $status);

        $response = curlPost($url, $postfields, false, $headers);
    }

    protected function updateRecheckStatus($paymentHash, $paymentType, $status)
    {
        $serverIP = Config::get('constants.SERVER_IP');
        $serverPort = Config::get('constants.SERVER_PORT');
        $serverToken = Config::get('constants.SERVER_TOKEN');

        $url = 'http://' . $serverIP . ':' . $serverPort . '/api/sem-fila/atualizar-recheck';
        $headers = array('Accept: application/json', 'Authorization: Bearer ' . $serverToken);
        $postfields = array('orderHash' => $paymentHash, 'payment_type' => $paymentType, 'status' => $status);

        $response = curlPost($url, $postfields, false, $headers);
    }

    protected function getPaymentByHash($paymentHash)
    {
        $serverIP = Config::get('constants.SERVER_IP');
        $serverPort = Config::get('constants.SERVER_PORT');
        $serverToken = Config::get('constants.SERVER_TOKEN');

        $url = 'http://' . $serverIP . ':' . $serverPort . '/api/sem-fila/pagamento-hash';
        $headers = array('Accept: application/json', 'Authorization: Bearer ' . $serverToken);
        $postfields = array('orderHash' => $paymentHash);

        $response = curlPost($url, $postfields, false, $headers);

        return $response;
    }

    protected function invoice($paymentHash, $operationId, $clientName, $cpf)
    {
        $serverIP = Config::get('constants.SERVER_IP');
        $serverPort = Config::get('constants.SERVER_PORT');
        $serverToken = Config::get('constants.SERVER_TOKEN');

        $url = 'http://' . $serverIP . ':' . $serverPort . '/api/sem-fila/nota-fiscal';
        $headers = array('Accept: application/json', 'Authorization: Bearer ' . $serverToken);
        $postfields = array('orderHash' => $paymentHash, 'operationId' => $operationId, 'clientName' => $clientName, 'cpf' => $cpf);

        $response = curlPost($url, $postfields, false, $headers);

        return $response;
    }

    protected function paymentData($paymentId, $referenceId, $holder, $email, $cpf, $paymentTotal)
	{
        $data['reference_id'] = $referenceId;

        $data['customer']['name'] = mb_strtoupper($holder);
        $data['customer']['email'] = $email;
        $data['customer']['tax_id'] = $cpf;

        $data['items'][0]['reference_id'] = $referenceId;
        $data['items'][0]['name'] = 'Pagamento de consumo no Bar Vila Dionísio';
        $data['items'][0]['quantity'] = 1;
        $data['items'][0]['unit_amount'] = $paymentTotal;

        //URL DE NOTIFICAÇÃO
        $data['notification_urls'][0] = Config::get('constants.GATEWAY_NOTIFICATION_URL');

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | Public Functions
    |--------------------------------------------------------------------------
    */

    public function create(Request $request)
    {
        $serverIP = Config::get('constants.SERVER_IP');
        $serverPort = Config::get('constants.SERVER_PORT');
        $serverToken = Config::get('constants.SERVER_TOKEN');

        $ticket = $request->ticket;
        $clientName = $request->client_name;
        $cpf = $request->cpf;

        //BUSCA INFORMACOES DO PEDIDO PARA EXIBIR NA TELA DE PAGAMENTO (VALORES)
        $response = $this->getTotals($ticket);

        //RETORNA ERRO NO CASO DA FICHA NÃO ESTAR EM USO
        if($response->status->codigo != '200')
        {
            return redirect()->route('site.bill.create')->withErrors('Erro de comunicação. Tente novamente!');
        }

        $productsTotal = $response->dados->subtotal;
        $serviceTax = $productsTotal * 0.1;
        $entrance = $response->dados->entrada;
        $subtotal = $response->dados->faltaReceber;
        $amount = $subtotal + $serviceTax;

        //GRAVAR INFORMACOES NA TABELA DE PEDIDOS
        $payment = Payment::create([
            'hash' => null,
            'client_name' => $clientName,
            'client_cpf' => $cpf,
            'store_id' => Session::get('storeId'),
            'ticket' => $ticket,
            'lock_id' => null,
            'amount' => $amount,
            'installments' => 1,
            'transaction_id' => null,
            'payment_type' => null,
            'charge_id' => null,
            'status' => 'Iniciado',
            'gateway_status' => null,
            'paid_at' => null
        ]);

        $paymentId = $payment->id;
        $paymentHash = md5(date('Y-m-d') . $paymentId);

        //ATUALIZA TABELA DE PAGAMENTOS
        $updatePayment = Payment::findPayment($paymentId);
        $updatePayment->hash = $paymentHash;
        $updatePayment->save();

        if($amount == 0)
        {
            //FECHAR CONTA
            $response = $this->closeBill($ticket);

            //RETORNA ERRO NO CASO DA FICHA ESTAR BLOQUEADA
            if($response->status->codigo != '201')
            {
                return redirect()->route('site.bill.create')->withErrors('Pulseira aberta em outro terminal! Tente novamente em 5 minutos!');
            }

            //BLOQUEAR O TICKET PARA NÃO RECEBER MAIS CONSUMOS DURANTE O PAGAMENTO
            $response = $this->blockReceipt($ticket);

            //RETORNA ERRO NO CASO DA FICHA ESTAR BLOQUEADA
            if($response->status->codigo != '200' || empty($response->dados))
            {
                return redirect()->route('site.bill.create')->withErrors('Pulseira aberta em outro terminal! Tente novamente em 5 minutos!');
            }

            $lockId = str_replace(array('{', '}'), '', $response->dados->id);

            //ATUALIZA TABELA DE PAGAMENTOS
            $updatePayment = Payment::findPayment($paymentId);
            $updatePayment->lock_id = $lockId;
            $updatePayment->payment_type = 'NONE';
            $updatePayment->status = 'Pago';
            $updatePayment->paid_at = now();
            $updatePayment->save();

            //GRAVAR PEDIDO NA TABELA LOCAL DA LOJA
            $saveLocalTable = $this->saveLocalTable(
                    $paymentId,
                    $paymentHash,
                    null,
                    null,
                    $lockId,
                    $ticket,
                    $clientName,
                    $cpf,
                    $amount,
                    $serviceTax,
                    3,
                    0,
                    null
                );

            $payment = $this->payment($ticket, $lockId, $amount);

            if($payment->status->codigo != '200')
            {
                $updateRecheckStatus = $this->updateRecheckStatus($paymentHash, $paymentType, 1);

                return redirect()->route('site.payment.response', [
                    'status' => 'error',
                    'paymentHash' => $paymentHash,
                    'message' => 'Falha de comunicação. Por favor, dirija-se ao caixa!'
                ]);
            }

            return redirect()->route('site.payment.response', [
                'status' => 'success',
                'paymentHash' => $paymentHash,
                'message' => 'Conta encerrada com sucesso!'
            ]);
        }

        return view('site.payment', [
            'paymentId' => $paymentId,
            'paymentHash' => $paymentHash,
            'ticket' => $ticket,
            'clientName' => $clientName,
            'cpf' => $cpf,
            'amount' => $amount
        ]);
    }

    public function store(Request $request)
    {
        if($request->_token)
        {
            $serverIP = Config::get('constants.SERVER_IP');
            $serverPort = Config::get('constants.SERVER_PORT');
            $serverToken = Config::get('constants.SERVER_TOKEN');
            $ordersURL = Config::get('constants.GATEWAY_URL') . 'orders';

            $ticket = $request->ticket;
            $paymentId = $request->paymentId;
            $clientName = $request->clientName;
            $email = $request->email;
            $clientCPF = $request->clientCPF;
            $cpf = onlyNumbers($request->cpf);

            $holder = $request->holder;
            $encrypted = $request->encrypted;
            $securityCode = $request->securityCode;

            //BUSCA INFORMACOES DO PEDIDO PARA EXIBIR NA TELA DE PAGAMENTO (VALORES)
            $response = $this->getTotals($ticket);

            //RETORNA ERRO NO CASO DA FICHA NÃO ESTAR EM USO
            if($response->status->codigo != '200')
            {
                $response = ['status' => 'error', 'paymentHash' => null, 'message' => 'Falha de comunicação. Por favor, dirija-se ao caixa!'];
                return json_encode($response);
            }

            $productsTotal = $response->dados->subtotal;
            $serviceTax = $productsTotal * 0.1;
            $entrance = $response->dados->entrada;
            $subtotal = $response->dados->faltaReceber;
            $amount = $subtotal + $serviceTax;
            $paymentTotal = parseCents($amount);

            //FECHAR CONTA
            $response = $this->closeBill($ticket);

            //RETORNA ERRO NO CASO DA FICHA ESTAR BLOQUEADA
            if($response->status->codigo != '201')
            {
                $response = ['status' => 'error', 'paymentHash' => null,  'message' => 'Pulseira em pagamento. Tente novamente em 5 minutos!'];
                return json_encode($response);
            }

            //BLOQUEAR O TICKET PARA NÃO RECEBER MAIS CONSUMOS DURANTE O PAGAMENTO
            $response = $this->blockReceipt($ticket);

            //RETORNA ERRO NO CASO DA FICHA ESTAR BLOQUEADA
            if($response->status->codigo != '200' || empty($response->dados))
            {
                $response = ['status' => 'error', 'paymentHash' => null, 'message' => 'Pulseira aberta em outro terminal! Tente novamente em 5 minutos!'];
                return json_encode($response);
            }

            $lockId = str_replace(array('{', '}'), '', $response->dados->id);

            //GRAVAR INFORMACOES NA TABELA DE PEDIDOS
            $payment = Payment::create([
                'hash' => null,
                'client_name' => $clientName,
                'client_cpf' => $clientCPF,
                'store_id' => Session::get('storeId'),
                'ticket' => $ticket,
                'lock_id' => $lockId,
                'amount' => $amount,
                'installments' => 1,
                'transaction_id' => null,
                'payment_type' => null,
                'holder' => $holder,
                'holder_cpf' => $cpf,
                'charge_id' => null,
                'status' => 'Iniciado',
                'gateway_status' => null,
                'paid_at' => null
            ]);

            $paymentId = $payment->id;
            $paymentHash = md5(date('Y-m-d') . $paymentId);

            $updatePayment = Payment::findPayment($paymentId);
            $updatePayment->hash = $paymentHash;
            $updatePayment->save();

            //GRAVAR PEDIDO NA TABELA LOCAL DA LOJA
            $saveLocalTable = $this->saveLocalTable(
                    $paymentId,
                    $paymentHash,
                    null,
                    null,
                    $lockId,
                    $ticket,
                    $clientName,
                    $cpf,
                    $amount,
                    $serviceTax,
                    0,
                    0,
                    null
            );

            //DADOS REFERENTES AO PEDIDO PARA ENVIO AO PAGSEGURO
            $referenceId = mb_strtoupper(Session::get('storeAbbr')) . '-' . $paymentId;
            $data = $this->paymentData($paymentId, $referenceId, $holder, $email, $cpf, $paymentTotal);

            //DADOS RELACIONADOS COM A FORMA DE PAGAMENTO CARTÃO DE CRÉDITO
            $data['charges'][0]['reference_id'] = $referenceId;
            $data['charges'][0]['description'] = 'Pagamento de consumo - Pulseira ' . $ticket;
            $data['charges'][0]['amount']['value'] = $paymentTotal;
            $data['charges'][0]['amount']['currency'] = 'BRL';
            $data['charges'][0]['payment_method']['type'] = 'CREDIT_CARD';
            $data['charges'][0]['payment_method']['installments'] = 1;
            $data['charges'][0]['payment_method']['capture'] = true;
            $data['charges'][0]['payment_method']['soft_descriptor'] = 'VILASEMFILA';
            $data['charges'][0]['payment_method']['card']['encrypted'] = $encrypted;
            $data['charges'][0]['payment_method']['card']['security_code'] = $securityCode;
            $data['charges'][0]['payment_method']['card']['holder']['name'] = $holder;
            $data['charges'][0]['payment_method']['card']['store'] = false;

            $curl = curl_init();
            $headers = array(
                'content-type: application/json',
                'Authorization: Bearer ' . Config::get('constants.GATEWAY_TOKEN')
            );

            curl_setopt_array($curl, array
                (
                    CURLOPT_URL => $ordersURL,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode($data),
                    CURLOPT_HTTPHEADER => $headers,
            ));

            $response = curl_exec($curl);
            $result = json_decode($response);
            curl_close($curl);

            //GERAR LOG DA TRANSAÇÃO
            $logPath = storage_path('logs/payments/' . date('Ymd') . '.log');
            $content = date('d/m/Y H:i:s') . ' Order: ' . $referenceId . " Begins\n";
            $content .= date('d/m/Y H:i:s') . ' POST: ' . $ordersURL . "\n";
            $content .= date('d/m/Y H:i:s') . ' Response: ' . json_encode(paymentLogSummary($result)) . "\n\n";
            writeFile($logPath, $content);

            if(empty($result->error_messages))
            {
                $transactionId = $result->id;
                $chargeId = $result->charges[0]->id;
                $installments = $result->charges[0]->payment_method->installments;
                $paymentType = $result->charges[0]->payment_method->type;
                $message = $result->charges[0]->payment_response->message;
                $status = $result->charges[0]->status;

                $updatePayment = Payment::findPayment($paymentId);
                $updatePayment->transaction_id = $transactionId;
                $updatePayment->payment_type = $paymentType;
                $updatePayment->charge_id = $chargeId;
                $updatePayment->installments = $installments;
                $updatePayment->gateway_status = $status;

                //PAGAMENTO APROVADO
                if($status == 'PAID' || $status == 'AUTHORIZED')
                {
                    //ATUALIZAR A TABELA DE PEDIDOS
                    $updatePayment->status = 'Pago';
                    $updatePayment->paid_at = $result->charges[0]->paid_at;
                    $updatePayment->save();

                    //UPDATE STAUS DE PAGAMENTO NA TABELA LOCAL DA LOJA
                    $updatePaymentStatus = $this->updatePaymentStatus($paymentHash, $paymentType, 3);

                    //DÁ BAIXA NO COLIBRI
                    $payment = $this->payment($ticket, $lockId, $amount);

                    if($payment->status->codigo != '200')
                    {
                        $updateRecheckStatus = $this->updateRecheckStatus($paymentHash, $paymentType, 1);

                        $response = ['status' => 'error', 'paymentHash' => $paymentHash, 'message' => 'Pagamento realizado, porém sem baixa no sistema. Por favor, procure o caixa!'];
                        return json_encode($response);
                    }

                    $response = ['status' => 'success', 'paymentHash' => $paymentHash, 'message' => 'Conta encerrada com sucesso!'];
                    return json_encode($response);
                }

                //PAGAMENTO AUTORIZADO OU EM ANÁLISE
                elseif($status == 'IN_ANALYSIS' || $status == 'WAITING')
                {
                    //ATUALIZAR A TABELA DE PEDIDOS
                    $updatePayment->status = 'Em análise';
                    $updatePayment->save();

                    //UPDATE STATUS DE PAGAMENTO NA TABELA LOCAL DA LOJA
                    $updatePaymentStatus = $this->updatePaymentStatus($paymentHash, $paymentType, 2);
                    $updateRecheckStatus = $this->updateRecheckStatus($paymentHash, $paymentType, 1);

                    $response = ['status' => 'pending', 'paymentHash' => $paymentHash, 'message' => 'Pagamento em processamento.'];
                    return json_encode($response);
                }

                //PAGAMENTO NÃO AUTORIZADO
                else
                {
                    //ATUALIZAR A TABELA DE PEDIDOS
                    $updatePayment->status = 'Não autorizado';
                    $updatePayment->save();

                    //UPDATE STAUS DE PAGAMENTO NA TABELA LOCAL DA LOJA
                    $updatePaymentStatus = $this->updatePaymentStatus($paymentHash, $paymentType, 7);

                    //REMOVER BLOQUEIO DE RECEBIMENTO
                    $response = $this->delReceiptBlock($ticket, $lockId);

                    //REABRIR CONTA
                    $response = $this->reopenBill($ticket);

                    $response = ['status' => 'error', 'paymentHash' => $paymentHash, 'message' => 'Pagamento não autorizado. Procure o caixa!'];
                    return json_encode($response);
                }
            }
            else
            {
                //ATUALIZAR A TABELA DE PEDIDOS
                $updatePayment->status = 'Não autorizado';
                $updatePayment->save();

                //UPDATE STAUS DE PAGAMENTO NA TABELA LOCAL DA LOJA
                $updatePaymentStatus = $this->updatePaymentStatus($paymentHash, $paymentType, 7);

                //REMOVER BLOQUEIO DE RECEBIMENTO
                $response = $this->delReceiptBlock($ticket, $lockId);

                //REABRIR CONTA
                $response = $this->reopenBill($ticket);

                $response = ['status' => 'error', 'paymentHash' => $paymentHash, 'message' => 'Erro no pagamento. Tente novamente!'];
                return json_encode($response);
            }
        }
    }

    public function pixPayment(Request $request)
    {
        if($request->_token)
        {
            $serverIP = Config::get('constants.SERVER_IP');
            $serverPort = Config::get('constants.SERVER_PORT');
            $serverToken = Config::get('constants.SERVER_TOKEN');
            $ordersURL = Config::get('constants.GATEWAY_URL') . 'orders';

            $pixData = '';
            $paymentHash = '';
            $ticket = $request->ticket;
            $paymentId = $request->paymentId;
            $clientName = $request->clientName;
            $cpf = $request->cpf;
            $email = 'bar@viladionisio.com';

            //BUSCA INFORMACOES DO PEDIDO PARA EXIBIR NA TELA DE PAGAMENTO (VALORES)
            $response = $this->getTotals($ticket);

            //RETORNA ERRO NO CASO DA FICHA NÃO ESTAR EM USO
            if($response->status->codigo != '200')
            {
                $pixData .= '<div class="small text-center">Falha de comunicação. Por favor, dirija-se ao caixa!</div>';

                $response = ['pixData' => $pixData, 'paymentHash' => $paymentHash];
                return $response;
            }

            $productsTotal = $response->dados->subtotal;
            $serviceTax = $productsTotal * 0.1;
            $entrance = $response->dados->entrada;
            $subtotal = $response->dados->faltaReceber;
            $amount = $subtotal + $serviceTax;
            $paymentTotal = parseCents($amount);

            //FECHAR CONTA
            $response = $this->closeBill($ticket);

            //RETORNA ERRO NO CASO DA FICHA ESTAR BLOQUEADA
            if($response->status->codigo != '201')
            {
                $pixData .= '<div class="small text-center">Pulseira em pagamento. Tente novamente em 5 minutos!</div>';

                $response = ['pixData' => $pixData, 'paymentHash' => $paymentHash];
                return $response;
            }

            //BLOQUEAR O TICKET PARA NÃO RECEBER MAIS CONSUMOS DURANTE O PAGAMENTO
            $response = $this->blockReceipt($ticket);

            //RETORNA ERRO NO CASO DA FICHA ESTAR BLOQUEADA
            if($response->status->codigo != '200' || empty($response->dados))
            {
                $pixData .= '<div class="small text-center">Pulseira aberta em outro terminal! Tente novamente em 5 minutos!</div>';

                $response = ['pixData' => $pixData, 'paymentHash' => $paymentHash];
                return $response;
            }

            $lockId = str_replace(array('{', '}'), '', $response->dados->id);

            //GRAVAR INFORMACOES NA TABELA DE PEDIDOS
            $payment = Payment::create([
                'hash' => null,
                'client_name' => $clientName,
                'client_cpf' => $cpf,
                'store_id' => Session::get('storeId'),
                'ticket' => $ticket,
                'lock_id' => $lockId,
                'amount' => $amount,
                'installments' => 1,
                'transaction_id' => null,
                'payment_type' => null,
                'charge_id' => null,
                'status' => 'Iniciado',
                'gateway_status' => null,
                'paid_at' => null
            ]);

            $paymentId = $payment->id;
            $paymentHash = md5(date('Y-m-d') . $paymentId);

            $updatePayment = Payment::findPayment($paymentId);
            $updatePayment->hash = $paymentHash;
            $updatePayment->save();

            //GRAVAR PEDIDO NA TABELA LOCAL DA LOJA
            $saveLocalTable = $this->saveLocalTable(
                    $paymentId,
                    $paymentHash,
                    null,
                    null,
                    $lockId,
                    $ticket,
                    $clientName,
                    $cpf,
                    $amount,
                    $serviceTax,
                    0,
                    0,
                    null
            );

            //DADOS REFERENTES AO PEDIDO PARA ENVIO AO PAGSEGURO
            $referenceId = mb_strtoupper(Session::get('storeAbbr')) . '-' . $paymentId;
            $data = $this->paymentData($paymentId, $referenceId, $clientName, $email, $cpf, $paymentTotal);

            //DADOS RELACIONADOS COM A FORMA DE PAGAMENTO PIX
            $data["qr_codes"][0]["amount"]["value"] = $paymentTotal;
            $data["qr_codes"][0]["expiration_date"] = date('c', strtotime('+5 minutes'));

            $curl = curl_init();
            $headers = array(
                'content-type: application/json',
                'Authorization: Bearer ' . Config::get('constants.GATEWAY_TOKEN')
            );

            curl_setopt_array($curl, array
                (
                    CURLOPT_URL => $ordersURL,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode($data),
                    CURLOPT_HTTPHEADER => $headers,
            ));

            $response = curl_exec($curl);
            $result = json_decode($response);
            curl_close($curl);

            //GERAR LOG DA TRANSAÇÃO
            $logPath = storage_path('logs/payments/' . date('Ymd') . '.log');
            $content = date('d/m/Y H:i:s') . ' Order: ' . $referenceId . " Begins\n";
            $content .= date('d/m/Y H:i:s') . ' POST: ' . $ordersURL . "\n";
            $content .= date('d/m/Y H:i:s') . ' Response: ' . json_encode(paymentLogSummary($result)) . "\n\n";
            writeFile($logPath, $content);

            if(empty($result->error_messages))
            {
                $transactionId = $result->id;

                // ATUALIZAR PAGAMENTO NO BANCO DE DADOS
                $updatePayment = Payment::findPayment($paymentId);
                $updatePayment->transaction_id = $transactionId;
                $updatePayment->status = 'Pendente';
                $updatePayment->save();

                //QRCODE E CHAVE
                foreach($result->qr_codes as $lines)
                {
                    $qrCodeId = $lines->id;
                    $pixCode = $lines->text;

                    foreach($lines->links as $url)
                    {
                        if($url->rel == "QRCODE.PNG")
                        {
                            $pixData .= '<div class="text-center mb-3"><img src="' . $url->href . '" class="qr_code"></div>';
                        }
                    }

                    $pixData .= '<div class="small text-center">' . $pixCode . '</div>';
                    //$pixData .= '<div class="small text-center">' . $qrCodeId . '</div>'; //CODIGO PARA SIMULAR PAGAMENTO - DESATIVAR EM PRODUÇÃO
                    $pixData .= '<div class="text-center"><span class="badge rounded-pill text-bg-secondary px-3 mt-2 text-center clickable" onclick="navigator.clipboard.writeText(\'' . $pixCode . '\').then(() => { alert(\'Código PIX copiado para a área de transferência!\') })"><i class="material-icons align-middle">content_copy</i> COPIAR CÓDIGO PIX</span></div>';
                    $pixData .= '<div class="fw-bold mt-3 text-center text-danger">Código PIX válido por 5 minutos. Após o pagamento basta aguardar.</div>';
                }
            }
            else
            {
                    $pixData .= '<div class="small text-center">Problema na geração do QR-Code. Por favor, tente novamente!</div>';
            }

            $response = ['pixData' => $pixData, 'paymentHash' => $paymentHash];
            return $response;
        }
    }

    public function paymentResponse(Request $request)
    {
        //VERIFICAR O STATUS, E A PARTIR DISSO DEFINIR A RESPOSTA, SALVAR COISAS NA TABELA E EMITIR A NOTA FISCAL
        $status = $request->status;
        $paymentHash = $request->paymentHash;
        $message = $request->message;

        //BUSCAR DADOS DO PEDIDO E AS INFORMACOES PARA GERAR A NOTA FISCAL

        if($status == 'success')
        {
            $payment = $this->getPaymentByHash($paymentHash);

            $ticket = $payment->ticket;
            $clientName = $payment->client_name;
            $cpf = $payment->cpf;

            $operation = $this->getOperation($ticket);

            $operationId = str_replace(array('{', '}'), '', $operation->dados->operacao_id);

            $invoice = $this->invoice($paymentHash, $operationId, $clientName, $cpf);

            return redirect()->route('site.bill.create')->with('message', $request->message);
        }
        else
        {
            return redirect()->route('site.bill.create')->withErrors($request->message);
        }
    }

    /*

    //DESATIVAR EM PRODUÇÃO

    public function simulatePIXPayment($qrCode)
	{
        $paymentURL = Config::get('constants.GATEWAY_URL') . 'pix/pay/' . $qrCode;

        $curl = curl_init();

        curl_setopt_array($curl, array
            (
                CURLOPT_URL => $paymentURL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => array(
                'content-type: application/json',
                'Authorization: Bearer ' . Config::get('constants.GATEWAY_TOKEN')
            ),
        ));

        $response = curl_exec($curl);
        $result = json_decode($response);
        curl_close($curl);

        return response(true, 200);
    }

    public function generatePublicKey()
    {
        $publicKeyURL = Config::get('constants.GATEWAY_URL') . 'public-keys';

        $data['type'] = 'card';

        $curl = curl_init();
        $headers = array(
            'Authorization: Bearer ' . Config::get('constants.GATEWAY_TOKEN'),
            'accept: *//*',
            'content-type: application/json'
        );

        curl_setopt_array($curl, array
            (
                CURLOPT_URL => $publicKeyURL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);
        $result = json_decode($response);
        curl_close($curl);

        return $result;
    }

    */
}
