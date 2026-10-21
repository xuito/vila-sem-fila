<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

class Store extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'name',
        'company_name',
        'address',
        'address_number',
        'address_complement',
        'neighborhood',
        'city',
        'state',
        'zip_code',
        'landline',
        'cell_phone',
        'email',
        'slug',
        'abbr',
        'map',
        'mini_map',
        'coords',
        'status',
        'gateway_user',
        'gateway_notification_url',
        'gateway_token',
        'gateway_public_key',
        'gateway_url',
        'server_ip',
        'server_port',
        'server_token'
    ];

    protected static function getStores()
    {
        $stores = Store::all();

        return $stores;
    }

    protected static function getStore($storeId)
    {
        $store = Store::where('id', $storeId)
            ->first();

        return $store;
    }

    protected static function getStoreParameters($slug)
    {
        $store = Store::where('slug', $slug)
            ->first();

        return $store;
    }

    protected static function getStoreParametersByAbbr($abbr)
    {
        $store = Store::where('abbr', $abbr)
            ->first();

        return $store;
    }

    protected static function getStoresUser($userId)
    {
        if(Auth::user()->is_admin == 1)
        {
            $storesUsers = Store::select(
                'stores.id as store_id',
                'stores.name as store_name'
            )
            ->get();
        }
        else
        {
            $storesUsers = Store::select(
                    'stores.id as store_id',
                    'stores.name as store_name'
                )
                ->where('su.user_id', $userId)
                ->leftJoin('stores_users as su', 'stores.id', '=', 'su.store_id')
                ->get();
        }

        return $storesUsers;
    }
}
