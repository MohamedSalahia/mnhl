<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class HomeController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth',),
        ];

    }// end of middleware

    public function index()
    {
        return view('home');
    }

}//end of controller
