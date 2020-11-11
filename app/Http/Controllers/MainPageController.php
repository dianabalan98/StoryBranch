<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stories;

class MainPageController extends Controller
{
    //
    public function index()
    {
        return view('mainPage');
    }

}
