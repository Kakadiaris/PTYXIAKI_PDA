<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BarController extends Controller
{
       public function index()
    {
        return view('bar.dashboard');
    }
}
