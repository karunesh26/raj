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

class Regret_report extends Controller
{
	public $table="followup_regret";
	public $primary_id="id";
	public $field = "date";
	public $msgName = "Regret Report";
	public $view = "regret_report";
	public $controller = "Regret_report";
	public $module_name = "regret_report";
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
		
		if($report_type == 'date'){
			if($from_date != ''){
				$f_date = date('Y-m-d',strtotime($from_date));
				$t_date = date('Y-m-d',strtotime($to_date));
				
				$data['result'] = Data_model::db_query("select `followup_regret`.*,`inquiry`.project_value,`quatation`.quatation_no,`quatation`.quatation_id,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`customer_master`.email,`customer_master`.email_2,`country_master`.country_name,`state_master`.state_name,`city_master`.city_name,`product_master`.product_name from `followup_regret` INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `followup_regret`.inquiry_id INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id LEFT JOIN `country_master` ON `country_master`.country_id = `customer_master`.country_id LEFT JOIN `state_master` ON `state_master`.state_id = `customer_master`.state_id LEFT JOIN `city_master` ON `city_master`.city_id = `customer_master`.city_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id where `followup_regret`.date >= '".$f_date."' AND `followup_regret`.date <= '".$t_date."' ");
			}else{
				$data['result'] = Data_model::db_query("select `followup_regret`.*,`inquiry`.project_value,`quatation`.quatation_no,`quatation`.quatation_id,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`customer_master`.email,`customer_master`.email_2,`country_master`.country_name,`state_master`.state_name,`city_master`.city_name,`product_master`.product_name from `followup_regret` INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `followup_regret`.inquiry_id INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id LEFT JOIN `country_master` ON `country_master`.country_id = `customer_master`.country_id LEFT JOIN `state_master` ON `state_master`.state_id = `customer_master`.state_id LEFT JOIN `city_master` ON `city_master`.city_id = `customer_master`.city_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id ");
			}
		}
		else if($report_type == 'month'){
			$data['month'] = Data_model::db_query("SELECT `id`,MONTH(`date`) as `month` FROM `followup_regret` Group By `month` Order By `month` asc");
		}
		else
		{
			$data['state'] = Data_model::db_query("select `followup_regret`.id,`inquiry`.inquiry_id,`customer_master`.state_id,`state_master`.state_name from `followup_regret` INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `followup_regret`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.`customer_id` INNER JOIN `state_master` ON `state_master`.state_id = customer_master.state_id Group By `state_id`");
		}
		$data['from_date'] = $from_date;
		$data['to_date'] = $to_date;
		$data['report_type'] = $report_type;
		return view($this->view.'/search_data',$data);
	}
	
}
