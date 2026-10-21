<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Store;

class StoreAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //VERIFICA SE O USUÁRIO LOGADO POSSUI AUTORIZAÇÃO PARA ACESSAR A LOJA
        $storeId = $request->store_id;

        $storesUser = Store::getStoresUser(Auth::user()->id);
        $auth = $storesUser->where('store_id', $storeId)->first();

        if(!empty($auth))
        {
            return $next($request);
        }
        else
        {
            return redirect()->back()->withErrors(['O usuário não tem acesso a unidade escolhida!']);
        }
    }
}
