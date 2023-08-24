<?php

namespace Jhumanj\LaravelModelStats;

use Closure;
use Illuminate\Http\Request;

trait AuthorizesRequests
{
    /**
     * The callback that should be used to authenticate ModelStats users.
     *
     * @var \Closure
     */
    public static Closure $authUsing;

    /**
     * Register the ModelStats authentication callback.
     *
     * @param \Closure $callback
     *
     * @return static
     */
    public static function auth(Closure $callback): static
    {
        static::$authUsing = $callback;

        return new static;
    }

    /**
     * Determine if the given request can access the ModelStats dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    public static function check(Request $request): bool
    {
        return (static::$authUsing)($request);
    }
}
