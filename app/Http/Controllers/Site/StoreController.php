<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

use App\Models\Store;

class StoreController extends Controller
{
    public function redirect($slug)
    {
        $storeParameters = Store::getStoreParameters($slug);

        if(empty($storeParameters))
        {
            abort(404);
        }

        Session::put('storeSlug', $slug);

        return redirect()->route('site.bill.create');
    }

    public function selector()
    {
        return view('site.store-selector');
    }
}
