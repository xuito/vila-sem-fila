<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

class BillController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Protected Functions
    |--------------------------------------------------------------------------
    */

    protected function getOperation($ticket)
	{
        $serverIP = Config::get('constants.SERVER_IP');
        $serverPort = Config::get('constants.SERVER_PORT');
        $serverToken = Config::get('constants.SERVER_TOKEN');

        $url = 'https://' . $serverIP . '/api/sem-fila/operacao';
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

        $url = 'https://' . $serverIP . '/api/sem-fila/cliente';
        $headers = array('Accept: application/json', 'Authorization: Bearer ' . $serverToken);
        $postfields = array('clientId' => $clientId);

        $response = curlPost($url, $postfields, false, $headers);

        return $response;
    }

    /*
    |--------------------------------------------------------------------------
    | Public Functions
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('site.bill-create');
    }

    public function store(Request $request)
    {
        $ticket = $request->ticket;
        $cpf = $request->cpf;

        //BUSCA INFORMACÕES SOBRE A OPERAÇÃO E DADOS DO CLIENTE (NOME E CPF)
        $response = $this->getOperation($ticket);

        if(empty($response->status))
        {
            return redirect()->route('site.bill.create')->withErrors('Servidor offline.');
        }

        //RETORNA ERRO NO CASO DA FICHA NÃO ESTAR EM USO
        if($response->status->codigo != '200' || ($response->dados->estado != 'consumindo' && $response->dados->estado != 'em pagamento'))
        {
            return redirect()->route('site.bill.create')->withErrors('Pulseira não encontrada ou sem consumo.');
        }

        //BUSCA APENAS AS INFORMAÇÕES RELEVANTES
        $productsTotal = $response->dados->totais->subtotal;
        $serviceTax = $productsTotal * 0.1;
        $entrance = $response->dados->totais->entrada;
        $subtotal = $response->dados->totais->faltaReceber;
        $consumptions = $response->dados->consumos;
        $amount = $subtotal + $serviceTax;

        //BUSCA O ID DO CLIENTE
        $consumption = reset($consumptions);
        $clientId = $consumption->cliente;

        //BUSCA O CPF DO CLIENTE
        $response = $this->getClient($clientId);

        //RETORNA ERRO NO CASO DA FICHA NÃO ESTAR EM USO
        if($response->status->codigo != '200' || empty($response->dados))
        {
            return redirect()->route('site.bill.create')->withErrors('CPF ausente no cadastro. Procure os caixas.');
        }

        $clientData = reset($response->dados);
        $cpfColibri = $clientData->cnpj_cpf;
        $clientFullName = explode(' ', mb_strtoupper($clientData->nome));
        $clientFirsName = reset($clientFullName);
        $clientLastName = end($clientFullName);
        $clientName = $clientFirsName . ' ' . $clientLastName;
        $cpfColibri = str_replace(array('.', '-'), '', $cpfColibri);
        $cpfTruncated = substr($cpfColibri, 0, 4);

        //VERIFICA SE O 4 PRIMEIROS DÍGITOS DO CPF ESTÃO CORRETOS... CASO CONTRARIO RETORNA ERRO
        if($cpf != $cpfTruncated)
        {
            return redirect()->route('site.bill.create')->withErrors('Os dados informados estão incorretos.');
        }

        //CRIA O ARRAY COM OS ITENS CONSUMIDOS
        $items = [];

        foreach($consumptions as $consumption)
        {
            foreach($consumption->venda->itens as $item)
            {
                $items[] = $item;
            }
        }

        return view('site.bill-list', [
            'ticket' => $ticket,
            'clientName' => $clientName,
            'cpf' => $cpfColibri,
            'productsTotal' => $productsTotal,
            'serviceTax' => $serviceTax,
            'entrance' => $entrance,
            'amount' => $amount,
            'items' => $items
        ]);
    }
}
