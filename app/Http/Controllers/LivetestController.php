<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LivetestController extends Controller
{
    //

    public function index() : View
    {
        return view('live-test.index');
    }
}
