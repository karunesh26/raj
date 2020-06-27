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

class Order_book_report extends Controller
{
	public $table="order_book";
	public $primary_id="order_id";
	public $field = "order_book_date";
	public $msgName = "Order Book Report";
	public $view = "order_book_report";
	public $controller = "Order_book_report";
	public $module_name = "order_book_report";
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

		if($report_type == 'month'){
			$data['month'] = Data_model::db_query("SELECT `order_id`,MONTH(`order_book_date`) as `month` FROM `order_book` Group By `month` Order By `month` asc");
		}
		else if($report_type == 'state'){
			$data['state'] = Data_model::db_query("select `order_book`.order_id,`inquiry`.inquiry_id,`customer_master`.state_id,`state_master`.state_name from `order_book` INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `order_book`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.`customer_id` INNER JOIN `state_master` ON `state_master`.state_id = customer_master.state_id Group By `state_id`");
		}
		else if($report_type == 'source_wise'){
			$data['source'] = Data_model::retrive('source_master','*',array('delete_status'=>0),'source_name','ASC');
		}
		else
		{
			$data['order_by'] = Data_model::db_query("select `order_book`.order_by,`employee`.name from `order_book` INNER JOIN `employee` ON `employee`.emp_id = `order_book`.order_by Group By `order_by` ");
		}
		$data['from_date'] = $from_date;
		$data['to_date'] = $to_date;
		$data['report_type'] = $report_type;
		return view($this->view.'/search_data',$data);
	}

}
