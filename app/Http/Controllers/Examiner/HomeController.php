<?php

namespace App\Http\Controllers\Examiner;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('examiner.home');

    }// end of index

}//end of controller
