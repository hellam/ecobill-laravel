<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if ($request->is('api/*')) {
            return response()->json(["authentication-failed"], 401);
        } else if ($request->is('admin/*')) {
            return route('admin.auth.login');
        } else if ($request->is('user/*')) {
            return route('user.auth.login');
        } else {
            return route('/');
        }
    }
}
