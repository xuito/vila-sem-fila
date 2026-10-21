<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Device;

class TokenController extends Controller
{
    public function generateToken(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'key' => 'required|string'
        ];

        $feedback = [
            'name.required' => 'Obrigatório o envio do nome do dispositivo.',
            'key.required' => 'Obrigatório preenchimento da permissão.'
        ];

        $request->validate($rules, $feedback);

        $device = Device::where('name', $request->name)->first();

        if(empty($device) || $device->key != $request->key)
        {
            return response(['error' => 'Credenciais inválidas'], 401);
        }

        $token = $device->createToken($device->name, [$device->abilities])->plainTextToken;

        return response($token, 201);
    }
}
