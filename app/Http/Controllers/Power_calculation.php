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

class Power_calculation extends Controller
{
	public $table="inquiry";
	public $primary_id="inquiry_id";
	public $field = "power";
	public $msgName = "Power Calculation";
	public $view = "power_calculation";
	public $controller = "Power_calculation";
	public $module_name = "power_calculation";
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
		if($this->role_id != '1')
		{
			$permission = Data_model::get_permission($this->module_name);
			$data['add_permission'] =  $permission[0]->add;
		}

		$data['role_id'] = $this->role_id;
		$data['utility'] = $this->utility;

		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		return view($this->view.'/manage',$data);
	}
	public function get_power_data(Request $request)
	{
		$data = array("data"=>"");


		$check = Data_model::retrive('power_calculation','inquiry_id',array(),'invoice_number','ASC');

		if(! empty($check))
		{
			$inquiry = array();
			foreach($check as $k=>$v)
			{
				$inquiry[] = $v->inquiry_id;
			}
			$inquiry = implode(",",$inquiry);

			$result = Data_model::db_query("select `quatation`.quatation_no,`quatation`.quatation_id,`inquiry`.inquiry_id,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`customer_master`.email,`customer_master`.email_2 from `inquiry` INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id where `inquiry`.inquiry_id NOT IN(".$inquiry.") ");
		}
		else
		{
			$result = Data_model::db_query("select `quatation`.quatation_no,`quatation`.quatation_id,`inquiry`.inquiry_id,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`customer_master`.email,`customer_master`.email_2 from `inquiry` INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id");
		}
		if($result)
		{
			foreach($result as $key=>$val)
			{

				$result_rq = Data_model::db_query("select `revise_quatation`.revise_id,`revise_quatation`.revise_quatation_no from  `revise_quatation` where quatation_id=".$val->quatation_id." ");

				$mobile = array();
				if($val->mobile != '')
					$mobile[] = $val->mobile;
				if($val->mobile_2 != '')
					$mobile[] = $val->mobile_2;
				if($val->mobile_3 != '')
					$mobile[] = $val->mobile_3;

				$email = array();
				if($val->email != '')
					$email[] = $val->email;
				if($val->email_2 != '')
					$email[] = $val->email_2;



				$action = "<a title='Power' class='btn bg-purple btn-flat btn-sm' href='".$this->controller."/generate_power/".$this->utility->encode($val->quatation_id)."/".$this->utility->encode('quotation')."'>Generate Power Calculation</a>&nbsp;";



				$data["data"][] = array(
					"quotation_no" => $val->quatation_no,
					"client_name" => $val->prefix.' '.$val->name,
					"mobile" => implode('<br />',$mobile),
					"email" => implode('<br />',$email),
					"actions" => $action
				);
				foreach($result_rq as $k=>$v)
				{

					$action = "<a title='Power' class='btn bg-purple btn-flat btn-sm' href='".$this->controller."/generate_power/".$this->utility->encode($v->revise_id)."/".$this->utility->encode('revise')."'>Generate Power Calculation</a>&nbsp;";


					$data["data"][] = array(
						"quotation_no" => $v->revise_quatation_no,
						"client_name" => $val->prefix.' '.$val->name,
						"mobile" => implode('<br />',$mobile),
						"email" => implode('<br />',$email),
						"actions" => $action
					);
				}
			}
		}
		echo json_encode($data);
	}
	public function generated_power_data(Request $request)
	{
		$data = array("data"=>"");

		$result = Data_model::db_query("select `power_calculation`.*,`inquiry`.inquiry_id,`quatation`.`quatation_no`,`revise_quatation`.revise_quatation_no,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`customer_master`.email,`customer_master`.email_2 from `power_calculation` INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `power_calculation`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.`customer_id` LEFT JOIN `quatation` ON `quatation`.quatation_id = `power_calculation`.quotation_id LEFT JOIN `revise_quatation` ON `revise_quatation`.revise_id = `power_calculation`.revise_id ");

		if($result)
		{
			foreach($result as $key=>$val)
			{
				if($val->quotation_id == 0)
				{
					$quot_num = $val->revise_quatation_no;
					$view_fun = 'Quatation/revise_quatation_view/'.$this->utility->encode($val->revise_id);
				}
				else
				{
					$quot_num = $val->quatation_no;
					$view_fun = 'Quatation/view/'.$this->utility->encode($val->quotation_id);
				}

				$mobile = array();
				if($val->mobile != '')
					$mobile[] = $val->mobile;
				if($val->mobile_2 != '')
					$mobile[] = $val->mobile_2;
				if($val->mobile_3 != '')
					$mobile[] = $val->mobile_3;

				$email = array();
				if($val->email != '')
					$email[] = $val->email;
				if($val->email_2 != '')
					$email[] = $val->email_2;

				$action = "<a target='_blank' class='btn btn-danger btn-flat btn-sm' href='".$this->controller."/print_pdf/".$this->utility->encode($val->id)."/".$this->utility->encode('print')."'><i class='glyphicon glyphicon-print icon-white'></i> Print</a>&nbsp;";

				$action .= "<a class='btn btn-info btn-flat btn-sm' href='".$this->controller."/print_pdf/".$this->utility->encode($val->id)."/".$this->utility->encode('download')."'><i class='glyphicon glyphicon-download icon-white'></i> Download</a>&nbsp;";

				$data["data"][] = array(
					"power_no" => $val->invoice_number,
					"quotation_no" => $quot_num,
					"client_name" => $val->prefix.' '.$val->name,
					"mobile" => implode('<br />',$mobile),
					"email" => implode('<br />',$email),
					"actions" => $action
				);

			}
		}
		echo json_encode($data);
	}
	public function generate_power($id,$type)
	{
		$type = $this->utility->decode($type);
		$quotation_id = $this->utility->decode($id);

		if($type == 'revise')
		{
			$data['result'] = Data_model::db_query("select * from `revise_quatation` where `revise_id`=".$quotation_id." ");
		}
		else
		{
			$data['result'] = Data_model::db_query("select * from `quatation` where `quatation_id`=".$quotation_id." ");
		}

		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;

		$data['quot_id'] = $quotation_id;
		$data['type'] = $type;

		$data['quatation_product'] = Data_model::retrive('quatation_product','*',array('delete_status'=>0),'name','ASC');

		return view($this->view.'/generate_power',$data);
	}
	public function add_power_data(Request $request)
	{
		$user_id = $this->user_id;
		$quot_id  = $request->input('quot_id');
		$type  = $request->input('type');
		$inquiry_id  = $request->input('inquiry_id');
		$product_id  = implode(",",$request->input('quatation_product_id'));
		$qty  = implode(",",$request->input('qty'));
		$power_hp  = implode(",",$request->input('power_hp'));
		$power_kw  = implode(",",$request->input('power_kw'));
		$qty_total  = $request->input('qty_total');
		$power_total_hp  = $request->input('power_total_hp');
		$power_total_kw  = $request->input('power_total_kw');
		$date = date('Y-m-d H:i:s');

		if(date('m') >= 4)
		{
			$last_year = date('Y');
			$year = date('y') +1;
	    }
	    else
	    {
			$year = date('y');
			$last_year = date('Y') - 1;
	    }
		$new_year = $last_year."-".$year;

		if($type == 'revise')
		{
			$data_power = array('inquiry_id'=>$inquiry_id,'revise_id'=>$quot_id,'product_id'=>$product_id,'qty'=>$qty,'power_hp'=>$power_hp,'power_kw'=>$power_kw,'qty_total'=>$qty_total,'hp_power_total'=>$power_total_hp,'kw_power_total'=>$power_total_kw,'added_by'=>$this->user_id,'added_date'=>$date);
		}
		else
		{
			$data_power = array('inquiry_id'=>$inquiry_id,'quotation_id'=>$quot_id,'product_id'=>$product_id,'qty'=>$qty,'power_hp'=>$power_hp,'power_kw'=>$power_kw,'qty_total'=>$qty_total,'hp_power_total'=>$power_total_hp,'kw_power_total'=>$power_total_kw,'added_by'=>$this->user_id,'added_date'=>$date);
		}
		$inserted_id = Data_model::store('power_calculation',$data_power);
		/* Year Wise get power Number */
		$get_num = Data_model::db_query("select * from `power_calculation` where `year`='".$new_year."' Order By `p_no` desc limit 1 ");

		if(empty($get_num)){
			$yearNo = '1';
		}
		else{
			$yearNo = $get_num[0]->p_no+1;
		}

		$invoice_num = 'RW/'.$new_year.'/PC-'.($yearNo);
		$update_data = array('p_no'=>$yearNo,'invoice_number'=>$invoice_num);
		Data_model::restore('power_calculation',$update_data,array('id'=>$inserted_id));

		$msg = array('success' => $this->msgName.' Generate Sucessfully');

		return redirect($this->controller)->with($msg);
	}
	public function print_pdf($id,$type)
	{
		$data['utility'] = $this->utility;
		$id = $this->utility->decode($id);
		$type = $this->utility->decode($type);

		 $quot_check = DB::table('power_calculation')
		 ->where('id',"=",$id)
		 ->get();
		 if($quot_check[0]->quotation_id == 0)
		 {
			 $quot = 'no';
		 }
		 else
		 {
			 $quot = 'yes';
		 }

		if($quot == 'yes')
		{
			$data['result'] = Data_model::db_query("select `power_calculation`.*,`inquiry`.inquiry_id,`quatation`.`quatation_no`,`quatation`.quatation_date as quot_date,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`customer_master`.email,`customer_master`.email_2,`customer_master`.company,`customer_master`.address from `power_calculation` INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `power_calculation`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.`customer_id` LEFT JOIN `quatation` ON `quatation`.quatation_id = `power_calculation`.quotation_id where `power_calculation`.id=".$id." ");
		}
		else
		{
			$data['result'] = Data_model::db_query("select `power_calculation`.*,`inquiry`.inquiry_id,`revise_quatation`.revise_quatation_no,`revise_quatation`.revise_date as quot_date,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`customer_master`.email,`customer_master`.email_2,`customer_master`.company,`customer_master`.address from `power_calculation` INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `power_calculation`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.`customer_id` LEFT JOIN `revise_quatation` ON `revise_quatation`.revise_id = `power_calculation`.revise_id where `power_calculation`.id=".$id." ");
		}

		$data['quatation_product'] = Data_model::retrive('quatation_product','*',array(),'name','ASC');
		$data['company'] = Data_model::retrive('company','*',array(),'name','ASC');
		$data['controller_name'] = $this->controller;
		$data['quot'] = $quot;

		$data['letter_head'] = Data_model::retrive('letterhead_master','*',array(),'letterhead_name','ASC');

		$pdf = PDF::loadView($this->view.'/print',$data)->setPaper('a4','portrait');
		if($type == 'print')
		{
			return $pdf->stream('Power_calculation_'.date('d-m-Y H:i:s'));
		}
		else
		{
			return $pdf->download('Power_calculation_'.date('d-m-Y H:i:s'));
		}
	}
	public function get_hp_power(Request $request)
	{
		$product_id = $request->input('product_id');

		$get_product = Data_model::retrive('quatation_product','*',array('p_id'=>$product_id),'name','ASC');

		if(! empty($get_product))
		{
			$hp_power = $get_product[0]->power_value;
		}
		else
		{
			$hp_power = 0;
		}
		echo $hp_power;
		exit;
	}
	public function view($id)
	{
		$data['utility'] = $this->utility;
		$order_id = $this->utility->decode($id);


		 $order_check = DB::table('order_book')
		 ->where('order_id',"=",$order_id)
		 ->get();
		 if($order_check[0]->quot_id == 0)
		 {
			 $quot = 'no';
		 }
		 else
		 {
			 $quot = 'yes';
		 }

		if($quot == 'yes')
		{
			$data['result'] = Data_model::db_query("select order_book.*,inquiry.*,quatation.*,customer_master.*,country_master.country_name,state_master.state_name,city_master.city_name,product_master.product_name,follow_up.added_by,u2.username as quot_user,u3.username as foll_user from order_book
			INNER JOIN inquiry ON inquiry.inquiry_id = order_book.inquiry_id
			LEFT JOIN quatation ON quatation.quatation_id = order_book.quot_id
			INNER JOIN customer_master ON customer_master.customer_id = inquiry.customer_id
			INNER JOIN country_master ON country_master.country_id = customer_master.country_id
			INNER JOIN product_master ON product_master.product_id = inquiry.product_id
			LEFT JOIN state_master ON state_master.state_id = customer_master.state_id
			LEFT JOIN city_master ON city_master.city_id = customer_master.city_id
			LEFT JOIN (select * from follow_up order by follow_up_id desc LIMIT 1) follow_up ON follow_up.inquiry_id = inquiry.inquiry_id
			LEFT JOIN users as u2 ON u2.id = quatation.added_by
			LEFT JOIN users as u3 ON u3.id = follow_up.added_by where order_book.order_id = ".$order_id." ");
		}
		else
		{
			$data['result'] = Data_model::db_query("select order_book.*,inquiry.*,revise_quatation.*,customer_master.*,country_master.country_name,state_master.state_name,city_master.city_name,product_master.product_name,follow_up.added_by,u1.username as revise_user,u3.username as foll_user from order_book
			INNER JOIN inquiry ON inquiry.inquiry_id = order_book.inquiry_id
			LEFT JOIN revise_quatation ON revise_quatation.revise_id = order_book.rquot_id
			INNER JOIN customer_master ON customer_master.customer_id = inquiry.customer_id
			INNER JOIN country_master ON country_master.country_id = customer_master.country_id
			INNER JOIN product_master ON product_master.product_id = inquiry.product_id
			LEFT JOIN state_master ON state_master.state_id = customer_master.state_id
			LEFT JOIN city_master ON city_master.city_id = customer_master.city_id
			LEFT JOIN (select * from follow_up order by follow_up_id desc LIMIT 1) follow_up ON follow_up.inquiry_id = inquiry.inquiry_id
			LEFT JOIN users as u1 ON u1.id = revise_quatation.added_by
			LEFT JOIN users as u3 ON u3.id = follow_up.added_by where order_book.order_id = ".$order_id." ");
		}

		$data['specification'] = Data_model::retrive('specification_master','*',array(),'sequence','ASC');
		$data['quatation_product'] = Data_model::retrive('quatation_product','*',array(),'name','ASC');
		$data['company'] = Data_model::retrive('company','*',array(),'name','ASC');
		$data['controller_name'] = $this->controller;
		$data['quot'] = $quot;
		$data['msgName']=$this->msgName;
		$data['letter_head'] = Data_model::retrive('letterhead_master','*',array(),'letterhead_name','ASC');
		return view($this->view.'/view',$data);
	}
}
