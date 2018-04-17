<?php

namespace App\Http\Controllers;

use App\Mail;
use App\Login;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	    $this->middleware('auth');
      $this->middleware('admin');
	}

	public function index(Request $req)
	{

    $mail = DB::table('mail')->first();

    $host = $mail->mail_host;
    $port = $mail->mail_port;
    $name = $mail->from_name;
    $email = $mail->smtp_email;
    $pass = $mail->password;

    $login = DB::table('login')->first();

    $main_logo = $login->logo;
    $main_favicon = $login->favicon;
    $main_descrip = $login->description;
    $main_name = $login->app_name;

		return view('core.pages.manage.settings', compact('host','port','email','name','pass','login','main_logo','main_descrip','main_name','main_favicon'));
	}

  public function create(Request $req)
  {
    $resp = [
      "state" => false
    ];

    $mail_host = $req->input("host");
    $mail_port = $req->input("port");
    $from_name = $req->input("name");
    $smtp_email = $req->input("email");
    $email_password = $req->input("pass");

    $validate = $req->validate([
      "host" => "required",       
      "port" => "required",       
      "name" => "required",    
      "email" => "required|email|max:255",
      "pass" => "required"
    ]);

    if (!isset($validate->errors)) {
      $resp["data"] = [
        "host" => $mail_host,       
        "port" => $mail_port,       
        "name" => $from_name,    
        "email" => $smtp_email,
        "pass" => $email_password
      ];

      $res = Mail::where('id', '>=', 1)->delete();

      $mail = new Mail();
      $mail->mail_host = $mail_host;
      $mail->mail_port = $mail_port;
      $mail->smtp_email = $smtp_email;
      $mail->password = $email_password;
      $mail->from_name = $from_name;
      $mail->save();

      $resp["state"] = true;
    } else {
      $resp["errors"] = $validate->errors;
    }

    return response()->json($resp);
  }


  public function mainCreate(Request $req)
  {
    $resp = [
      "state" => false
    ];

    $main_logo = $req->input("logo");
    $main_favicon = $req->input("favicon");
    $main_descrip = $req->input("descrip");
    $main_name = $req->input("name");

    $validate = $req->validate([
      "logo" => "required",       
      "descrip" => "required",
      "name" => "required",
      "favicon" => "required"
    ]);

    if (!isset($validate->errors)) {
      $resp["data"] = [
        "logo" => $main_logo,       
        "descrip" => $main_descrip,
        "name" => $main_name,
        "favicon" => $main_favicon
      ];

      $res = Login::where('id', '>=', 1)->delete();

      $login = new Login();
      $login->logo = $main_logo;
      $login->description = $main_descrip;
      $login->app_name = $main_name;
      $login->favicon = $main_favicon;
      $login->save();

      $resp["state"] = true;
    } else {
      $resp["errors"] = $validate->errors;
    }

    return response()->json($resp);
  }


}