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

class Inquiry_report extends Controller
{
	public $table="inquiry";
	public $primary_id="inquiry_id";
	public $field = "inquiry_date";
	public $msgName = "Inquiry Report";
	public $view = "inquiry_report";
	public $controller = "Inquiry_report";
	public $module_name = "inquiry_report";
	public $utility;
	public $role_id;
	public $user_id;
	public function __construct()
    {
		if (!Session::has('raj_user_id'))
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

		$data['source'] = Data_model::retrive('source_master','*',array('delete_status'=>0),'source_name','ASC');
		$data['state'] = Data_model::retrive('state_master','*',array('delete_status'=>0),'state_name','ASC');

		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		return view($this->view.'/manage',$data);
	}

	public function get_search_data(Request $request)
	{
		$state  = $request->input('state');
		$source  = $request->input('source');

		if(! empty($state)){
			if(in_array('all',$state)){
				$data['state'] = Data_model::retrive('state_master','*',array('delete_status'=>0),'state_name','ASC');
			}else{
				$state_id = implode(",",$state);
				$data['state'] = Data_model::db_query("select * from `state_master` where `state_id` IN (".$state_id.") ");
			}
		}else{
			$data['state'] = array();
		}

		$f_date = $request->input('from_date');
		$t_date = $request->input('to_date');
		$from_date  = date('Y-m-d',strtotime($request->input('from_date')));
		$to_date  = date('y-m-d',strtotime($request->input('to_date')));
		$data['utility'] = $this->utility;

		$data['source'] = $source;

		$data['from_date'] = $from_date;
		$data['to_date'] = $to_date;

		if($f_date != '' && $t_date != '')
		{
			$datesArray = array();
			$total_days = round(abs(strtotime($to_date) - strtotime($from_date)) / 86400, 0) + 1;
			$format="Y-m-d";
			for($day=0; $day<$total_days; $day++)
			{
				$datesArray[] = date($format, strtotime("{$from_date} + {$day} days"));
			}
			$data['date_arr'] = $datesArray;
		}
		else
		{
			$data['date_arr'] = array();
		}

		return view($this->view.'/search_data',$data);
	}

}
