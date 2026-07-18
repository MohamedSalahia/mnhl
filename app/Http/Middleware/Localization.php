<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    public function handle(Request $request, Closure $next): Response
    {
        $segments = $request->segments();

        $firstSegment = array_shift($segments);

        if ($firstSegment == 'en' || $firstSegment == 'ar') {
            
            return redirect()->to(implode('/', $segments));
        }

        if (session('locale')) {

            app()->setLocale(session('locale'));

        } else if (auth()->check() && auth()->user()->locale) {

            app()->setLocale(auth()->user()->locale);

        } else {

            app()->setLocale(config('app.locale'));

            session(['locale' => app()->getLocale()]);

        }//end of else

        return $next($request);

    }//end of handle

}//end of middleware
