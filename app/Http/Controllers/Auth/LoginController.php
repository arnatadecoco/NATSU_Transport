<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle redirection after authentication.
     *
     * @return string
     */
    public function redirectTo()
    {
        // Pastikan pengecekan is_admin sesuai dengan nilai di database (1)
        if (Auth::user()->is_admin == 1) {
            return route('root'); // Arahkan Admin ke dashboard
        }

        return route('profile'); // Arahkan User biasa ke profile
    }

    /**
     * The user has been authenticated.
     * * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Penambahan method ini sebagai backup redirect yang lebih kuat
        if ($user->is_admin == 1) {
            return redirect()->route('root');
        }

        return redirect()->route('profile');
    }
}