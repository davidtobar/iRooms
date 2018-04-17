<?php

namespace App\Http\Controllers;

use App\Meetings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Session;

class DashboardController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	    $this->middleware('auth');
	}

	public function saveState()
	{
	    //if no session then save as colaped
	    if (Session::has('sidebarState')) {
	        Session::remove('sidebarState');
	    } else {
	        //colapse sidebar
	        Session::put('sidebarState', 'sidebar-collapse');
	    }
	}

}