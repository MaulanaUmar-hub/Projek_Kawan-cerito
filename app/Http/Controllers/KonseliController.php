<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KonseliController extends Controller
{
    public function index()
    {
        return view('konseli.dashboard');
    }
}
