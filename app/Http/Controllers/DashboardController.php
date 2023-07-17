<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('pages.dashboard.index', get_defined_vars());
    }
}

