<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'description',
        'icon',
        'permission_id',
        'status'
    ];

    protected static function getSectors()
    {
        $sectors = Sector::all();

        return $sectors;
    }
}
