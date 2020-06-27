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

class Work_report extends Controller
{
	public $table="inquiry";
	public $primary_id="inquiry_id";
	public $field = "inquiry_date";
	public $msgName = "Work Report";
	public $view = "work_report";
	public $controller = "Work_report";
	public $module_name = "work_report";
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
			$data['employee'] = Data_model::db_query("select * from `employee` where `delete_status` = 0 AND `zone_id` != '' ");
		}
		else{
			$data['employee'] = Data_model::db_query("select `employee`.*,`users`.emp_id from `employee` INNER JOIN `users` ON `users`.emp_id = `employee`.emp_id where `users`.id = '".$this->user_id."' AND `employee`.zone_id != '' ");
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
		//dd($role);
		$get_zone_name = Data_model::db_query("select `zone_name` from `zone_master` where `zone_id` IN (".$zone.")");
		$emp_zone = array();
		foreach($get_zone_name as $k=>$v)
		{
			$emp_zone[] = $v->zone_name;
		}
		$data['emp_zone'] = implode(" / ",$emp_zone);

		if($role == '2' || $role == '3' || $role == '5')
		{
			/* previous pending inquiry */
			$previous_inq = Data_model::db_query("select count(*) as previous_pending_inq from `inquiry` where `first_quatation_id` = 0 AND `project_zone` IN (".$zone.") AND `delete_status` = 0 AND `remove_status`= 0 AND DATE(added_time) <= '".$previous_date."' ");
			$data['previous_pending_inq'] = $previous_inq[0]->previous_pending_inq;

			/* Inquiry Entry */
			$total_inq_per_day = Data_model::db_query("select count(*) as inquiry_total_per_day from `inquiry` where `added_by` = '".$user_id."' AND DATE(added_time) = '".$date."' ");
			$data['inquiry_total_per_day'] = $total_inq_per_day[0]->inquiry_total_per_day;

			/* Inquiry Allot */
			$alloted_inquiry = Data_model::db_query("select count(*) as inquiry_allot from `inquiry` where  DATE(added_time) = '".$date."' AND `project_zone` IN (".$zone.") ");
			$data['inquiry_allot'] = $alloted_inquiry[0]->inquiry_allot;

			/* Quotation Generate */
			$generated_quotation = Data_model::db_query("select count(*) as generate_quot from `quatation` where  DATE(added_time) = '".$date."' AND `added_by` = '".$user_id."' ");
			$data['generate_quot'] = $generated_quotation[0]->generate_quot;
			//dd($data['generate_quot']);
			/* Total Pending Inquiry */
			$total_pending_inquiry = Data_model::db_query("select count(*) as total_pending_inq from `inquiry` where `first_quatation_id` = 0 AND `project_zone` IN (".$zone.") AND `delete_status` = 0 AND `remove_status`= 0 AND DATE(added_time) <= '".$date."' ");
			$data['total_pending_inq'] = $total_pending_inquiry[0]->total_pending_inq;

			$data['quotation_detail'] = Data_model::db_query("select `quatation`.quatation_no,`quatation`.quatation_time,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`state_master`.`state_name`,`product_master`.product_name,`source_master`.source_name,`inquiry`.inquiry_id from `quatation` LEFT JOIN `inquiry` ON `inquiry`.inquiry_id = `quatation`.inquiry_id LEFT JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id LEFT JOIN `state_master` ON `state_master`.state_id = `customer_master`.state_id LEFT JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id LEFT JOIN `source_master` ON `source_master`.source_id = `inquiry`.source_id  where `quatation`.quatation_date = '".$date."' AND `quatation`.added_by = '".$user_id."' ");
			//dd($data['quotation_detail']);

			$data['revise_quotation_detail'] = Data_model::db_query("select `revise_quatation`.revise_quatation_no,`revise_quatation`.revise_time,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`state_master`.`state_name`,`product_master`.product_name,`source_master`.source_name,`inquiry`.remarks from `revise_quatation` INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `revise_quatation`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id INNER JOIN `state_master` ON `state_master`.state_id = `customer_master`.state_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `source_master` ON `source_master`.source_id = `inquiry`.source_id where `revise_quatation`.revise_date = '".$date."' AND `revise_quatation`.added_by = '".$user_id."' ");

			$data['cancel_inquiry_detail'] = Data_model::db_query("select `inquiry`.inquiry_no,`inquiry`.cancel_reason,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`state_master`.`state_name`,`product_master`.product_name,`source_master`.source_name,`inquiry`.inquiry_id from `inquiry` INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id INNER JOIN `state_master` ON `state_master`.state_id = `customer_master`.state_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `source_master` ON `source_master`.source_id = `inquiry`.source_id  where `inquiry`.cancel_date = '".$date."' AND `inquiry`.delete_status = '".$user_id."' ");

			$data['pending_inquiry'] = Data_model::db_query("select `inquiry`.inquiry_no,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`state_master`.`state_name`,`source_master`.source_name,`inquiry`.inquiry_id,`inquiry_remark`.remark as inq_remark,`inquiry_remark`.added_date as inquiry_time from `inquiry` INNER JOIN `inquiry_remark` ON `inquiry_remark`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id INNER JOIN `state_master` ON `state_master`.state_id = `customer_master`.state_id INNER JOIN `source_master` ON `source_master`.source_id = `inquiry`.source_id where `inquiry_remark`.date = '".$date."' AND `inquiry_remark`.added_by = '".$user_id."' ");
		}
		else if($role == '4' || $role == '6' || $role == '11')
		{
			$previous_pending_followup = Data_model::db_query("select count(*) as previous_pending_followup ,`inquiry`.`inquiry_id`,  `quatation`.`quatation_id`, `quatation`.`quatation_no`, `follow_up`.`follow_up_status` from `inquiry`  inner join `follow_up` on `follow_up`.`inquiry_id` = `inquiry`.`inquiry_id` inner join `quatation` on `quatation`.`inquiry_id` = `inquiry`.`inquiry_id` where `quatation`.zone_id IN (".$zone.") AND `follow_up`.next_followup_date <= '".$previous_date."' and `inquiry`.`order_status` = 0 and `inquiry`.`regret_status` = 0 AND `follow_up`.follow_up_status = 0 order by `follow_up`.`follow_up_id` desc ");

			$data['previous_pending_followup'] = $previous_pending_followup[0]->previous_pending_followup;

			$total_follow_up = Data_model::db_query("select count(*) as total_follow_up ,`inquiry`.`inquiry_id`,  `quatation`.`quatation_id`, `quatation`.`quatation_no`, `follow_up`.`follow_up_status` from `inquiry`  inner join `follow_up` on `follow_up`.`inquiry_id` = `inquiry`.`inquiry_id` inner join `quatation` on `quatation`.`inquiry_id` = `inquiry`.`inquiry_id` where `quatation`.zone_id IN (".$zone.") AND `follow_up`.next_followup_date = '".$date."' and `inquiry`.`order_status` = 0 and `inquiry`.`regret_status` = 0 order by `follow_up`.`follow_up_id` desc");

			$data['total_follow_up'] = $total_follow_up[0]->total_follow_up;

			$pending_follow_up = Data_model::db_query("select count(*) as pending_follow_up ,`inquiry`.`inquiry_id`,  `quatation`.`quatation_id`, `quatation`.`quatation_no`, `follow_up`.`follow_up_status` from `inquiry` inner join `follow_up` on `follow_up`.`inquiry_id` = `inquiry`.`inquiry_id` inner join `quatation` on `quatation`.`inquiry_id` = `inquiry`.`inquiry_id` where `quatation`.zone_id IN (".$zone.") AND `follow_up`.next_followup_date = '".$date."'  and `follow_up`.follow_up_status = 0 and `inquiry`.`order_status` = 0 and `inquiry`.`regret_status` = 0 order by `follow_up`.`follow_up_id` desc");

			$data['pending_follow_up'] = $pending_follow_up[0]->pending_follow_up;

			$allotment_total = Data_model::db_query("select count(*) as allotment_total,`inquiry`.*, `quatation`.`quatation_id`, `quatation`.`quatation_no`, `quatation`.`follow_up_status` from `inquiry` inner join `quatation` on `quatation`.`inquiry_id` = `inquiry`.`inquiry_id` where `quatation`.zone_id IN (".$zone.") AND `quatation`.quatation_date = '".$date."'  and `inquiry`.`order_status` = 0 and `inquiry`.`regret_status` = 0");

			$data['allotment_total'] = $allotment_total[0]->allotment_total;

			$data['ehot_detail'] = Data_model::db_query("select `inquiry`.inquiry_no,`quatation`.quatation_no,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`product_master`.product_name,`follow_up`.call_receive_remark,`client_category_master`.client_category_name from `inquiry` INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id  INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `follow_up` ON `follow_up`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `client_category_master` ON `client_category_master`.client_category_id = `inquiry`.client_category_id where `follow_up`.follow_up_date = '".$date."' AND `follow_up`.added_by = '".$user_id."' AND `client_category_master`.client_category_id = 4 ");

			$data['elive_detail'] = Data_model::db_query("select `inquiry`.inquiry_no,`quatation`.quatation_no,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`product_master`.product_name,`follow_up`.call_receive_remark,`client_category_master`.client_category_name from `inquiry` INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id  INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `follow_up` ON `follow_up`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `client_category_master` ON `client_category_master`.client_category_id = `inquiry`.client_category_id where `follow_up`.follow_up_date = '".$date."' AND `follow_up`.added_by = '".$user_id."' AND `client_category_master`.client_category_id = 2 ");

			$data['prise_issue'] = Data_model::db_query("select `inquiry`.inquiry_no,`quatation`.quatation_no,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`product_master`.product_name,`prise_issue_notify`.remark from `inquiry` INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id  INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `prise_issue_notify` ON `prise_issue_notify`.inquiry_id = `inquiry`.inquiry_id where DATE(`prise_issue_notify`.added_date) = '".$date."' AND `prise_issue_notify`.added_by = '".$user_id."'  ");

			$data['order_book'] = Data_model::db_query("select `inquiry`.inquiry_no,`quatation`.quatation_no,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`product_master`.product_name,`order_book`.remark from `inquiry` INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id  INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `order_book` ON `order_book`.inquiry_id = `inquiry`.inquiry_id where `order_book`.order_book_date = '".$date."' AND `order_book`.added_by = '".$user_id."'  ");

			$data['regret_list'] = Data_model::db_query("select `inquiry`.inquiry_no,`quatation`.quatation_no,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`product_master`.product_name,`followup_regret`.regret_remark from `inquiry` INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id  INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `followup_regret` ON `followup_regret`.inquiry_id = `inquiry`.inquiry_id where `followup_regret`.date = '".$date."' AND `followup_regret`.added_by = '".$user_id."'  ");

			$general_count = Data_model::db_query("select count(*) as general_client_category , `inquiry`.inquiry_no,`quatation`.quatation_no,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`product_master`.product_name,`follow_up`.call_receive_remark,`client_category_master`.client_category_name from `inquiry` INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id  INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `follow_up` ON `follow_up`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `client_category_master` ON `client_category_master`.client_category_id = `inquiry`.client_category_id where `follow_up`.follow_up_date = '".$date."' AND `follow_up`.added_by = '".$user_id."' AND `client_category_master`.client_category_id = 7 ");

			$data['general_client_category'] = $general_count[0]->general_client_category;

			$hot_count = Data_model::db_query("select count(*) as hot_client_category , `inquiry`.inquiry_no,`quatation`.quatation_no,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`product_master`.product_name,`follow_up`.call_receive_remark,`client_category_master`.client_category_name from `inquiry` INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id  INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `follow_up` ON `follow_up`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `client_category_master` ON `client_category_master`.client_category_id = `inquiry`.client_category_id where `follow_up`.follow_up_date = '".$date."' AND `follow_up`.added_by = '".$user_id."' AND `client_category_master`.client_category_id = 3 ");

			$data['hot_client_category'] = $hot_count[0]->hot_client_category;

			$live_count = Data_model::db_query("select count(*) as live_client_category , `inquiry`.inquiry_no,`quatation`.quatation_no,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`product_master`.product_name,`follow_up`.call_receive_remark,`client_category_master`.client_category_name from `inquiry` INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id  INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `follow_up` ON `follow_up`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `client_category_master` ON `client_category_master`.client_category_id = `inquiry`.client_category_id where `follow_up`.follow_up_date = '".$date."' AND `follow_up`.added_by = '".$user_id."' AND `client_category_master`.client_category_id = 3 ");

			$data['live_client_category'] = $live_count[0]->live_client_category;

			$posponed_count = Data_model::db_query("select count(*) as posponed_client_category , `inquiry`.inquiry_no,`quatation`.quatation_no,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`product_master`.product_name,`follow_up`.call_receive_remark,`client_category_master`.client_category_name from `inquiry` INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id  INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `follow_up` ON `follow_up`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `client_category_master` ON `client_category_master`.client_category_id = `inquiry`.client_category_id where `follow_up`.follow_up_date = '".$date."' AND `follow_up`.added_by = '".$user_id."' AND `client_category_master`.client_category_id = 5 ");

			$data['posponed_client_category'] = $posponed_count[0]->posponed_client_category;
		}

		$data['date'] = $date;
		$data['role'] = $role;
		return view($this->view.'/search_data',$data);
	}

}
