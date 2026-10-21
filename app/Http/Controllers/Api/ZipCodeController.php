<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ZipCodeController extends Controller
{
    public function zipCodeQuery(Request $request)
    {
        $zipCode = $request->zip_code ?? '00000-000';
        $zipCode = cleanZipCode($zipCode);

        // URL da API VIACEP
        $url = "https://viacep.com.br/ws/{$zipCode}/json/";

        // Inicializar cURL
        $ch = curl_init();

        // Configurar as opções do cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Executar a requisição e obter a resposta
        $content = curl_exec($ch);

        // Verificar se a requisição foi bem-sucedida
        if($content === false)
        {
            $response = ['success' => false];
            return $response;
        }

        // Decodificar o JSON retornado pela API
        $response = json_decode($content, true);

        // Verificar se o CEP é válido
        if(isset($response['erro']))
        {
            $response = ['success' => false];
            return $response;
        }

        // Fechar a sessão cURL
        curl_close($ch);

        $response['success'] = true;

        return response($response, 201);
    }
}
