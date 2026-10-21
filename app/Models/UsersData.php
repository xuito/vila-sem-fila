<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersData extends Model
{
    use HasFactory;

    protected $table = 'users_data';

    protected $fillable =
    [
        'user_id',
        'cpf',
        'zip_code',
        'street_address',
        'address_number',
        'complement',
        'district',
        'city',
        'uf',
        'tel'
    ];

    protected static function getUsersData()
    {
        $usersData = UsersData::all();

        return $usersData;
    }

    protected static function getUserData($userId)
    {
        $userData = UsersData::where('users_data.user_id', $userId)
            ->leftJoin('users as u', 'users_data.user_id', '=', 'u.id')
            ->first();

        return $userData;
    }
}
