<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
public function username()
{
    return 'username';
}
    protected function authenticated(Request $request, $user)
    {   
        if ($user->role == 1) {
            // User has role 1 (admin)
            return redirect()->route('admin.dashboard');
        }  else if ($user->role == 2) {
            // User has role 1 (admin)
            return redirect()->route('agent.dashboard');
        }  else if ($user->role == 3) {
            // User has role 1 (admin)
            return redirect()->route('sub.dashboard');
        } else {
			
            return redirect()->route('home');
        }
    }
}