<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

use App\Models\Store;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function notification(Request $request)
    {
        $json = file_get_contents('php://input');
        $decodedJson  = json_decode($json);

        $transactionId = $decodedJson->id;
        $paymentReference = $decodedJson->reference_id;
        $paymentReference = explode('-', $paymentReference);
        $abbr = mb_strtolower($paymentReference[0]);
        $paymentId = $paymentReference[1];

        $storeParameters = Store::getStoreParametersByAbbr($abbr);

        $serverIP = $storeParameters->server_ip;
        $serverPort = $storeParameters->server_port;
        $serverToken = $storeParameters->server_token;

        $transactionURL = $storeParameters->gateway_url . 'orders/' . $transactionId;

        $curl = curl_init();
        $headers = array(
            'content-type: application/json',
            'Authorization: Bearer ' . $storeParameters->gateway_token
        );

        curl_setopt_array($curl, array
        (
            CURLOPT_URL => $transactionURL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $headers,
        ));
        $response = curl_exec($curl);
        $result = json_decode($response);
        curl_close($curl);

        //GERAR LOG DA TRANSAÇÃO
        $logPath = storage_path('logs/payments/' . date('Ymd') . '.log');
        $content = date('d/m/Y H:i:s') . ' Order: ' . $decodedJson->reference_id . " Begins\n";
        $content .= date('d/m/Y H:i:s') . ' POST: ' . $transactionURL . "\n";
        $content .= date('d/m/Y H:i:s') . ' Response: ' . json_encode(paymentLogSummary($result)) . "\n\n";
        writeFile($logPath, $content);

        if(empty($result->error_messages))
        {
            $transactionId = $result->id;
            $chargeId = $result->charges[0]->id;
            $paymentType = $result->charges[0]->payment_method->type;
            $message = $result->charges[0]->payment_response->message;
            $status = $result->charges[0]->status;

            $updatePayment = Payment::findPayment($paymentId);

            $paymentHash = $updatePayment->hash;
            $ticket = $updatePayment->ticket;
            $lockId = $updatePayment->lock_id;
            $amount = $updatePayment->amount;
            $gateway_status = $updatePayment->gateway_status;

            if($status != $gateway_status)
            {
                $updatePayment->transaction_id = $transactionId;
                $updatePayment->payment_type = $paymentType;
                $updatePayment->charge_id = $chargeId;
                $updatePayment->gateway_status = $status;

                //PAGAMENTO APROVADO
                if($status == 'PAID' || $status == 'AUTHORIZED')
                {
                    //ATUALIZAR A TABELA DE PEDIDOS
                    $updatePayment->status = 'Pago';
                    $updatePayment->paid_at = $result->charges[0]->paid_at;
                    $updatePayment->save();

                    //UPDATE STAUS DE PAGAMENTO NA TABELA LOCAL DA LOJA
                    $url = 'http://' . $serverIP . ':' . $serverPort . '/api/sem-fila/atualizar-status-pagamento';
                    $headers = array('Accept: application/json', 'Authorization: Bearer ' . $serverToken);
                    $postfields = array('orderHash' => $paymentHash, 'payment_type' => $paymentType, 'status' => 3);

                    $response = curlPost($url, $postfields, false, $headers);

                    //DÁ BAIXA NO COLIBRI
                    $url = 'http://' . $serverIP . ':' . $serverPort . '/api/sem-fila/pagamento';
                    $headers = array('Accept: application/json', 'Authorization: Bearer ' . $serverToken);
                    $postfields = array('ticket' => $ticket, 'lockId' => $lockId, 'total' => $amount);

                    $payment = curlPost($url, $postfields, false, $headers);
                }

                //PAGAMENTO EM ANÁLISE
                elseif($status == 'IN_ANALYSIS' || $status == 'WAITING')
                {
                    //ATUALIZAR A TABELA DE PEDIDOS
                    $updatePayment->status = 'Em análise';
                    $updatePayment->save();

                    //UPDATE STAUS DE PAGAMENTO NA TABELA LOCAL DA LOJA
                    $url = 'http://' . $serverIP . ':' . $serverPort . '/api/sem-fila/atualizar-status-pagamento';
                    $headers = array('Accept: application/json', 'Authorization: Bearer ' . $serverToken);
                    $postfields = array('orderHash' => $paymentHash, 'payment_type' => $paymentType, 'status' => 2);

                    $response = curlPost($url, $postfields, false, $headers);
                }

                //PAGAMENTO NÃO AUTORIZADO
                elseif($status == 'DECLINED')
                {
                    //ATUALIZAR A TABELA DE PEDIDOS
                    $updatePayment->status = 'Não autorizado';
                    $updatePayment->save();

                    //UPDATE STAUS DE PAGAMENTO NA TABELA LOCAL DA LOJA
                    $url = 'http://' . $serverIP . ':' . $serverPort . '/api/sem-fila/atualizar-status-pagamento';
                    $headers = array('Accept: application/json', 'Authorization: Bearer ' . $serverToken);
                    $postfields = array('orderHash' => $paymentHash, 'payment_type' => $paymentType, 'status' => 7);

                    $response = curlPost($url, $postfields, false, $headers);

                    //REMOVER BLOQUEIO DE RECEBIMENTO
                    $url = 'http://' . $serverIP . ':' . $serverPort . '/api/sem-fila/remover-bloqueio-recebimento';
                    $headers = array('Accept: application/json', 'Authorization: Bearer ' . $serverToken);
                    $postfields = array('ticket' => $ticket, 'lockId' => $lockId);

                    $response = curlPost($url, $postfields, false, $headers);

                    //REABRIR CONTA
                    $url = 'http://' . $serverIP . ':' . $serverPort . '/api/sem-fila/reabrir-conta';
                    $headers = array('Accept: application/json', 'Authorization: Bearer ' . $serverToken);
                    $postfields = array('ticket' => $ticket);

                    $response = curlPost($url, $postfields, false, $headers);
                }

                //PAGAMENTO CANCELADO E OUTROS
                else
                {
                    //ATUALIZAR A TABELA DE PEDIDOS
                    $updatePayment->status = 'Cancelado';
                    $updatePayment->save();

                    //UPDATE STAUS DE PAGAMENTO NA TABELA LOCAL DA LOJA
                    $url = 'http://' . $serverIP . ':' . $serverPort . '/api/sem-fila/atualizar-status-pagamento';
                    $headers = array('Accept: application/json', 'Authorization: Bearer ' . $serverToken);
                    $postfields = array('orderHash' => $paymentHash, 'payment_type' => $paymentType, 'status' => 7);

                    $response = curlPost($url, $postfields, false, $headers);
                }
            }
        }

        return response(true, 200);
    }

    public function checkStatus($hash)
    {
        $paymentStatus = Payment::getPayment($hash)->status;

        return $paymentStatus;
    }
}
