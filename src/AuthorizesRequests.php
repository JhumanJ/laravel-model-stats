<?php

namespace Jhumanj\LaravelModelStats;

use Closure;
use Illuminate\Http\Request;

trait AuthorizesRequests
{
    /**
     * The callback that should be used to authenticate ModelStats users.
     */
    public static Closure $authUsing;

    /**
     * Register the ModelStats authentication callback.
     */
    public static function auth(Closure $callback): static
    {
        static::$authUsing = $callback;

        return new static();
    }

    /**
     * Determine if the given request can access the ModelStats dashboard.
     */
    public static function check(Request $request): bool
    {
        return (static::$authUsing)($request);
    }
}
