<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;
use App\Traits\GlobalResponseTrait;

class Authenticate extends Middleware
{
    use GlobalResponseTrait;
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        
        return $request->expectsJson() ? null : route('login');
    }
}
