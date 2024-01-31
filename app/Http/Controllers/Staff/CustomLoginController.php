<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class CustomLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('staff.custom-login'); // 'staff.custom-login' is the name of your Blade view file
    }

    public function login(Request $request)
    {
       
$input=['username' => request('username'), 'password' => request('password')];
        
        if(auth()->guard('staff')->attempt($input,true)){
         return redirect()->route('custom-dashboard');
        
        } else {
            // Authentication failed
             return redirect()->route('custom.login');
        }
    }
	public function index()
	{
		 return view('staff.custom-dashboard');
	}
	
	  public function logout(Request $request) {
       
       auth()->guard('staff')->logout();
	     return redirect()->route('custom.login');
     }
}
