<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Session;

use DB;

use Redirect;



use App\Http\Controllers\Controller;



class Logout extends Controller
{
	
	public function index()
	{
		if (Session::has('raj_user_id'))
		{
			Session::forget('raj_user_id');
		}
		
		Session::flush();
		$msg = array('success' => 'Logout Sucessfully');
		return redirect('/')->with($msg);
				
		
	}
}