<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'hash',
        'client_name',
        'client_cpf',
        'store_id',
        'ticket',
        'lock_id',
        'amount',
        'installments',
        'transaction_id',
        'payment_type',
        'holder',
        'holder_cpf',
        'charge_id',
        'status',
        'gateway_status',
        'paid_at'
    ];

    protected static function findPayment($paymentId)
    {
        $payment = Payment::all()->find($paymentId);

        return $payment;
    }

    protected static function getPayments()
    {
        $payment = Payment::all();

        return $payment;
    }

    protected static function getPayment($hash)
    {
        $payment = Payment::where('hash', $hash)
            ->first();

        return $payment;
    }
}
