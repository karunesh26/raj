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

class Hot_list_report extends Controller
{
	public $table="inquiry";
	public $primary_id="inquiry_id";
	public $field = "inquiry_date";
	public $msgName = "Hot List Report";
	public $view = "hot_list_report";
	public $controller = "Hot_list_report";
	public $module_name = "hot_list_report";
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
		
		$data['source'] = Data_model::retrive('source_master','*',array('delete_status'=>0),'source_name','ASC');
		
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
		
		if($report_type == 'month'){
			$data['month'] = Data_model::db_query("SELECT `id`,MONTH(`from_date`) as `month` FROM `hot_list_notify` Group By `month` Order By `month` asc");
		}
		else if($report_type == 'week'){
			if($from_date != ''){
				$f_date = date('Y-m-d',strtotime($from_date));
				$t_date = date('Y-m-d',strtotime($to_date));
				$data['week_num'] = Data_model::db_query("select `week_no`,`from_date`,`to_date` from `hot_list_notify` where `from_date` >= '".$f_date."' AND `from_date` <= '".$t_date."' Group By `week_no` Order By `week_no` desc");
			}else{
				$data['week_num'] = Data_model::db_query("select `week_no`,`from_date`,`to_date` from `hot_list_notify` Group By `week_no` Order By `week_no` desc");
			}
		}
		else
		{
			$data['zone'] = Data_model::db_query("SELECT `hot_list_notify`.inquiry_id,`inquiry`.project_zone,`zone_master`.zone_name FROM `hot_list_notify` INNER JOIN `inquiry` ON `inquiry`.`inquiry_id` = `hot_list_notify`.`inquiry_id` INNER JOIN `zone_master` ON `zone_master`.zone_id = `inquiry`.project_zone Group By `project_zone`");
		}
		$data['from_date'] = $from_date;
		$data['to_date'] = $to_date;
		$data['report_type'] = $report_type;
		return view($this->view.'/search_data',$data);
	}
	
}
