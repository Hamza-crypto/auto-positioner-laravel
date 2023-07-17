<?php

namespace App\Http\Controllers;

use App\Models\Position;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::all();
        return view('pages.positions.index', compact('positions'));
    }
}
