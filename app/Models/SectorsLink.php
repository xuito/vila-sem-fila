<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectorsLink extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'sector_id',
        'link',
        'description',
        'permission_id',
        'status'
    ];

    protected static function getSectorsLinks()
    {
        $sectorsLinks = SectorsLink::select(
            'sectors_links.link as sector_link',
            'sectors_links.description as link_description',
            's.id as sector_id',
            's.description as sector_description',
            's.icon as sector_icon',
            'p.name as sector_link_permission',
            'e.name as sector_permission'
        )
        ->where('sectors_links.status', 1)
        ->leftJoin('sectors as s', 'sectors_links.sector_id', '=', 's.id')
        ->leftJoin('permissions as p', 'sectors_links.permission_id', '=', 'p.id')
        ->leftJoin('permissions as e', 's.permission_id', '=', 'e.id')
        ->get();

        $sectorsLinks = $sectorsLinks->groupBy('sector_id');

        return $sectorsLinks;
    }
}
