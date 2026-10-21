<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Store;

class StoreParametersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Session::has('storeSlug'))
        {
            $storeSlug = Session::get('storeSlug');
            $storeParameters = Store::getStoreParameters($storeSlug);

            Session::put([
                'storeId' => $storeParameters->id,
                'storeName' => $storeParameters->name,
                'storeCompanyName' => $storeParameters->company_name,
                'storeAddress' => $storeParameters->address,
                'storeAddressNumber' => $storeParameters->address_number,
                'storeAddressComplement' => $storeParameters->address_complement,
                'storeNeighborhood' => $storeParameters->neighborhood,
                'storeCity' => $storeParameters->city,
                'storeState' => $storeParameters->state,
                'storeZipCode' => $storeParameters->zip_code,
                'storeLandline' => $storeParameters->landline,
                'storeCellPhone' => $storeParameters->cell_phone,
                'storeEmail' => $storeParameters->email,
                'storeAbbr' => $storeParameters->abbr,
                'storeMap' => $storeParameters->map,
                'storeMiniMap' => $storeParameters->mini_map,
                'storeCoords' => $storeParameters->coords,
                'storeStatus' => $storeParameters->status
            ]);

            //ALTERA O ARQUIVO DE CONSTANTES, ATRIBUINDO VALORES AS VARIAVEIS DE ACORDO COM A LOJA
            Config::set('constants.GATEWAY_USER', $storeParameters->gateway_user);
            Config::set('constants.GATEWAY_NOTIFICATION_URL', $storeParameters->gateway_notification_url);
            Config::set('constants.GATEWAY_TOKEN', $storeParameters->gateway_token);
            Config::set('constants.GATEWAY_PUBLIC_KEY', $storeParameters->gateway_public_key);
            Config::set('constants.GATEWAY_URL', $storeParameters->gateway_url);
            Config::set('constants.SERVER_IP', $storeParameters->server_ip);
            Config::set('constants.SERVER_PORT', $storeParameters->server_port);
            Config::set('constants.SERVER_TOKEN', $storeParameters->server_token);

            return $next($request);
        }
        else
        {
            return redirect()->route('site.store.selector');
        }
    }
}
