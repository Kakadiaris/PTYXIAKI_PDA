<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function index()
    {
        $items = \App\Models\MenuItem::all();
        return view('menu.index', compact('items'));
    }
}
