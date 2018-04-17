<?php

namespace App\Http\Controllers;

use App\User;
use App\Meetings;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserAccountController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	    $this->middleware('auth');
      $this->middleware('user');
	}

	public function myAccount(Request $req)
	{
		$name = Auth::user()->name;
		$telephone = Auth::user()->phone;
		$email = Auth::user()->email;

    $login = DB::table('login')->first();

		return view('core.pages.account.user.my_account', compact('email','name','telephone','login'));
	}

	public function editProfile(Request $req)
	{
		$resp = [
        "state" => false
      ];

      $profile_name = $req->input("name");
      $profile_phone = $req->input("phone");
      $profile_email = $req->input("email");

      $validate = $req->validate([
        "name" => "required",       
        "phone" => "required",    
        "email" => "required"
      ]);

      if (!isset($validate->errors)) {
        $resp["data"] = [
          "name" => $profile_name,       
          "phone" => $profile_phone,       
          "email" => $profile_email
        ];

        $userId = Auth::id();

        $user = User::find($userId);
        $user->name = $profile_name;
        $user->email = $profile_email;
        $user->phone = $profile_phone;
        $user->save();

        $resp["state"] = true;
      } else {
        $resp["errors"] = $validate->errors;
      }

      return response()->json($resp);
	}

	public function changePassword(Request $request){

		if (!(\Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your old password does not matches with the password you provided.");
        }
 
        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){

            return redirect()->back()->with("error","New Password cannot be same as your old password.");
        }
 
        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);
 
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();
 
        return redirect()->back()->with("success","Password updated successfully.");

	}

}