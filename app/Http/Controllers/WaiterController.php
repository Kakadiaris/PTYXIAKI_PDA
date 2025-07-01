<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WaiterController extends Controller
{
       public function index()
    {
        return view('waiter.dashboard');
    }
}
