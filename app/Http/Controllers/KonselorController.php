<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KonselorController extends Controller
{
    public function index()
    {
        return view('konselor.dashboard');
    }
}
