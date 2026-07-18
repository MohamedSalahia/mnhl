<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('teacher.home');

    }// end of index

}//end of controller
