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

class Detail_work_report extends Controller
{
	public $table="inquiry";
	public $primary_id="inquiry_id";
	public $field = "inquiry_date";
	public $msgName = "Detail Work Report";
	public $view = "detail_work_report";
	public $controller = "Detail_work_report";
	public $module_name = "detail_work_report";
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

		if($this->role_id == 1)
		{
			$data['employee'] = Data_model::db_query("select * from `employee` where `delete_status` = 0 AND `zone_id` != '' AND (`role_id` = 4 OR `role_id`= 6 OR `role_id` = 11) ");
		}
		else{
			$data['employee'] = Data_model::db_query("select `employee`.*,`users`.emp_id from `employee` INNER JOIN `users` ON `users`.emp_id = `employee`.emp_id where `users`.id = '".$this->user_id."' AND `employee`.zone_id != '' AND (`employee`.role_id = 4 OR `employee`.role_id = 6 OR `employee`.role_id = 11) ");
		}


		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		return view($this->view.'/manage',$data);
	}

	public function get_search_data(Request $request)
	{
		$data['utility'] = $this->utility;
		$date  = date('Y-m-d',strtotime($request->input('date')));
		$previous_date = date('Y-m-d',strtotime($date." - 1 day"));
		$employee  = $request->input('employee');

		/* get designation */
		$get_emp_data = Data_model::retrive('employee','*',array('emp_id'=>$employee),'name','ASC');

		$get_user_id = Data_model::retrive('users','*',array('emp_id'=>$employee),'username','ASC');
		$user_id = $get_user_id[0]->id;


		$data['emp_detail'] = $get_emp_data;
		$zone = $get_emp_data[0]->zone_id;
		$role = $get_emp_data[0]->role_id;
		$get_zone_name = Data_model::db_query("select `zone_name` from `zone_master` where `zone_id` IN (".$zone.")");
		$emp_zone = array();
		foreach($get_zone_name as $k=>$v)
		{
			$emp_zone[] = $v->zone_name;
		}
		$data['emp_zone'] = implode(" / ",$emp_zone);

		if($role == '4' || $role == '6' || $role == '11')
		{
			$previous_pending_followup = Data_model::db_query("select count(*) as previous_pending_followup ,`inquiry`.`inquiry_id`,  `quatation`.`quatation_id`, `quatation`.`quatation_no`, `follow_up`.`follow_up_status` from `inquiry`  inner join `follow_up` on `follow_up`.`inquiry_id` = `inquiry`.`inquiry_id` inner join `quatation` on `quatation`.`inquiry_id` = `inquiry`.`inquiry_id` where `quatation`.zone_id IN (".$zone.") AND `follow_up`.next_followup_date <= '".$previous_date."' and `inquiry`.`order_status` = 0 and `inquiry`.`regret_status` = 0 AND `follow_up`.follow_up_status = 0 order by `follow_up`.`follow_up_id` desc  ");

			$data['previous_pending_followup'] = $previous_pending_followup[0]->previous_pending_followup;

			$total_follow_up = Data_model::db_query("select count(*) as total_follow_up ,`inquiry`.`inquiry_id`,  `quatation`.`quatation_id`, `quatation`.`quatation_no`, `follow_up`.`follow_up_status` from `inquiry`  inner join `follow_up` on `follow_up`.`inquiry_id` = `inquiry`.`inquiry_id` inner join `quatation` on `quatation`.`inquiry_id` = `inquiry`.`inquiry_id` where `quatation`.zone_id IN (".$zone.") AND `follow_up`.next_followup_date = '".$date."' and `inquiry`.`order_status` = 0 and `inquiry`.`regret_status` = 0 order by `follow_up`.`follow_up_id` desc");

			$data['total_follow_up'] = $total_follow_up[0]->total_follow_up;

			$pending_follow_up = Data_model::db_query("select count(*) as pending_follow_up ,`inquiry`.`inquiry_id`,  `quatation`.`quatation_id`, `quatation`.`quatation_no`, `follow_up`.`follow_up_status` from `inquiry` inner join `follow_up` on `follow_up`.`inquiry_id` = `inquiry`.`inquiry_id` inner join `quatation` on `quatation`.`inquiry_id` = `inquiry`.`inquiry_id` where `quatation`.zone_id IN (".$zone.") AND `follow_up`.next_followup_date = '".$date."'  and `follow_up`.follow_up_status = 0 and `inquiry`.`order_status` = 0 and `inquiry`.`regret_status` = 0 order by `follow_up`.`follow_up_id` desc");

			$data['pending_follow_up'] = $pending_follow_up[0]->pending_follow_up;

			$allotment_total = Data_model::db_query("select count(*) as allotment_total,`inquiry`.*, `quatation`.`quatation_id`, `quatation`.`quatation_no`, `quatation`.`follow_up_status` from `inquiry` inner join `quatation` on `quatation`.`inquiry_id` = `inquiry`.`inquiry_id` where `quatation`.zone_id IN (".$zone.") AND `quatation`.quatation_date = '".$date."'  and `inquiry`.`order_status` = 0 and `inquiry`.`regret_status` = 0");

			$data['allotment_total'] = $allotment_total[0]->allotment_total;

			$data['ehot_detail'] = Data_model::db_query("select `inquiry`.inquiry_no,`quatation`.quatation_no,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`product_master`.product_name,`follow_up`.call_receive_remark,`follow_up`.`next_followup_date`,`client_category_master`.client_category_name from `inquiry` INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id  INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `follow_up` ON `follow_up`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `client_category_master` ON `client_category_master`.client_category_id = `inquiry`.client_category_id where `follow_up`.follow_up_date = '".$date."' AND `follow_up`.added_by = '".$user_id."' AND `client_category_master`.client_category_id = 4 ");

			$data['elive_detail'] = Data_model::db_query("select `inquiry`.inquiry_no,`quatation`.quatation_no,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`product_master`.product_name,`follow_up`.call_receive_remark,`follow_up`.`next_followup_date`,`client_category_master`.client_category_name from `inquiry` INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id  INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `follow_up` ON `follow_up`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `client_category_master` ON `client_category_master`.client_category_id = `inquiry`.client_category_id where `follow_up`.follow_up_date = '".$date."' AND `follow_up`.added_by = '".$user_id."' AND `client_category_master`.client_category_id = 2 ");

			$data['hot_detail'] = Data_model::db_query("select `inquiry`.inquiry_no,`quatation`.quatation_no,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`product_master`.product_name,`follow_up`.call_receive_remark,`follow_up`.`next_followup_date`,`client_category_master`.client_category_name from `inquiry` INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id  INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `follow_up` ON `follow_up`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `client_category_master` ON `client_category_master`.client_category_id = `inquiry`.client_category_id where `follow_up`.follow_up_date = '".$date."' AND `follow_up`.added_by = '".$user_id."' AND `client_category_master`.client_category_id = 3 ");

			$data['live_detail'] = Data_model::db_query("select `inquiry`.inquiry_no,`quatation`.quatation_no,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`product_master`.product_name,`follow_up`.call_receive_remark,`follow_up`.`next_followup_date`,`client_category_master`.client_category_name from `inquiry` INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id  INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `follow_up` ON `follow_up`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `client_category_master` ON `client_category_master`.client_category_id = `inquiry`.client_category_id where `follow_up`.follow_up_date = '".$date."' AND `follow_up`.added_by = '".$user_id."' AND `client_category_master`.client_category_id = 1 ");

			$data['general_detail'] = Data_model::db_query("select `inquiry`.inquiry_no,`quatation`.quatation_no,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`product_master`.product_name,`follow_up`.call_receive_remark,`follow_up`.`next_followup_date`,`client_category_master`.client_category_name from `inquiry` INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id  INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `follow_up` ON `follow_up`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `client_category_master` ON `client_category_master`.client_category_id = `inquiry`.client_category_id where `follow_up`.follow_up_date = '".$date."' AND `follow_up`.added_by = '".$user_id."' AND `client_category_master`.client_category_id = 7 ");

			$data['prise_issue'] = Data_model::db_query("select `inquiry`.inquiry_no,`quatation`.quatation_no,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`product_master`.product_name,`prise_issue_notify`.remark from `inquiry` INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id  INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `prise_issue_notify` ON `prise_issue_notify`.inquiry_id = `inquiry`.inquiry_id where DATE(`prise_issue_notify`.added_date) = '".$date."' AND `prise_issue_notify`.added_by = '".$user_id."'  ");

			$data['order_book'] = Data_model::db_query("select `inquiry`.inquiry_no,`quatation`.quatation_no,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`product_master`.product_name,`order_book`.remark from `inquiry` INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id  INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `order_book` ON `order_book`.inquiry_id = `inquiry`.inquiry_id where `order_book`.order_book_date = '".$date."' AND `order_book`.added_by = '".$user_id."'  ");

			$data['postponed_list'] = Data_model::db_query("select `inquiry`.inquiry_no,`quatation`.quatation_no,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`product_master`.product_name,`follow_up`.call_receive_remark,`follow_up`.`next_followup_date`,`client_category_master`.client_category_name from `inquiry` INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id  INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `follow_up` ON `follow_up`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `client_category_master` ON `client_category_master`.client_category_id = `inquiry`.client_category_id where `follow_up`.follow_up_date = '".$date."' AND `follow_up`.added_by = '".$user_id."' AND `client_category_master`.client_category_id = 5 ");

			$data['regret_list'] = Data_model::db_query("select `inquiry`.inquiry_no,`quatation`.quatation_no,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`product_master`.product_name,`followup_regret`.regret_remark from `inquiry` INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id  INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `followup_regret` ON `followup_regret`.inquiry_id = `inquiry`.inquiry_id where `followup_regret`.date = '".$date."' AND `followup_regret`.added_by = '".$user_id."'  ");

		}

		$data['date'] = $date;
		$data['role'] = $role;
		return view($this->view.'/search_data',$data);
	}

}
