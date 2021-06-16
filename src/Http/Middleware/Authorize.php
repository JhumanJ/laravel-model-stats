<?php


namespace Jhumanj\LaravelModelStats\Http\Middleware;


use Jhumanj\LaravelModelStats\LaravelModelStats;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return LaravelModelStats::check($request) ? $next($request) : abort(403);
    }
}
