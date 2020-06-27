<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Data_model;
use App\Libraries\Utility;

use Session;
use DB;
use Redirect;
use Validator;

class Dashboard extends Controller
{
	public $controller = "Dashboard";
	public $utility;
	public $role_id;
	public $user_id;
	public $zone_id;

	public function __construct()
    {

		if (!Session::has('raj_user_id'))
		{
			$msg = array('error' => 'You Must First Login To Access');
			 Redirect::to('/admin')->send()->with($msg);
		}
		$this->role_id = Session::get('raj_role_id');
		$this->user_id = Session::get('raj_user_id');
		$this->zone_id = Session::get('raj_zone_id');


		date_default_timezone_set("Asia/Kolkata");
		$this->utility = new Utility();

	}

	public function index()
	{
		if($this->role_id == 1)
		{
			$data['emp_work_detail'] = Data_model::db_query("select `users`.id,`employee`.role_id,`employee`.zone_id,`employee`.name from `users` INNER JOIN `employee` ON `employee`.emp_id = `users`.emp_id where `employee`.delete_status = 0 AND `zone_id` != '' AND `users`.role_id IN (2,3,5) order by `employee`.name asc ");

			$data['sales_emp_detail'] = Data_model::db_query("select `users`.id,`employee`.role_id,`employee`.zone_id,`employee`.name from `users` INNER JOIN `employee` ON `employee`.emp_id = `users`.emp_id where `employee`.delete_status = 0 AND `zone_id` != '' AND `users`.role_id IN (4,6,11) order by `employee`.name asc ");

			$data['revise_notify'] =  DB::table('revise_quotation_notify')
				->join('inquiry', 'inquiry.inquiry_id', '=','revise_quotation_notify.inquiry_id')
				->join('customer_master', 'customer_master.customer_id', '=','inquiry.customer_id')
				->join('product_master', 'product_master.product_id', '=','inquiry.product_id')
				->leftJoin('follow_up', 'follow_up.inquiry_id', '=','inquiry.inquiry_id')
				->join('users as u1', 'u1.id', '=','revise_quotation_notify.added_by')
				->join('employee', 'employee.emp_id', '=','revise_quotation_notify.emp_id')
				->select('revise_quotation_notify.*','product_master.product_name','customer_master.name','customer_master.prefix','u1.username as follow_up_by','employee.name as allot_to','follow_up.added_by')
				->where('revise_quotation_notify.clear',0)
				->orderBy('id','DESC')
				->groupBy('follow_up.inquiry_id')
				->get();

			$data['prise_issue_notify'] =  DB::table('prise_issue_notify')
				->join('inquiry', 'inquiry.inquiry_id', '=','prise_issue_notify.inquiry_id')
				->join('customer_master', 'customer_master.customer_id', '=','inquiry.customer_id')
				->join('product_master', 'product_master.product_id', '=','inquiry.product_id')
				->leftJoin('follow_up', 'follow_up.inquiry_id', '=','inquiry.inquiry_id')
				->join('users as u1', 'u1.id', '=','prise_issue_notify.added_by')
				->join('employee', 'employee.emp_id', '=','prise_issue_notify.emp_id')
				->select('prise_issue_notify.*','product_master.product_name','customer_master.name','customer_master.prefix','u1.username as follow_up_by','employee.name as allot_to')
				->where('prise_issue_notify.clear',0)
				->orderBy('id','DESC')
				->groupBy('follow_up.inquiry_id')
				->get();


		}
		else
		{
			/* get emp_id in users */
			$emp_id_data = Data_model::retrive('users','*',array('id'=>$this->user_id),'emp_id','DESC');
			$date = date('Y-m-d');
			$previous_date = date('Y-m-d',strtotime($date." - 1 day"));
			if(! empty($emp_id_data))
			{
				$employee_id = $emp_id_data[0]->emp_id;
			}
			else
			{
				$employee_id = 0;
			}
			$data['emp_detail'] = Data_model::retrive('employee','*',array('emp_id'=>$employee_id),'emp_id','DESC');

			if($this->role_id == '2' || $this->role_id == '3' || $this->role_id == '5')
			{
				/* work Detail */

				/* previous pending inquiry */
				$previous_inq = Data_model::db_query("select count(*) as previous_pending_inq from `inquiry` where `first_quatation_id` = 0 AND `project_zone` IN (".$this->zone_id.") AND `delete_status` = 0 AND `remove_status`= 0 AND DATE(added_time) <= '".$previous_date."' ");
				$data['previous_pending_inq'] = $previous_inq[0]->previous_pending_inq;

				/* Inquiry Entry */
				$total_inq_per_day = Data_model::db_query("select count(*) as inquiry_total_per_day from `inquiry` where `added_by` = '".$this->user_id."' AND DATE(added_time) = '".$date."' ");
				$data['inquiry_total_per_day'] = $total_inq_per_day[0]->inquiry_total_per_day;

				/* Inquiry Allot */
				$alloted_inquiry = Data_model::db_query("select count(*) as inquiry_allot from `inquiry` where  DATE(added_time) = '".$date."' AND `project_zone` IN (".$this->zone_id.") ");
				$data['inquiry_allot'] = $alloted_inquiry[0]->inquiry_allot;

				/* inquiry call */
				$call_inquiry = Data_model::db_query("select count(*) as call_inquiry from `inquiry_remark` where  date = '".$date."' AND `added_by`='".$this->user_id."' ");
				$data['call_inquiry'] = $call_inquiry[0]->call_inquiry;

				/* Quotation Generate */
				$generated_quotation = Data_model::db_query("select count(*) as generate_quot from `quatation` where  DATE(added_time) = '".$date."' AND `added_by` = '".$this->user_id."' ");
				$data['generate_quot'] = $generated_quotation[0]->generate_quot;

				/* Total Pending Inquiry */
				$total_pending_inquiry = Data_model::db_query("select count(*) as total_pending_inq from `inquiry` where `first_quatation_id` = 0 AND `project_zone` IN (".$this->zone_id.") AND `delete_status` = 0 AND `remove_status`= 0 AND DATE(added_time) <= '".$date."' ");
				$data['total_pending_inq'] = $total_pending_inquiry[0]->total_pending_inq;

				/* Total Revise Quotation */
				$total_revise_quotation = Data_model::db_query("select count(*) as total_revise_quotation from `revise_quatation` where DATE(added_time) = '".$date."' AND `added_by`= '".$this->user_id."' ");
				$data['total_revise_quotation'] = $total_revise_quotation[0]->total_revise_quotation;

				/* cancel inquiry */
				$cancel_inq = Data_model::db_query("select count(*) as cancel_inq from `inquiry` where `delete_status` = '".$this->user_id."' AND cancel_date ='".$date."' ");
				$data['cancel_inq'] = $cancel_inq[0]->cancel_inq;
			}
			if($this->role_id == '4' || $this->role_id == '6' || $this->role_id == '11')
			{
				/* Previous Pending Follow-up */
				$previous_pending_followup = Data_model::db_query("select count(*) as previous_pending_followup ,`inquiry`.`inquiry_id`,  `quatation`.`quatation_id`, `quatation`.`quatation_no`, `follow_up`.`follow_up_status` from `inquiry`  inner join `follow_up` on `follow_up`.`inquiry_id` = `inquiry`.`inquiry_id` inner join `quatation` on `quatation`.`inquiry_id` = `inquiry`.`inquiry_id` where `quatation`.zone_id IN (".$this->zone_id.") AND `follow_up`.next_followup_date <= '".$previous_date."' and `inquiry`.`order_status` = 0 and `inquiry`.`regret_status` = 0 AND `follow_up`.follow_up_status = 0 order by `follow_up`.`follow_up_id` desc ");

				$data['previous_pending_followup'] = $previous_pending_followup[0]->previous_pending_followup;

				$total_follow_up = Data_model::db_query("select count(*) as total_follow_up ,`inquiry`.`inquiry_id`,  `quatation`.`quatation_id`, `quatation`.`quatation_no`, `follow_up`.`follow_up_status` from `inquiry`  inner join `follow_up` on `follow_up`.`inquiry_id` = `inquiry`.`inquiry_id` inner join `quatation` on `quatation`.`inquiry_id` = `inquiry`.`inquiry_id` where `quatation`.zone_id IN (".$this->zone_id.") AND `follow_up`.next_followup_date = '".$date."' and `inquiry`.`order_status` = 0 and `inquiry`.`regret_status` = 0 order by `follow_up`.`follow_up_id` desc");

				$data['total_follow_up'] = $total_follow_up[0]->total_follow_up;

				$pending_follow_up = Data_model::db_query("select count(*) as pending_follow_up ,`inquiry`.`inquiry_id`,  `quatation`.`quatation_id`, `quatation`.`quatation_no`, `follow_up`.`follow_up_status` from `inquiry` inner join `follow_up` on `follow_up`.`inquiry_id` = `inquiry`.`inquiry_id` inner join `quatation` on `quatation`.`inquiry_id` = `inquiry`.`inquiry_id` where `quatation`.zone_id IN (".$this->zone_id.") AND `follow_up`.next_followup_date = '".$date."'  and `follow_up`.follow_up_status = 0 and `inquiry`.`order_status` = 0 and `inquiry`.`regret_status` = 0 order by `follow_up`.`follow_up_id` desc");

				$data['pending_follow_up'] = $pending_follow_up[0]->pending_follow_up;

				$allotment_total = Data_model::db_query("select count(*) as allotment_total,`inquiry`.*, `quatation`.`quatation_id`, `quatation`.`quatation_no`, `quatation`.`follow_up_status` from `inquiry` inner join `quatation` on `quatation`.`inquiry_id` = `inquiry`.`inquiry_id` where `quatation`.zone_id IN (".$this->zone_id.") AND `quatation`.quatation_date = '".$previous_date."'  and `inquiry`.`order_status` = 0 and `inquiry`.`regret_status` = 0");

				$data['allotment_total'] = $allotment_total[0]->allotment_total;
			}



			$where1 ="(find_in_set(`inquiry`.project_zone,'$this->zone_id') OR `revise_quotation_notify`.emp_id = ".$employee_id.") AND `revise_quotation_notify`.clear = 0 ";

			$data['revise_notify'] =  DB::table('revise_quotation_notify')
				->join('inquiry', 'inquiry.inquiry_id', '=','revise_quotation_notify.inquiry_id')
				->join('customer_master', 'customer_master.customer_id', '=','inquiry.customer_id')
				->join('product_master', 'product_master.product_id', '=','inquiry.product_id')
				->leftJoin('follow_up', 'follow_up.inquiry_id', '=','inquiry.inquiry_id')
				->join('users as u1', 'u1.id', '=','revise_quotation_notify.added_by')
				->join('employee', 'employee.emp_id', '=','revise_quotation_notify.emp_id')
				->select('revise_quotation_notify.*','product_master.product_name','customer_master.name','customer_master.prefix','u1.username as follow_up_by','employee.name as allot_to','follow_up.added_by')
				->whereRaw($where1)
				->orderBy('employee.name','ASC'
)				->orderBy('id','DESC')
				->groupBy('follow_up.inquiry_id')
				->get();

			$where2 ="(find_in_set(`inquiry`.project_zone,'$this->zone_id') OR `prise_issue_notify`.emp_id = ".$employee_id.") AND `prise_issue_notify`.clear = 0";

			$data['prise_issue_notify'] =  DB::table('prise_issue_notify')
				->join('inquiry', 'inquiry.inquiry_id', '=','prise_issue_notify.inquiry_id')
				->join('customer_master', 'customer_master.customer_id', '=','inquiry.customer_id')
				->join('product_master', 'product_master.product_id', '=','inquiry.product_id')
				->leftJoin('follow_up', 'follow_up.inquiry_id', '=','inquiry.inquiry_id')
				->join('users as u1', 'u1.id', '=','prise_issue_notify.added_by')
				->join('employee', 'employee.emp_id', '=','prise_issue_notify.emp_id')
				->select('prise_issue_notify.*','product_master.product_name','customer_master.name','customer_master.prefix','u1.username as follow_up_by','employee.username as allot_to')
				->whereRaw($where2)
				->orderBy('employee.name','ASC')
				->orderBy('id','DESC')
				->groupBy('follow_up.inquiry_id')
				->get();
		}

		$data['utility'] = $this->utility;
		$data['controller_name'] = $this->controller;
		$data['role_id'] = $this->role_id;

		return view('dashboard',$data);
	}
	public function read_clear($id,$type)
	{
		$id = $this->utility->decode($id);
		$type = $this->utility->decode($type);

		if($type == 'prise')
		{
			$data = array('clear'=>1);
			$where = array('id'=>$id);
			Data_model::restore('prise_issue_notify',$data,$where);
		}
		else
		{
			$data = array('clear'=>1);
			$where = array('id'=>$id);
			Data_model::restore('revise_quotation_notify',$data,$where);
		}

		$msg = array('success' => 'Reject Successfully');

		return redirect($this->controller)->with($msg);
	}
}
