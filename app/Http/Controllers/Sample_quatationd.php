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

//set_error_handler(null);
//set_exception_handler(null);
class Sample_quatation extends Controller
{
	public $table="sample_quatation";
	public $primary_id="sq_id";
	public $foreign_table = "specification";
	public $foreign_id = "specification_id";
	public $msgName = "Sample Quatation";
	public $view = "sample_quatation";
	public $controller = "Sample_quatation";
	public $utility;
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
		$data['utility'] = $this->utility;
		$role_session = Session::get('raj_role_id');
		$zone_session = Session::get('raj_zone_id');
		
		//New Inquiry
		
		//For Old Quatation
		
		
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		return view($this->view.'/manage',$data);
	}
	
	
	
	public function add($inquiry_id,$q_master_id)
	{
		$data['utility'] = $this->utility;
		$inquiry_id = $this->utility->decode($inquiry_id);
		$q_master_id = $this->utility->decode($q_master_id);
		$count = DB::select("Select * from ".$this->table." order by `q_no` DESC LIMIT 1");
		
		if (date('m') >= 4)
		{
		  $last_year = date('y') ;
		  $year = date('Y') +1;
	    }
	    else 
	    {
		  
		   $year = date('y');
		   $last_year = date('Y') - 1;
	    }
		 $new_year = $last_year."-".$year;
		
		if(!empty($count))
		{
			
			$data['quatation_no'] = 'RW/'. $new_year.'/Q_'.($count[0]->q_no + 1);
		}
		else
		{
			$data['quatation_no'] = 'RW/'. $new_year.'/Q_1';
		}
		
		$data['action']="update";
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		$data['inquiry_id'] = $inquiry_id;
		$data['q_master_id'] = $q_master_id;
		
		$data['result'] =DB::table($this->foreign_table)
		->join('customer_master', 'customer_master.customer_id', '=', $this->foreign_table.'.customer_id')
		->join('users', 'users.id', '=', $this->foreign_table.'.added_by')
	   ->select($this->foreign_table.'.*', 'customer_master.*', 'users.username')
	   ->where($this->foreign_id,"=",$inquiry_id )
	   ->get();

		
			

		$data['category'] = Data_model::retrive('category_master','*',array('delete_status'=>0),'category_id','DESC');
		$data['product'] = Data_model::retrive('product_master','*',array('delete_status'=>0),'product_name','ASC');
		$data['quatation_product'] = Data_model::retrive('quatation_product','*',array('delete_status'=>0),'name','ASC');
		$data['country'] = Data_model::retrive('country_master','*',array('delete_status'=>0),'country_name','ASC');
		$data['state'] = Data_model::retrive('state_master','*',array('delete_status'=>0),'state_name','ASC');
		$data['city'] = Data_model::retrive('city_master','*',array('delete_status'=>0),'city_name','ASC');
		$data['employee'] = Data_model::retrive('employee','*',array('delete_status'=>0),'name','ASC');
		$data['customer'] = Data_model::retrive('customer_master','*',array(),'customer_id','DESC');
		$data['source'] = Data_model::retrive('source_master','*',array('delete_status'=>0),'source_name','ASC');
		$data['specification'] = Data_model::retrive('specification_master','*',array('delete_status'=>0),'specification_id','DESC');
		return view($this->view.'/generate_quatation',$data);
	}
	public function get_customer_data(Request $request)
	{
		$customer_id = $request->input('customer_id');
		$customer = Data_model::retrive('customer_master','*',array('customer_id'=>$customer_id),'customer_id','DESC');
		
		$st = '';
		
		$st.= $customer[0]->address."**".$customer[0]->state_id."**".$customer[0]->city_id."**".$customer[0]->mobile;
		
		if($customer[0]->mobile_2!='')
		{
			$st.="**".$customer[0]->mobile_2;
		}
		else
		{
			$st.="**null";
		}
		
		if($customer[0]->mobile_3!='')
		{
			$st.="**".$customer[0]->mobile_3;
		}
		else
		{
			$st.="**null";
		}
		if($customer[0]->office_address!='')
		{
			$st.="**".$customer[0]->office_address;
		}
		else
		{
			$st.="**null";
		}
		if($customer[0]->landline!='')
		{
			$st.="**".$customer[0]->landline;
		}
		else
		{
			$st.="**null";
		}
		if($customer[0]->company!='')
		{
			$st.="**".$customer[0]->company;
		}
		else
		{
			$st.="**null";
		}
		if($customer[0]->phone_no!='')
		{
			$st.="**".$customer[0]->phone_no;
		}
		else
		{
			$st.="**null";
		}
		if($customer[0]->email!='')
		{
			$st.="**".$customer[0]->email;
		}
		else
		{
			$st.="**null";
		}
		if($customer[0]->email_2!='')
		{
			$st.="**".$customer[0]->email_2;
		}
		else
		{
			$st.="**null";
		}
		if($customer[0]->country_id!='')
		{
			$st.="**".$customer[0]->country_id;
			$get_country_zone = Data_model::retrive('country_master','zone_id',array('country_id'=>$customer[0]->country_id),'country_id','');
			//For Country Zone Id
			$data[1] = $get_country_zone[0]->zone_id;
		}
		else
		{
			$st.="**null";
		}
		$data[0]=$st;
		echo json_encode($data);
		
	}
	public function get_city(Request $request)
	{
		$state_id = $request->input('state_id');
		$get_city = Data_model::retrive('city_master','*',array('state_id'=>$state_id,'delete_status'=>0),'city_name','ASC');
		echo '<option  value="">Select</option>';
		foreach($get_city as $k=> $v)
		{
			echo '<option value="'.$v->city_id.'" >'.$v->city_name.'</option>';
		}
	}
	public function mobile_check(Request $request)
	{
		$customer_id  = trim($request->customer_id);
		$mobile  = trim($request->mobile);
		
		if($customer_id=='')
		{
			$check = Data_model::db_query("SELECT * FROM `customer_master` WHERE `mobile`='$mobile' OR `mobile_2` = '$mobile' OR `mobile_3` = '$mobile'");
			if(count($check))
			{
				echo 1;
			}	
			else
			{
				echo 0;
			}
		}
		else
		{
			$check = Data_model::db_query("SELECT * FROM `customer_master` WHERE (`mobile`='$mobile' OR `mobile_2` = '$mobile' OR `mobile_3` = '$mobile') AND `customer_id` != $customer_id");
			if(count($check))
			{
				echo 1;
			}	
			else
			{
				echo 0;
			}
		}
		
	}
	public function email_check(Request $request)
	{
		$customer_id  = trim($request->customer_id);
		$email  = trim($request->email);
		
		if($customer_id=='')
		{
			$check = Data_model::db_query("SELECT * FROM `customer_master` WHERE `email`='$email' OR `email_2` = '$email'");
			if(count($check))
			{
				echo 1;
			}	
			else
			{
				echo 0;
			}
		}
		else
		{
			$check = Data_model::db_query("SELECT * FROM `customer_master` WHERE (`email`='$email' OR `email_2` = '$email') AND `customer_id` != $customer_id");
			if(count($check))
			{
				echo 1;
			}	
			else
			{
				echo 0;
			}
		}
		
	}
	
	public function get_country_zone(Request $request)
	{
		$country_id  = trim($request->country_id);
		$check = Data_model::retrive("country_master",'*',array('country_id'=>$country_id),'country_id','');
		$state=Data_model::retrive("state_master",'*',array('country_id'=>$country_id,'delete_status'=>0),'state_name','ASC');
		$data[0] = $check[0]->zone_id;
		$data[1]='<option  value="">Select</option>';
		foreach($state as $k=> $v)
		{		
			$data[1] .= '<option value="'.$v->state_id.'">'.$v->state_name.'</option>';
		}
		echo json_encode($data);
	}
	public function get_rate(Request $request)
	{
		$quatation_product_id = $request->quatation_product_id;
		$get_rate = Data_model::retrive('quatation_product','*',array('p_id'=>$quatation_product_id),'p_id','');
		if(count($get_rate))
		{
			$data[0]=$get_rate[0]->rate;
		}
		else
		{
			$data[0]=0;
		}
		echo json_encode($data);
	}

	public function add_quatation(Request $request)
	{
		
		$inquiry_id = $request->input("inquiry_id");
		$q_master_id = $request->input("q_master_id");
		
		$inquiry_no  = $request->input('inquiry_no');
		$inquiry_date  = date("Y-m-d",strtotime($request->input('inquiry_date')));
		$quatation_no = $request->input('quatation_no');
		$explode_q_no=explode('_',$quatation_no);
		$q_no=$explode_q_no[1];
		$quatation_date = date("Y-m-d");
		$quatation_time = date("H:i:s");
		
		$quatation_product_id = implode(',',$request->quatation_product_id);
		$rate = implode(',',$request->rate);
		$qty = implode(',',$request->qty);
		$amount = implode(',',$request->amount);
		$gross_amount = $request->gross_amount;
		$gst_amount = $request->gst_amount;
		$total_amount = $request->total_amount;
		
		$user_id = Session::get('raj_user_id');
		$added_time = date("Y-m-d H:i:s");
		
		if(!empty($request->specification_id))
		{
			$specification_id = implode("*****",$request->specification_id);
			$specification_arr = $request->specification_id;
			
			$spe_name_arr = array();
			$spe_value_arr = array();
			$sequence_arr = array();
			foreach($specification_arr as $key=>$value)
			{
				
				$sequence_arr[].= $request->input($value."_seqence");
				$nm = $request->input($value."_spe_name");
				$spe_name_arr[].= implode("+++++",$nm);

				$val = $request->input($value."_spe_value");
				$spe_value_arr[].= implode("+++++",$val);
			}
			$spe_name = implode("*****",$spe_name_arr);
			$spe_value = implode("*****",$spe_value_arr);
			
		}
		else
		{
			$specification_id = '';
			$spe_name = '';
			$spe_value = '';
			
		}
		
		$product_id = $request->input('product_id');
		$category_id = $request->input('category_id');
		$p_id = implode(",",$request->input('quatation_product_id'));
		$p_rate = implode(",",$request->input('rate'));
		
		$insentive = $request->input('insentive');
		if($insentive == 'no')
		{
			$attended_by = 0;
		}
		else
		{
			$attended_by = $request->input('attended_by');
		}
		
		$customer_id = $request->input('customer_id');
		$name = $request->input('name');
		$address = $request->input('address');
		$country_id = $request->input('country_id');
		$state_id = $request->input('state_id');
		$city_id = $request->input('city_id');
		$get_country_zone = Data_model::retrive('country_master','zone_id',array('country_id'=>$country_id),'country_id','');
		//Get Zone
		if($get_country_zone[0]->zone_id != 0)
		{
			$inquery_zone_id = $get_country_zone[0]->zone_id;
			$state_id = 0;
			$city_id = 0;
			
		}
		else
		{
			$get_zone = Data_model::retrive('state_master','zone_id',array('state_id'=>$state_id),'state_id','DESC');
			$inquery_zone_id = $get_zone[0]->zone_id;
		}
		
		$mobile = $request->input('mobile');
		$mobile_2 = $request->input('mobile_2');
		$mobile_3 = $request->input('mobile_3');
		$office_address = $request->input('office_address');
		$landline = $request->input('landline');
		$company = $request->input('company');
	
		$email = $request->input('email');
		$email_2 = $request->input('email_2');
	
		$source_id = $request->input('source_id');
		
		$project_state = $request->input('project_state');
		$project_city = $request->input('project_city');
		$remarks = $request->input('remarks');
		
		
		$customer_type = $request->input('customer_type');
		
		if($customer_type == 'new')
		{
			$customer_data = array('name'=>$name,'address'=>$address,'country_id'=>$country_id,'state_id'=>$state_id,'city_id'=>$city_id,'mobile'=>$mobile,'mobile_2'=>$mobile_2,'mobile_3'=>$mobile_3,'office_address'=>$office_address,'landline'=>$landline,'company'=>$company,'email'=>$email,'email_2'=>$email_2);
		}
		else
		{
			$customer_data = array('address'=>$address,'country_id'=>$country_id,'state_id'=>$state_id,'city_id'=>$city_id,'mobile'=>$mobile,'mobile_2'=>$mobile_2,'mobile_3'=>$mobile_3,'office_address'=>$office_address,'landline'=>$landline,'company'=>$company,'email'=>$email,'email_2'=>$email_2);
		}

		$data = array(
			'q_master_id'=>$q_master_id,'inquiry_id'=>$inquiry_id,'zone_id'=>$inquery_zone_id,'inquiry_no'=>$inquiry_no,'inquiry_date'=>$inquiry_date,'q_no'=>$q_no,'quatation_no'=>$quatation_no,'quatation_date'=>$quatation_date,'quatation_time'=>$quatation_time,'quatation_product_id'=>$quatation_product_id,'rate'=>$rate,'qty'=>$qty,'amount'=>$amount,'gross_amount'=>$gross_amount,'gst_amount'=>$gst_amount,'total_amount'=>$total_amount,'specification_id'=>$specification_id,'spe_name'=>$spe_name,'spe_value'=>$spe_value,
			'added_by'=>$user_id,'added_time'=>$added_time);

		if($id = Data_model::store($this->table,$data))
		{
			Data_model::restore('customer_master',$customer_data,array('customer_id'=>$customer_id));

			Data_model::restore('inquiry',array('product_id'=>$product_id,'p_id'=>$p_id,'p_rate'=>$p_rate,'category_id'=>$category_id,'insentive'=>$insentive,'attended_by'=>$attended_by,'customer_type'=>$customer_type,'customer_id'=>$customer_id,'source_id'=>$source_id,'project_zone'=>$inquery_zone_id,'project_state'=>$project_state,'project_city'=>$project_city,'remarks'=>$remarks,'updated_by'=>$user_id,'first_quatation_id'=>$id),array('inquiry_id'=>$inquiry_id));

			Data_model::restore('quatation_master',array('zone_id'=>$inquery_zone_id),array('inquiry_id'=>$inquiry_id));

			$msg = array('success' => 'Quatation Added Sucessfully');
		}
		else
		{
			$msg = array('error' => 'Some Problem ... Please Try Again');
		}
		return redirect($this->controller)->with($msg);
	}

	public function view($id)
	{
		$data['utility'] = $this->utility;
		$data['msg'] = $this->msgName;
		$quatation_id = $this->utility->decode($id);
		
		$data['result'] = Data_model::db_query("SELECT `".$this->table."`.* ,`product_master`.`product_name`,`customer_master`.`mobile` ,`customer_master`.`email` ,`customer_master`.`name` as `customer_name`,`customer_master`.`company`,`customer_master`.`address` ,`role_master`.`role_name` , `users`.`username` , `users`.`mobile` as `user_mobile` FROM ".$this->table."
		LEFT JOIN `".$this->foreign_table."` ON `".$this->table."`.`".$this->foreign_id."` = `".$this->foreign_table."`.`".$this->foreign_id."`
		LEFT JOIN `product_master` ON `product_master`.`product_id` = `".$this->foreign_table."`.`product_id`
		LEFT JOIN `customer_master` ON `customer_master`.`customer_id` = `".$this->foreign_table."`.`customer_id`
		LEFT JOIN `users` ON `users`.`id` = `".$this->table."`.`added_by`
		LEFT JOIN `role_master` ON `role_master`.`role_id` = `users`.`role_id`
		where `".$this->table."`.`".$this->primary_id."` = '".$quatation_id."'");
		
		$data['specification'] = Data_model::retrive('specification_master','*',array(),'sequence','ASC');
		$data['quatation_product'] = Data_model::retrive('quatation_product','*',array(),'name','ASC');
		$data['terms_condition'] = Data_model::retrive('terms_condition','*',array(),'term_id','ASC');
		$data['company'] = Data_model::retrive('company','*',array(),'name','ASC');
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		return view($this->view.'/view',$data);
	}

	public function print_quatation($id,$type,$with_latterhead)
	{
		
		$data['utility'] = $this->utility;
		$quatation_id = $this->utility->decode($id);
		$type = $this->utility->decode($type);
		$data['with_latterhead']= $this->utility->decode($with_latterhead);
		
		$data['result'] = Data_model::db_query("SELECT `".$this->table."`.* ,`product_master`.`product_name`,`customer_master`.`mobile` ,`customer_master`.`email` ,`customer_master`.`name` as `customer_name` ,`customer_master`.`company`,`customer_master`.`address`,`role_master`.`role_name` , `users`.`username` , `users`.`mobile` as `user_mobile` FROM ".$this->table."
		LEFT JOIN `".$this->foreign_table."` ON `".$this->table."`.`".$this->foreign_id."` = `".$this->foreign_table."`.`".$this->foreign_id."`
		LEFT JOIN `product_master` ON `product_master`.`product_id` = `".$this->foreign_table."`.`product_id`
		LEFT JOIN `customer_master` ON `customer_master`.`customer_id` = `".$this->foreign_table."`.`customer_id`
		LEFT JOIN `users` ON `users`.`id` = `".$this->table."`.`added_by`
		LEFT JOIN `role_master` ON `role_master`.`role_id` = `users`.`role_id`
		where `".$this->table."`.`".$this->primary_id."` = '".$quatation_id."'");
		
		$data['specification'] = Data_model::retrive('specification_master','*',array(),'sequence','ASC');
		$data['quatation_product'] = Data_model::retrive('quatation_product','*',array(),'name','ASC');
		$data['terms_condition'] = Data_model::retrive('terms_condition','*',array(),'term_id','ASC');
		$data['company'] = Data_model::retrive('company','*',array(),'name','ASC');
		$data['controller_name'] = $this->controller;
		
		if($type == 'print')
		{	
			$pdf = PDF::loadView($this->view.'/print',$data);
			return $pdf->stream('Quatation '.date('d-m-Y H:i:s'));
		}
		else
		{
			$pdf = PDF::loadView($this->view.'/print',$data);
			return $pdf->download('Quatation_'.date('d-m-Y H:i:s').'.pdf');
		}
		
	}
	public function revise_quatation($inquiry_id,$quatation_id)
	{
		$data['utility'] = $this->utility;
		$inquiry_id = $this->utility->decode($inquiry_id);
		$quatation_id = $this->utility->decode($quatation_id);
		
		
		$data['quatation_result'] =DB::table($this->table)
		->join('users', 'users.id', '=', $this->table.'.added_by')
		->select($this->table.'.*', 'users.username')
		->where($this->primary_id,"=",$quatation_id )
		->get();
		
		$count = Data_model::retrive('revise_quatation','*',array($this->primary_id=>$quatation_id),'rq_no','DESC');
		
		if(count($count))
		{
			$data['revise_quatation_no'] = $data['quatation_result'][0]->quatation_no.'/R_'.($count[0]->rq_no + 1);
			$data['revice_quatation_result']=$count;
		}
		else
		{
			$data['revise_quatation_no'] = $data['quatation_result'][0]->quatation_no.'/R_1';
		}
		$data['action']="update";
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		$data['inquiry_id'] = $inquiry_id;
		$data['quatation_id'] = $quatation_id;
		
		$data['result'] =DB::table($this->foreign_table)
		->join('customer_master', 'customer_master.customer_id', '=', $this->foreign_table.'.customer_id')
		->join('users', 'users.id', '=', $this->foreign_table.'.added_by')
	   ->select($this->foreign_table.'.*', 'customer_master.*', 'users.username')
	   ->where($this->foreign_id,"=",$inquiry_id)
	   ->get();
		
	   
		$data['category'] = Data_model::retrive('category_master','*',array('delete_status'=>0),'category_id','DESC');
		$data['product'] = Data_model::retrive('product_master','*',array('delete_status'=>0),'product_name','ASC');
		$data['quatation_product'] = Data_model::retrive('quatation_product','*',array('delete_status'=>0),'name','ASC');
		$data['country'] = Data_model::retrive('country_master','*',array('delete_status'=>0),'country_name','ASC');
		$data['state'] = Data_model::retrive('state_master','*',array('delete_status'=>0),'state_name','ASC');
		$data['city'] = Data_model::retrive('city_master','*',array('delete_status'=>0),'city_name','ASC');
		$data['employee'] = Data_model::retrive('employee','*',array('delete_status'=>0),'name','ASC');
		$data['customer'] = Data_model::retrive('customer_master','*',array(),'customer_id','DESC');
		$data['source'] = Data_model::retrive('source_master','*',array('delete_status'=>0),'source_name','ASC');
		$data['specification'] = Data_model::retrive('specification_master','*',array('delete_status'=>0),'specification_id','DESC');
		return view($this->view.'/revise_generate_quatation',$data);
	}
	
	public function add_revise_quatation(Request $request)
	{
		
		$inquiry_id = $request->input("inquiry_id");
		$quatation_id = $request->input("quatation_id");
		
		//Revise data
		$revise_quatation_no = $request->input('revise_quatation_no');
		$revise_date = date("Y-m-d");
		$revise_time = date("H:i:s");
		
		$rq_explode= explode('/',$revise_quatation_no);
		$rq_no_explode=explode('_',$rq_explode[3]);
		$rq_no=$rq_no_explode[1];
		
		$inquiry_no  = $request->input('inquiry_no');
		$inquiry_date  = date("Y-m-d",strtotime($request->input('inquiry_date')));
		
		$quatation_no = $request->input('quatation_no');
		$quatation_date = date("Y-m-d");
		$quatation_time = date("H:i:s");
		
		$quatation_product_id = implode(',',$request->quatation_product_id);
		$rate = implode(',',$request->rate);
		$qty = implode(',',$request->qty);
		$amount = implode(',',$request->amount);
		$gross_amount = $request->gross_amount;
		$gst_amount = $request->gst_amount;
		$total_amount = $request->total_amount;
		
		$user_id = Session::get('raj_user_id');
		$added_time = date("Y-m-d H:i:s");
		
		if(!empty($request->specification_id))
		{
			$specification_id = implode("*****",$request->specification_id);
			$specification_arr = $request->specification_id;
			$spe_name_arr = array();
			$spe_value_arr = array();
			$sequence_arr = array();
			foreach($specification_arr as $key=>$value)
			{
				$sequence_arr[].= $request->input($value."_seqence");
				$nm = $request->input($value."_spe_name");
				$spe_name_arr[].= implode("+++++",$nm);

				$val = $request->input($value."_spe_value");
				$spe_value_arr[].= implode("+++++",$val);
			}
			$spe_name = implode("*****",$spe_name_arr);
			$spe_value = implode("*****",$spe_value_arr);
		}
		else
		{
			$specification_id = '';
			$spe_name = '';
			$spe_value = '';
		}
		
		$product_id = $request->input('product_id');
		$category_id = $request->input('category_id');
		$p_id = implode(",",$request->input('quatation_product_id'));
		$p_rate = implode(",",$request->input('rate'));
		
		$insentive = $request->input('insentive');
		if($insentive == 'no')
		{
			$attended_by = 0;
		}
		else
		{
			$attended_by = $request->input('attended_by');
		}
		
		$customer_id = $request->input('customer_id');
		$name = $request->input('name');
		$address = $request->input('address');
		$country_id = $request->input('country_id');
		$state_id = $request->input('state_id');
		$city_id = $request->input('city_id');
		$get_country_zone = Data_model::retrive('country_master','zone_id',array('country_id'=>$country_id),'country_id','');
		//Get Zone
		if($get_country_zone[0]->zone_id != 0)
		{
			$inquery_zone_id = $get_country_zone[0]->zone_id;
			$state_id = 0;
			$city_id = 0;
			
		}
		else
		{
			$get_zone = Data_model::retrive('state_master','zone_id',array('state_id'=>$state_id),'state_id','DESC');
			$inquery_zone_id = $get_zone[0]->zone_id;
		}
		
		$mobile = $request->input('mobile');
		$mobile_2 = $request->input('mobile_2');
		$mobile_3 = $request->input('mobile_3');
		$office_address = $request->input('office_address');
		$landline = $request->input('landline');
		$company = $request->input('company');
	
		$email = $request->input('email');
		$email_2 = $request->input('email_2');
	
		$source_id = $request->input('source_id');
		$project_state = $request->input('project_state');
		$project_city = $request->input('project_city');
		$remarks = $request->input('remarks');
		
		$customer_type = $request->input('customer_type');
		
		if($customer_type == 'new')
		{
			$customer_data = array('name'=>$name,'address'=>$address,'country_id'=>$country_id,'state_id'=>$state_id,'city_id'=>$city_id,'mobile'=>$mobile,'mobile_2'=>$mobile_2,'mobile_3'=>$mobile_3,'office_address'=>$office_address,'landline'=>$landline,'company'=>$company,'email'=>$email,'email_2'=>$email_2);
		}
		else
		{
			$customer_data = array('address'=>$address,'country_id'=>$country_id,'state_id'=>$state_id,'city_id'=>$city_id,'mobile'=>$mobile,'mobile_2'=>$mobile_2,'mobile_3'=>$mobile_3,'office_address'=>$office_address,'landline'=>$landline,'company'=>$company,'email'=>$email,'email_2'=>$email_2);
		}

		$data = array(
			'rq_no'=>$rq_no,
			'revise_quatation_no'=>$revise_quatation_no,
			'revise_date'=>$revise_date,
			'revise_time'=>$revise_time,
			'inquiry_id'=>$inquiry_id,
			'quatation_id'=>$quatation_id,
			'quatation_product_id'=>$quatation_product_id,
			'rate'=>$rate,'qty'=>$qty,
			'amount'=>$amount,
			'gross_amount'=>$gross_amount,
			'gst_amount'=>$gst_amount,
			'total_amount'=>$total_amount,
			'specification_id'=>$specification_id,
			'spe_name'=>$spe_name,
			'spe_value'=>$spe_value,
			'added_by'=>$user_id,
			'added_time'=>$added_time);

		if($id = Data_model::store('revise_quatation',$data))
		{
			Data_model::restore('customer_master',$customer_data,array('customer_id'=>$customer_id));
			Data_model::restore('inquiry',array('product_id'=>$product_id,'p_id'=>$p_id,'p_rate'=>$p_rate,'category_id'=>$category_id,'insentive'=>$insentive,'attended_by'=>$attended_by,'customer_type'=>$customer_type,'customer_id'=>$customer_id,'source_id'=>$source_id,'project_zone'=>$inquery_zone_id,'project_state'=>$project_state,'project_city'=>$project_city,'remarks'=>$remarks,'updated_by'=>$user_id,'first_quatation_id'=>$id),array('inquiry_id'=>$inquiry_id));

			$msg = array('success' => 'Quatation Added Sucessfully');
		}
		else
		{
			$msg = array('error' => 'Some Problem ... Please Try Again');
		}
		return redirect($this->controller)->with($msg);
	}
	
	public function revise_quatation_index($id)
	{
		$data['utility'] = $this->utility;
		$role_session = Session::get('raj_role_id');
		$zone_session = Session::get('raj_zone_id');
		
		$quatation_id=$this->utility->decode($id);

		$masterTable = 'revise_quatation';
		$parentTable = $this->table;
		$select = array($masterTable.'.*',$parentTable.'.*');
		$condition_1= $masterTable.'.'.$this->primary_id;
		$condition_2= $parentTable.'.'.$this->primary_id;
		$orderField = $masterTable.'.revise_id';
		$orderType = 'DESC';
		
		if ( $role_session == '1') 
		{
			$where = array($masterTable.'.'.$this->primary_id=>$quatation_id);
		}
		else
		{
			$where = array($masterTable.'.zone_id'=>$zone_session,$masterTable.'.'.$$this->primary_id=>$quatation_id);
		}
		$data['result'] = Data_model::singleJoin($masterTable,$parentTable,$select,$condition_1,$condition_2,$where,$orderField ,$orderType);
	
	
		$data['controller_name'] = $this->controller;
		$data['msgName']='Revise Quatation';
		
	
		return view($this->view.'/revise_manage',$data);
	}
	
	public function revise_quatation_view($id)
	{
		$data['utility'] = $this->utility;
		$data['msg'] = $this->msgName;
		$revise_quatation_id = $this->utility->decode($id);
		
		$data['result'] = Data_model::db_query("SELECT `revise_quatation`.* ,`product_master`.`product_name`,`customer_master`.`mobile` ,`customer_master`.`email` ,`customer_master`.`name` as `customer_name`,`customer_master`.`company`,`customer_master`.`address` ,`role_master`.`role_name` , `users`.`username` , `users`.`mobile` as `user_mobile` FROM revise_quatation
		LEFT JOIN `".$this->foreign_table."` ON `revise_quatation`.`".$this->foreign_id."` = `".$this->foreign_table."`.`".$this->foreign_id."`
		LEFT JOIN `product_master` ON `product_master`.`product_id` = `".$this->foreign_table."`.`product_id`
		LEFT JOIN `customer_master` ON `customer_master`.`customer_id` = `".$this->foreign_table."`.`customer_id`
		LEFT JOIN `users` ON `users`.`id` = `revise_quatation`.`added_by`
		LEFT JOIN `role_master` ON `role_master`.`role_id` = `users`.`role_id`
		where `revise_quatation`.`revise_id` = '$revise_quatation_id'");
		
		$data['specification'] = Data_model::retrive('specification_master','*',array(),'sequence','ASC');

		$data['quatation_product'] = Data_model::retrive('quatation_product','*',array(),'name','ASC');
		$data['terms_condition'] = Data_model::retrive('terms_condition','*',array(),'term_id','ASC');
		$data['company'] = Data_model::retrive('company','*',array(),'name','ASC');
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		return view($this->view.'/revise_view',$data);
	}
	

	
	public function revise_quatation_print($id,$type,$with_latterhead)
	{
		$data['utility'] = $this->utility;
		$data['msg'] = $this->msgName;
		$revise_quatation_id = $this->utility->decode($id);
		$type = $this->utility->decode($type);
		$data['with_latterhead']= $this->utility->decode($with_latterhead);
		
		$data['result'] = Data_model::db_query("SELECT `revise_quatation`.* ,`product_master`.`product_name`,`customer_master`.`mobile` ,`customer_master`.`email` ,`customer_master`.`name` as `customer_name`,`customer_master`.`company`,`customer_master`.`address` ,`role_master`.`role_name` , `users`.`username` , `users`.`mobile` as `user_mobile` FROM revise_quatation
		LEFT JOIN `".$this->foreign_table."` ON `revise_quatation`.`".$this->foreign_id."` = `".$this->foreign_table."`.`".$this->foreign_id."`
		LEFT JOIN `product_master` ON `product_master`.`product_id` = `".$this->foreign_table."`.`product_id`
		LEFT JOIN `customer_master` ON `customer_master`.`customer_id` = `".$this->foreign_table."`.`customer_id`
		LEFT JOIN `users` ON `users`.`id` = `revise_quatation`.`added_by`
		LEFT JOIN `role_master` ON `role_master`.`role_id` = `users`.`role_id`
		where `revise_quatation`.`revise_id` = '$revise_quatation_id'");
		
		$data['specification'] = Data_model::retrive('specification_master','*',array(),'sequence','ASC');

		$data['quatation_product'] = Data_model::retrive('quatation_product','*',array(),'name','ASC');
		$data['terms_condition'] = Data_model::retrive('terms_condition','*',array(),'term_id','ASC');
		$data['company'] = Data_model::retrive('company','*',array(),'name','ASC');
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		
		if($type == 'print')
		{	
			$pdf = PDF::loadView($this->view.'/revise_print',$data);
			return $pdf->stream('Quatation '.date('d-m-Y H:i:s'));
		}
		else
		{
			$pdf = PDF::loadView($this->view.'/revise_print',$data);
			return $pdf->download('Quatation_'.date('d-m-Y H:i:s').'.pdf');
		}
	}
	
	
	public function delete($id)
	{
		$id = $this->utility->decode($id);
		$where = array($this->primary_id=>$id);
		if(Data_model::remove($this->table,$where))
		{
			$msg = array('success' => $this->msgName.' Deleted Sucessfully');
		}
		else
		{
			$msg = array('error' => 'Some Problem ... Please Try Again');
		}
		return redirect($this->controller)->with($msg);
	}
	public function cancel_inquiry($inquiry_id,$q_master_id)
	{
		$data['utility'] = $this->utility;
		$inquiry_id = $this->utility->decode($inquiry_id);
		$q_master_id = $this->utility->decode($q_master_id);
		$data['inquiry_id'] = $inquiry_id;
		$data['q_master_id'] = $q_master_id;
		$data['action']="update";
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		$data['inquiry_id'] = $inquiry_id;
		$data['q_master_id'] = $q_master_id;
		$data['result'] =DB::table($this->foreign_table)
		->join('customer_master', 'customer_master.customer_id', '=', $this->foreign_table.'.customer_id')
		->join('users', 'users.id', '=', $this->foreign_table.'.added_by')
	   ->select($this->foreign_table.'.*', 'customer_master.*', 'users.username')
	   ->where($this->foreign_id,"=",$inquiry_id )
	   ->get();

		$data['category'] = Data_model::retrive('category_master','*',array('delete_status'=>0),'category_id','DESC');
		$data['product'] = Data_model::retrive('product_master','*',array('delete_status'=>0),'product_name','ASC');
		$data['quatation_product'] = Data_model::retrive('quatation_product','*',array('delete_status'=>0),'name','ASC');
		$data['country'] = Data_model::retrive('country_master','*',array('delete_status'=>0),'country_name','ASC');
		$data['state'] = Data_model::retrive('state_master','*',array('delete_status'=>0),'state_name','ASC');
		$data['city'] = Data_model::retrive('city_master','*',array('delete_status'=>0),'city_name','ASC');
		$data['employee'] = Data_model::retrive('employee','*',array('delete_status'=>0),'name','ASC');
		$data['customer'] = Data_model::retrive('customer_master','*',array(),'customer_id','DESC');
		$data['source'] = Data_model::retrive('source_master','*',array('delete_status'=>0),'source_name','ASC');
		$data['specification'] = Data_model::retrive('specification_master','*',array('delete_status'=>0),'specification_id','DESC');
		return view($this->view.'/inquiry_cancel',$data);
	}
	public function cancel_inquiry_view($inquiry_id)
	{
		$data['utility'] = $this->utility;
		$inquiry_id = $this->utility->decode($inquiry_id);
		$data['inquiry_id'] = $inquiry_id;
		$data['action']="update";
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		$data['inquiry_id'] = $inquiry_id;
		$data['result'] =DB::table($this->foreign_table)
		->join('customer_master', 'customer_master.customer_id', '=', $this->foreign_table.'.customer_id')
		->join('users', 'users.id', '=', $this->foreign_table.'.added_by')
	   ->select($this->foreign_table.'.*', 'customer_master.*', 'users.username')
	   ->where($this->foreign_id,"=",$inquiry_id )
	   ->get();

		$data['category'] = Data_model::retrive('category_master','*',array('delete_status'=>0),'category_id','DESC');
		$data['product'] = Data_model::retrive('product_master','*',array('delete_status'=>0),'product_name','ASC');
		$data['quatation_product'] = Data_model::retrive('quatation_product','*',array('delete_status'=>0),'name','ASC');
		$data['country'] = Data_model::retrive('country_master','*',array('delete_status'=>0),'country_name','ASC');
		$data['state'] = Data_model::retrive('state_master','*',array('delete_status'=>0),'state_name','ASC');
		$data['city'] = Data_model::retrive('city_master','*',array('delete_status'=>0),'city_name','ASC');
		$data['employee'] = Data_model::retrive('employee','*',array('delete_status'=>0),'name','ASC');
		$data['customer'] = Data_model::retrive('customer_master','*',array(),'customer_id','DESC');
		$data['source'] = Data_model::retrive('source_master','*',array('delete_status'=>0),'source_name','ASC');
		$data['specification'] = Data_model::retrive('specification_master','*',array('delete_status'=>0),'specification_id','DESC');
		return view($this->view.'/inquiry_cancel_view',$data);
	}
	public function cancel_inquiry_update(Request $request)
	{
		$inquiry_id = $request->input('inquiry_id');
		$q_master_id = $request->input('q_master_id');
		$cancel_reason = $request->input('cancel_reason');
		$user_id = Session::get('raj_user_id');
		Data_model::restore($this->foreign_table,array('cancel_reason'=>$cancel_reason,'delete_status'=>$user_id),array($this->foreign_id=>$inquiry_id));
		Data_model::restore('quatation_master',array('delete_status'=>$user_id),array('q_master_id'=>$q_master_id));
		$msg = array('success' => 'Inquiry Canceled Sucessfully');
		return redirect($this->controller)->with($msg);
	}
	
}