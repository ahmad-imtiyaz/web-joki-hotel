<?php
// File: app/Http/Controllers/Auth/LoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // Override username method to use 'username' instead of 'email'
    public function username()
    {
        return 'username';
    }

    // Override credentials to check is_active status
    protected function credentials(Request $request)
    {
        return array_merge($request->only($this->username(), 'password'), ['is_active' => true]);
    }

    // Redirect after login based on role
    protected function redirectTo()
    {
        return '/dashboard';
    }
}
