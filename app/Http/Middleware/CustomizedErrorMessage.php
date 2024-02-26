<?php

namespace App\Http\Middleware;

use App\Services\CustomizedErrorService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomizedErrorMessage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        dump('-----middleware----');
        $ss = app()->make("CES");
        $res =  $next($request);
        dump($ss->showError());
        return $res;
    }
}
