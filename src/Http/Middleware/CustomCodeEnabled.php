<?php


namespace Jhumanj\LaravelModelStats\Http\Middleware;

class CustomCodeEnabled
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        if (! config('model-stats.allow_custom_code')) {
            return response([
                'message' => 'Custom code not enabled.',
            ], 403);
        }

        return $next($request);
    }
}
