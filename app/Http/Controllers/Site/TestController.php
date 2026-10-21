<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

//use App\Models\Store;

class TestController extends Controller
{
    public function index()
    {
        $referenceId = 'FRC-0001';
        $ordersURL = 'https://www.vilasemfila.com.br/';
        $data = 'Bla bla bla';

        $headers = array(
            'content-type: application/json',
            'Authorization: Bearer 9A9A9A9A9A9A9A9A9A9A9A9A'
        );

        $response = array(
            'content-type: application/json',
            'Authorization: Bearer 9A9A9A9A9A9A9A9A9A9A9A9A'
        );

        //GERAR LOG DA TRANSAÇÃO
        $logPath = storage_path('logs/payments/' . date('Ymd') . '.log');
        $content = date('d/m/Y H:i:s') . ' Order: ' . $referenceId . " Begins\n";
        $content .= date('d/m/Y H:i:s') . ' POST: ' . $ordersURL . "\n";
        $content .= date('d/m/Y H:i:s') . ' Headers: ' . json_encode($headers) . "\n";
        $content .= date('d/m/Y H:i:s') . ' Postfields: ' . $data . "\n";
        $content .= date('d/m/Y H:i:s') . ' Response: ' . json_encode($response) . "\n\n";
        writeFile($logPath, $content);
    }
}
