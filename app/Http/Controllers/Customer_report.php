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
use PDF;

class Customer_report extends Controller
{
	public $table="customer_master";
	public $primary_id="customer_id";
	public $field = "date";
	public $msgName = "Customer Report";
	public $view = "customer_report";
	public $controller = "Customer_report";
	public $module_name = "customer_report";
	public $utility;
	public $role_id;
	public $user_id;
	public function __construct()
    {
		if(!Session::has('raj_user_id')) 
		{
			$msg = array('error' => 'You Must First Login To Access');
			Redirect::to('/')->send()->with($msg);
		}
		$this->role_id = Session::get('raj_role_id');
		$this->user_id = Session::get('raj_user_id');
		
		if($this->role_id != '1')
		{
			$permission = Data_model::get_permission($this->module_name);
			if(empty($permission))
			{
				Redirect::to('/')->send();
			}
		}
		
		date_default_timezone_set("Asia/Kolkata");
		$this->utility = new Utility();
		
	}
	
	public function index()
	{
		$data['role_id'] = $this->role_id;
		$data['utility'] = $this->utility;
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		return view($this->view.'/manage',$data);
	}
	
	public function get_search_data(Request $request)
	{
		$data['utility'] = $this->utility;
		$from_date  = $request->input('from_date');
		$to_date  = $request->input('to_date');
		$report_type  = $request->input('report_type');
		
		if($report_type == 'state'){
			$data['state'] = Data_model::db_query("SELECT * FROM `state_master` where `delete_status` = 0");
		}
		else
		{
			$data['zone'] = Data_model::db_query("select * from `zone_master` where `delete_status` = 0 ");
		}
		$data['from_date'] = $from_date;
		$data['to_date'] = $to_date;
		$data['report_type'] = $report_type;
		return view($this->view.'/search_data',$data);
	}
	
}
