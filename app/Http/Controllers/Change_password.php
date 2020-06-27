<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Data_model;
use App\Libraries\Utility;

use Session;
use DB;
use Redirect;
use Validator;


//set_error_handler(null);
//set_exception_handler(null);
class Change_password extends Controller
{

	public $utility;
	public $controller = "Change_password";
	public $msgName = "Change Password";
	public function __construct()
    {
		
		
		if (!Session::has('raj_user_id')) 
		{
			
			
				$msg = array('error' => 'You Must First Login To Access');
				 Redirect::to('/')->send()->with($msg);
			
			
		}
		
		
		
		
		date_default_timezone_set("Asia/Kolkata");
		$this->utility = new Utility();
		
	}
	
	public function index()
	{
			  
	
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		
		return view('change_password/form',$data);
	}
	

	public function update(Request $request)
	{
		
		$id = $request->input('user_id');
		$new_pwd = $request->input('new_password');
		$old_password = md5($request->input('old_password'));
		$new_password = md5($request->input('new_password'));
		
		$check = Data_model::retrive('users','*',array('id'=>$id,'password'=>$old_password),'id');
		if(!empty($check))
		{
			if( Data_model::restore('users',array('password'=>$new_password),array('id'=>$id,'password'=>$old_password)))
			{
				Data_model::restore('employee',array('password'=>$new_pwd),array('emp_id'=>$check[0]->emp_id));
				$msg = array('success' => $this->msgName.' Updated Sucessfully');
			}
			else
			{
				$msg = array('error' => 'Some Problem ... Please Try Again');
			}
		}
		else
		{
			$msg = array('error' => 'Old Password Does Not Match');
			
			
		}
		
		return redirect($this->controller)->with($msg);
	}
	
	
}
