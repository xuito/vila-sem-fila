<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SectorsLink;

class DashboardController extends Controller
{
    public function index()
    {
        $sectorsLinks = SectorsLink::getSectorsLinks();

        return view('dashboard.index', [
            'sectorsLinks' => $sectorsLinks
        ]);
    }
}
