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

class Inquiry extends Controller
{
	public $table="inquiry";
	public $primary_id="inquiry_id";
	public $msgName = "Inquiry";
	public $view = "inquiry";
	public $controller = "Inquiry";
	public $module_name = "inquiry";
	public $utility;
	public $role_id;
	public $user_id;
	public $zone_id;

	public function __construct()
    {
		if (!Session::has('raj_user_id'))
		{
			$msg = array('error' => 'You Must First Login To Access');
			Redirect::to('/')->send()->with($msg);
		}
		$this->role_id = Session::get('raj_role_id');
		$this->user_id = Session::get('raj_user_id');
		$this->zone_id = Session::get('raj_zone_id');


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

		if($this->role_id != '1')
		{
			$permission = Data_model::get_permission($this->module_name);
			$data['add_permission'] =  $permission[0]->add;
			$data['edit_permission'] =  $permission[0]->edit;
			$data['print_permission'] =  $permission[0]->print;
			$data['delete_permission'] =  $permission[0]->delete;
		}
		$data['utility'] = $this->utility;

		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		$data['primary_id']=$this->primary_id;

		return view($this->view.'/manage',$data);
	}
	public function add()
	{
		$count = DB::select("Select * from `".$this->table."` ORDER BY `i_no` DESC LIMIT 1");
		$num = intval($count[0]->i_no)+intval(1);

		$d= '2018-04-09';


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
		if(!empty($count))
		{
			$data['inq_no'] = 'RW/'. $new_year.'/INQ_'.($num);
		}
		else
		{
			$data['inq_no'] = 'RW/'. $new_year.'/INQ_1';
		}

		$data['action']="insert";
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		$data['primary_id']=$this->primary_id;
		$data['category'] = Data_model::retrive('category_master','*',array('delete_status'=>0),'category_id','DESC');
		$data['product'] = Data_model::retrive('product_master','*',array('delete_status'=>0),'product_name','ASC');
		$data['quatation_product'] = Data_model::retrive('quatation_product','*',array('delete_status'=>0),'name','ASC');
		$data['country'] = Data_model::retrive('country_master','*',array('delete_status'=>0),'country_name','ASC');
		$data['state'] = Data_model::retrive('state_master','*',array('delete_status'=>0),'state_name','ASC');
		$data['city'] = Data_model::retrive('city_master','*',array('delete_status'=>0),'city_name','ASC');
		$data['employee'] = Data_model::retrive('employee','*',array('delete_status'=>0),'name','ASC');

		$data['customer'] = Data_model::retrive('customer_master','*',array(),'customer_id','DESC');
		$data['source'] = Data_model::retrive('source_master','*',array('delete_status'=>0),'source_name','ASC');
		return view($this->view.'/form',$data);
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
		if($customer[0]->mobile_type1 !='' )
		{
			$st.="**".$customer[0]->mobile_type1;
		}
		else
		{
			$st.="**null";
		}
		if($customer[0]->mobile_type2 !='' )
		{
			$st.="**".$customer[0]->mobile_type2;
		}
		else
		{
			$st.="**null";
		}
		if($customer[0]->mobile_type3 !='' )
		{
			$st.="**".$customer[0]->mobile_type3;
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
	public function insert(Request $request)
	{
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
		DB::beginTransaction();
		DB::raw('LOCK TABLES "'.$this->table.'" WRITE');

		$inquiry_no  = $request->input('inquiry_no');
		$explode_i_no=explode('_',$inquiry_no);
		$i_no=$explode_i_no[1];
		$product_id = $request->input('product_id');
		$p_id = implode(",",$request->input('p_id'));
		$p_rate = implode(",",$request->input('rate'));
		$p_qty = implode(",",$request->input('qty'));
		$p_amount = implode(",",$request->input('amount'));
		$category_id = $request->input('category_id');

		$insentive = $request->input('insentive');

		if($insentive == 'no')
		{
			$attended_by = 0;
		}
		else
		{
			$attended_by = $request->input('attended_by');
		}
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

		$customer_prefix = $request->input('customer_prefix');
		$mtype1 = $request->input('mtype1');
		$mtype2 = $request->input('mtype2');
		$mtype3 = $request->input('mtype3');

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

		$user_id = Session::get('raj_user_id');
		$added_time = date("Y-m-d H:i:s");
		$customer_type = $request->input('customer_type');
		$dt = date("Y-m-d");
		$time = date("H:i:s");
		if($customer_type == 'new')
		{
			$customer_data = array('prefix'=>$customer_prefix,'name'=>$name,'address'=>$address,'country_id'=>$country_id,'state_id'=>$state_id,'city_id'=>$city_id,'mobile'=>$mobile,'mobile_2'=>$mobile_2,'mobile_3'=>$mobile_3,'mobile_type1'=>$mtype1,'mobile_type2'=>$mtype2,'mobile_type3'=>$mtype3,'office_address'=>$office_address,'landline'=>$landline,'company'=>$company,'email'=>$email,'email_2'=>$email_2);
			$customer_id = Data_model::store('customer_master',$customer_data);
		}
		else
		{
			$customer_id = $request->input('customer_id');
			$customer_data = array('address'=>$address,'country_id'=>$country_id,'state_id'=>$state_id,'city_id'=>$city_id,'mobile'=>$mobile,'mobile_2'=>$mobile_2,'mobile_3'=>$mobile_3,'mobile_type1'=>$mtype1,'mobile_type2'=>$mtype2,'mobile_type3'=>$mtype3,'office_address'=>$office_address,'landline'=>$landline,'company'=>$company,'email'=>$email,'email_2'=>$email_2);
			Data_model::restore('customer_master',$customer_data,array('customer_id'=>$customer_id));
		}

		$inquiry_data = array('inquiry_date'=>$dt,'inquiry_time'=>$time,/* 'i_no'=>$i_no, 'inquiry_no'=>$inquiry_no, */'product_id'=>$product_id,'p_id'=>$p_id,'p_rate'=>$p_rate,'p_qty'=>$p_qty,'p_amount'=>$p_amount,'category_id'=>$category_id,'insentive'=>$insentive,'attended_by'=>$attended_by,'customer_type'=>$customer_type,'customer_id'=>$customer_id,'source_id'=>$source_id,'project_zone'=>$inquery_zone_id,'project_state'=>$project_state,'project_city'=>$project_city,'remarks'=>$remarks,'added_by'=>$user_id,'added_time'=>$added_time,'year'=>$new_year);

		if($id = Data_model::store($this->table,$inquiry_data))
		{
			/* Year Wise get Inquiry Number */
			$get_num = Data_model::db_query("select * from `inquiry` where `year`='".$new_year."' Order By `i_no` desc limit 1 ");

			if(empty($get_num)){
				$yearNo = '1';
			}
			else{
				$yearNo = $get_num[0]->i_no+1;
			}

			$explode_i_no = explode('_',$inquiry_no);
			$inquiry_number = $explode_i_no[0].'_'.$yearNo;
			Data_model::restore($this->table,array('inquiry_no'=>$inquiry_number,'i_no'=>$yearNo),array('inquiry_id'=>$id));
			Data_model::store('quatation_master',array('inquiry_id'=>$id,'zone_id'=>$inquery_zone_id));
			$msg = array('success' => $this->msgName.' Added Sucessfully');
			DB::commit();
		}
		else
		{
			DB::rollback();
			$msg = array('error' => 'Some Problem ... Please Try Again');
		}

		return redirect($this->controller)->with($msg);
	}

	public function edit($id)
	{
		$data['utility'] = $this->utility;
		$id = $this->utility->decode($id);
		$data['action']="update";
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		$data['primary_id']=$this->primary_id;

		$data['category'] = Data_model::retrive('category_master','*',array('delete_status'=>0),'category_name','ASC');
		$data['product'] = Data_model::retrive('product_master','*',array('delete_status'=>0),'product_name','ASC');
		$data['quatation_product'] = Data_model::retrive('quatation_product','*',array('delete_status'=>0),'name','ASC');
		$data['country'] = Data_model::retrive('country_master','*',array('delete_status'=>0),'country_name','ASC');
		$data['state'] = Data_model::retrive('state_master','*',array('delete_status'=>0),'state_name','ASC');
		$data['city'] = Data_model::retrive('city_master','*',array('delete_status'=>0),'city_name','ASC');
		$data['employee'] = Data_model::retrive('employee','*',array('delete_status'=>0),'name','ASC');


		$data['source'] = Data_model::retrive('source_master','*',array('delete_status'=>0),'source_name','ASC');
		$data['customer'] = Data_model::retrive('customer_master','*',array(),'name','ASC');

		$data['result'] =  DB::table($this->table)
		 	->join('customer_master', 'customer_master.customer_id', '=', $this->table.'.customer_id')
			->select($this->table.'.*', 'customer_master.*')
			->where($this->primary_id,'=',$id )
            ->get();


		return view($this->view.'/form',$data);


	}

	public function editSource($id)
	{
		$data['utility'] = $this->utility;
		$id = $this->utility->decode($id);
		$data['action']="update";
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		$data['primary_id']=$this->primary_id;

		$data['source'] = Data_model::retrive('source_master','*',array('delete_status'=>0),'source_name','ASC');

		$data['result'] =  DB::table($this->table)
		 	->join('customer_master', 'customer_master.customer_id', '=', $this->table.'.customer_id')
			->select($this->table.'.*', 'customer_master.*')
			->where($this->primary_id,'=',$id )
            ->get();


		return view($this->view.'/editForm',$data);

	}

	public function updateInq(Request $request) {
		$id = $request->input("id");
		$source_id = $request->input('source_id');
		$user_id = Session::get('raj_user_id');

		$inquiry_data = array('source_id'=>$source_id,'updated_by'=>$user_id);

		$where = array($this->primary_id=>$id);
		Data_model::restore($this->table,$inquiry_data,$where);
		$msg = array('success' => $this->msgName.' Updated Sucessfully');
		return redirect($this->controller)->with($msg);

	}

	public function update(Request $request)
	{

		$id = $request->input("id");
		$inquiry_no  = $request->input('inquiry_no');
		$explode_i_no=explode('_',$inquiry_no);
		$i_no=$explode_i_no[1];
		$product_id = $request->input('product_id');
		$category_id = $request->input('category_id');
		$p_id = implode(",",$request->input('p_id'));
		$p_rate = implode(",",$request->input('rate'));
		$p_qty = implode(",",$request->input('qty'));
		$p_amount = implode(",",$request->input('amount'));
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

		$customer_prefix = $request->input('customer_prefix');
		$mtype1 = $request->input('mtype1');
		$mtype2 = $request->input('mtype2');
		$mtype3 = $request->input('mtype3');

		$project_state = $request->input('project_state');
		$project_city = $request->input('project_city');
		$remarks = $request->input('remarks');

		$user_id = Session::get('raj_user_id');
		$customer_type = $request->input('customer_type');

		if($customer_type == 'new')
		{
			$customer_data = array('prefix'=>$customer_prefix,'name'=>$name,'address'=>$address,'country_id'=>$country_id,'state_id'=>$state_id,'city_id'=>$city_id,'mobile'=>$mobile,'mobile_2'=>$mobile_2,'mobile_3'=>$mobile_3,'mobile_type1'=>$mtype1,'mobile_type2'=>$mtype2,'mobile_type3'=>$mtype3,'office_address'=>$office_address,'landline'=>$landline,'company'=>$company,'email'=>$email,'email_2'=>$email_2);
		}
		else
		{
			$customer_data = array('address'=>$address,'country_id'=>$country_id,'state_id'=>$state_id,'city_id'=>$city_id,'mobile'=>$mobile,'mobile_2'=>$mobile_2,'mobile_3'=>$mobile_3,'mobile_type1'=>$mtype1,'mobile_type2'=>$mtype2,'mobile_type3'=>$mtype3,'office_address'=>$office_address,'landline'=>$landline,'company'=>$company,'email'=>$email,'email_2'=>$email_2);
		}
		Data_model::restore('customer_master',$customer_data,array('customer_id'=>$customer_id));

		$inquiry_data = array('i_no'=>$i_no,'inquiry_no'=>$inquiry_no,'product_id'=>$product_id,'p_id'=>$p_id,'p_rate'=>$p_rate,'p_qty'=>$p_qty,'p_amount'=>$p_amount,'category_id'=>$category_id,'insentive'=>$insentive,'attended_by'=>$attended_by,'customer_type'=>$customer_type,'customer_id'=>$customer_id,'source_id'=>$source_id,'project_zone'=>$inquery_zone_id,'project_state'=>$project_state,'project_city'=>$project_city,'remarks'=>$remarks,'updated_by'=>$user_id);



		$where = array($this->primary_id=>$id);
		Data_model::restore($this->table,$inquiry_data,$where);
		Data_model::restore('quatation_master',array('zone_id'=>$inquery_zone_id),$where);
		$msg = array('success' => $this->msgName.' Updated Sucessfully');
		return redirect($this->controller)->with($msg);
	}

	public function view($id)
	{
		$data['utility'] = $this->utility;
		$id = $this->utility->decode($id);
		$data['action']="update";
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		$data['primary_id']=$this->primary_id;
		$data['primary_id']=$this->primary_id;
		$data['category'] = Data_model::retrive('category_master','*',array('delete_status'=>0),'category_name','ASC');
		$data['product'] = Data_model::retrive('product_master','*',array('delete_status'=>0),'product_name','ASC');
		$data['quatation_product'] = Data_model::retrive('quatation_product','*',array(),'name','ASC');
		$data['country'] = Data_model::retrive('country_master','*',array('delete_status'=>0),'country_name','ASC');
		$data['state'] = Data_model::retrive('state_master','*',array('delete_status'=>0),'state_name','ASC');
		$data['city'] = Data_model::retrive('city_master','*',array('delete_status'=>0),'city_name','ASC');
		$data['employee'] = Data_model::retrive('employee','*',array('delete_status'=>0),'name','ASC');
		$data['source'] = Data_model::retrive('source_master','*',array('delete_status'=>0),'source_name','ASC');
		$data['customer'] = Data_model::retrive('customer_master','*',array(),'name','ASC');

		$data['result'] =  DB::table($this->table)
		 	->join('customer_master', 'customer_master.customer_id', '=', $this->table.'.customer_id')
			->select($this->table.'.*', 'customer_master.*')
			->where($this->primary_id,'=',$id )
            ->get();

		return view($this->view.'/view',$data);


	}
	public function delete($id)
	{
		$id = $this->utility->decode($id);
		$where = array($this->primary_id=>$id);

		if(Data_model::restore($this->table,array('remove_status'=>$this->user_id),$where))
		{
			$msg = array('success' => $this->msgName.' Deleted Sucessfully');
		}
		else
		{
			$msg = array('error' => 'Some Problem ... Please Try Again');
		}
		return redirect($this->controller)->with($msg);
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
	public function inquiry_active($id)
	{
		$id = $this->utility->decode($id);
		$where = array($this->primary_id=>$id);
		if(Data_model::restore($this->table,array('delete_status'=>0),$where))
		{
			Data_model::restore('quatation_master',array('delete_status'=>0),$where);
			$msg = array('success' => 'Inquiry Active Sucessfully');
		}
		else
		{
			$msg = array('error' => 'Some Problem ... Please Try Again');
		}
		return redirect($this->controller)->with($msg);
	}
	public function get_inq(Request $request)
	{
		$columns = array(
			0 => 'id',
			1 => 'inquiry_no',
			2 => 'inquiry_date',
			3 => 'product_master.product_name',
			4 => 'customer_master.name',
			5 => 'customer_master.mobile',
			6 => 'customer_master.email',
			7 => 'users.username',
		);

		$totalData = DB::table($this->table)
		->join('customer_master', 'customer_master.customer_id', '=', $this->table.'.customer_id')
		->join('category_master', 'category_master.category_id', '=', $this->table.'.category_id')
		->join('product_master', 'product_master.product_id', '=', $this->table.'.product_id')
		->join('users', 'users.id', '=', $this->table.'.added_by')
		->select($this->table.'.*','product_master.product_name','category_master.category_name','customer_master.name','customer_master.prefix','customer_master.mobile','customer_master.mobile_2','customer_master.mobile_3','customer_master.email','customer_master.email_2','users.username')
		->Where('inquiry.first_quatation_id','=',0)
		->Where('inquiry.delete_status','=',0)
		->Where('inquiry.remove_status','=',0)
		->orderBy($this->primary_id,'DESC')
		->count();

		$totalFiltered = $totalData;


		$limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');


		if(empty($request->input('search.value')))
        {
			$result = DB::table($this->table)
					->join('customer_master', 'customer_master.customer_id', '=', $this->table.'.customer_id')
					->join('category_master', 'category_master.category_id', '=', $this->table.'.category_id')
					->join('product_master', 'product_master.product_id', '=', $this->table.'.product_id')
					->join('users', 'users.id', '=', $this->table.'.added_by')
					->select($this->table.'.*','product_master.product_name','category_master.category_name','customer_master.name','customer_master.prefix','customer_master.mobile','customer_master.mobile_2','customer_master.mobile_3','customer_master.email','customer_master.email_2','users.username')
					->Where('inquiry.first_quatation_id','=',0)
					->Where('inquiry.delete_status','=',0)
					->Where('inquiry.remove_status','=',0)
					->offset($start)
					->limit($limit)
					->orderBy('inquiry.inquiry_id','desc')
					->orderBy($order,$dir)
					->get();

        }else{
			$search = $request->input('search.value');

			$result =  DB::table($this->table)
				->join('customer_master', 'customer_master.customer_id', '=', $this->table.'.customer_id')
				->join('category_master', 'category_master.category_id', '=', $this->table.'.category_id')
				->join('product_master', 'product_master.product_id', '=', $this->table.'.product_id')
				->join('users', 'users.id', '=', $this->table.'.added_by')
				->select($this->table.'.*','product_master.product_name','category_master.category_name','customer_master.name','customer_master.prefix','customer_master.mobile','customer_master.mobile_2','customer_master.mobile_3','customer_master.email','customer_master.email_2','users.username')
				->Where('inquiry.first_quatation_id','=',0)
				->Where('inquiry.delete_status','=',0)
				->Where('inquiry.remove_status','=',0)
				->where(function($query) use($search){
					$query->orWhere('inquiry.inquiry_no', 'LIKE',"%{$search}%")
					->orWhere('inquiry.inquiry_date', 'LIKE',"%{$search}%")
					->orWhere('product_master.product_name', 'LIKE',"%{$search}%")
					->orWhere('customer_master.name', 'LIKE',"%{$search}%")
					->orWhere('customer_master.mobile', 'LIKE',"%{$search}%")
					->orWhere('customer_master.mobile_2', 'LIKE',"%{$search}%")
					->orWhere('customer_master.mobile_3', 'LIKE',"%{$search}%")
					->orWhere('customer_master.email', 'LIKE',"%{$search}%")
					->orWhere('customer_master.email_2', 'LIKE',"%{$search}%")
					->orWhere('users.username', 'LIKE',"%{$search}%");
				})
				->offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();


            $totalFiltered = DB::table($this->table)
			->join('customer_master', 'customer_master.customer_id', '=', $this->table.'.customer_id')
			->join('category_master', 'category_master.category_id', '=', $this->table.'.category_id')
			->join('product_master', 'product_master.product_id', '=', $this->table.'.product_id')
			->join('users', 'users.id', '=', $this->table.'.added_by')
			->select($this->table.'.*','product_master.product_name','category_master.category_name','customer_master.name','customer_master.prefix','customer_master.mobile','customer_master.mobile_2','customer_master.mobile_3','customer_master.email','customer_master.email_2','users.username')
			->Where('inquiry.first_quatation_id','=',0)
			->Where('inquiry.delete_status','=',0)
			->Where('inquiry.remove_status','=',0)
			->where(function($query) use($search) {
				$query->orWhere('inquiry.inquiry_no', 'LIKE',"%{$search}%")
				->orWhere('inquiry.inquiry_date', 'LIKE',"%{$search}%")
				->orWhere('product_master.product_name', 'LIKE',"%{$search}%")
				->orWhere('customer_master.name', 'LIKE',"%{$search}%")
				->orWhere('customer_master.mobile', 'LIKE',"%{$search}%")
				->orWhere('customer_master.mobile_2', 'LIKE',"%{$search}%")
				->orWhere('customer_master.mobile_3', 'LIKE',"%{$search}%")
				->orWhere('customer_master.email', 'LIKE',"%{$search}%")
				->orWhere('customer_master.email_2', 'LIKE',"%{$search}%")
				->orWhere('users.username', 'LIKE',"%{$search}%");
			})
			->orderBy($order,$dir)
			->count();
		}


		$data = array();
        if(!empty($result))
        {
			$t=$start+1;
            foreach ($result as $res)
            {
				$customer_mobile=array();
				if($res->mobile !='')
					$customer_mobile[]=$res->mobile;
				if($res->mobile_2 !='')
					$customer_mobile[]=$res->mobile_2;
				if($res->mobile_3 !='')
					$customer_mobile[]=$res->mobile_3;
				$customer_email=array();
				if($res->email !='')
					$customer_email[]=$res->email;
				if($res->email_2 !='')
				$customer_email[]=$res->email_2;

				$action = "<a title='View' class='btn bg-olive btn-flat btn-sm' href='".$this->controller."/view/".$this->utility->encode($res->inquiry_id)."' class='btn btn-warning'><i class='glyphicon glyphicon-eye-open icon-white'></i></a>&nbsp;";

				if($this->role_id == 1)
				{
					$action .= "<a title='Edit' class='btn bg-purple btn-flat btn-sm' href='".$this->controller."/edit/".$this->utility->encode($res->inquiry_id)."' class='btn btn-warning'><i class='glyphicon glyphicon-edit icon-white'></i></a>&nbsp;";

					$action .= "<a class='btn bg-maroon btn-flat btn-sm delete-inq' href='".$this->controller."/delete/".$this->utility->encode($res->inquiry_id)."' class='btn btn-warning'><i class='glyphicon glyphicon-trash icon-white'></i></a>";
				}
				else
				{
					$permission = Data_model::get_permission($this->module_name);
					if($permission[0]->edit == 1)
					{
						$action .= "<a title='Edit' class='btn bg-purple btn-flat btn-sm' href='".$this->controller."/edit/".$this->utility->encode($res->inquiry_id)."' class='btn btn-warning'><i class='glyphicon glyphicon-edit icon-white'></i></a>&nbsp;";
					}
					if($permission[0]->delete == 1)
					{
						$action .= "<a class='btn bg-maroon btn-flat btn-sm delete-inq' href='".$this->controller."/delete/".$this->utility->encode($res->inquiry_id)."' onclick='return confirm(\"Are You Sure To Delete?\");' class='btn btn-warning'><i class='glyphicon glyphicon-trash icon-white'></i></a>";
					}
				}

				$nestedData['id'] = $t;
				$nestedData['inquiry_no'] = $res->inquiry_no;
				$nestedData['inquiry_date'] = date('d-m-Y',strtotime($res->inquiry_date));
				$nestedData['product_name'] = $res->product_name;
				$nestedData['name'] = $res->name;
				$nestedData['mobile'] = implode('<br />',$customer_mobile);
				$nestedData['email'] = implode('<br />',$customer_email);
				$nestedData['username'] = $res->username;

				/* action start */
				$nestedData['action'] = $action;

                $data[] = $nestedData;
				$t++;
            }
		}

		$json_data = array(
			"draw"            => intval($request->input('draw')),
			"recordsTotal"    => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data"            => $data
			);

		echo json_encode($json_data);

	}
	public function get_active_inq(Request $request)
	{
		$columns = array(
			0 => 'id',
			1 => 'inquiry_no',
			2 => 'inquiry_date',
			3 => 'product_master.product_name',
			4 => 'customer_master.name',
			5 => 'customer_master.mobile',
			6 => 'customer_master.email',
			7 => 'users.username',
		);

		$totalData = $result =  DB::table($this->table)
		->join('customer_master', 'customer_master.customer_id', '=', $this->table.'.customer_id')
		->join('category_master', 'category_master.category_id', '=', $this->table.'.category_id')
		->join('product_master', 'product_master.product_id', '=', $this->table.'.product_id')
		->join('users', 'users.id', '=', $this->table.'.added_by')
		->select($this->table.'.*','product_master.product_name','category_master.category_name','customer_master.name','customer_master.prefix','customer_master.mobile','customer_master.mobile_2','customer_master.mobile_3','customer_master.email','customer_master.email_2','users.username')
		->where('inquiry.first_quatation_id','!=',0)
		->where(function($query){
			$query->orWhere('inquiry.delete_status','!=',0)
			->orWhere('inquiry.delete_status','=',0);
		})
		->orderBy($this->primary_id,'DESC')
		->count();

		$totalFiltered = $totalData;


		$limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');


		if(empty($request->input('search.value')))
        {
			$result = $result =  DB::table($this->table)
			->join('customer_master', 'customer_master.customer_id', '=', $this->table.'.customer_id')
			->join('category_master', 'category_master.category_id', '=', $this->table.'.category_id')
			->join('product_master', 'product_master.product_id', '=', $this->table.'.product_id')
			->join('users', 'users.id', '=', $this->table.'.added_by')
			->select($this->table.'.*','product_master.product_name','category_master.category_name','customer_master.name','customer_master.prefix','customer_master.mobile','customer_master.mobile_2','customer_master.mobile_3','customer_master.email','customer_master.email_2','users.username')
			->where('inquiry.first_quatation_id','!=',0)
			->where(function($query) {
				$query->orWhere('inquiry.delete_status','!=',0)
				->orWhere('inquiry.delete_status','=',0);
			})
			->offset($start)
			->limit($limit)
			->orderBy('inquiry.inquiry_id','desc')
			->orderBy($order,$dir)
			->get();



        }else{
			$search = $request->input('search.value');

			$result =  DB::table($this->table)
			->join('customer_master', 'customer_master.customer_id', '=', $this->table.'.customer_id')
			->join('category_master', 'category_master.category_id', '=', $this->table.'.category_id')
			->join('product_master', 'product_master.product_id', '=', $this->table.'.product_id')
			->join('users', 'users.id', '=', $this->table.'.added_by')
			->select($this->table.'.*','product_master.product_name','category_master.category_name','customer_master.name','customer_master.prefix','customer_master.mobile','customer_master.mobile_2','customer_master.mobile_3','customer_master.email','customer_master.email_2','users.username')
			->where('inquiry.first_quatation_id','!=',0)
			->where(function($query) use($search) {
				$query->orWhere('inquiry.inquiry_no', 'LIKE',"%{$search}%")
				->orWhere('inquiry.inquiry_date', 'LIKE',"%{$search}%")
				->orWhere('product_master.product_name', 'LIKE',"%{$search}%")
				->orWhere('customer_master.name', 'LIKE',"%{$search}%")
				->orWhere('customer_master.mobile', 'LIKE',"%{$search}%")
				->orWhere('customer_master.mobile_2', 'LIKE',"%{$search}%")
				->orWhere('customer_master.mobile_3', 'LIKE',"%{$search}%")
				->orWhere('customer_master.email', 'LIKE',"%{$search}%")
				->orWhere('customer_master.email_2', 'LIKE',"%{$search}%")
				->orWhere('users.username', 'LIKE',"%{$search}%");
			})
			->offset($start)
			->limit($limit)
			->orderBy('inquiry.inquiry_id','desc')
			->orderBy($order,$dir)
			->get();


            $totalFiltered = DB::table($this->table)
			->join('customer_master', 'customer_master.customer_id', '=', $this->table.'.customer_id')
			->join('category_master', 'category_master.category_id', '=', $this->table.'.category_id')
			->join('product_master', 'product_master.product_id', '=', $this->table.'.product_id')
			->join('users', 'users.id', '=', $this->table.'.added_by')
			->select($this->table.'.*','product_master.product_name','category_master.category_name','customer_master.name','customer_master.prefix','customer_master.mobile','customer_master.mobile_2','customer_master.mobile_3','customer_master.email','customer_master.email_2','users.username')
			->where('inquiry.first_quatation_id','!=',0)
			->where(function($query) use($search) {
				$query->orWhere('inquiry.inquiry_no', 'LIKE',"%{$search}%")
				->orWhere('inquiry.inquiry_date', 'LIKE',"%{$search}%")
				->orWhere('product_master.product_name', 'LIKE',"%{$search}%")
				->orWhere('customer_master.name', 'LIKE',"%{$search}%")
				->orWhere('customer_master.mobile', 'LIKE',"%{$search}%")
				->orWhere('customer_master.mobile_2', 'LIKE',"%{$search}%")
				->orWhere('customer_master.mobile_3', 'LIKE',"%{$search}%")
				->orWhere('customer_master.email', 'LIKE',"%{$search}%")
				->orWhere('customer_master.email_2', 'LIKE',"%{$search}%")
				->orWhere('users.username','LIKE',"%{$search}%");
			})
			->orderBy('inquiry.inquiry_id','desc')
			->orderBy($order,$dir)
			->count();
		}


		$data = array();
        if(!empty($result))
        {
			$t=$start+1;
            foreach ($result as $res)
            {
				$customer_mobile=array();
				if($res->mobile !='')
					$customer_mobile[]=$res->mobile;
				if($res->mobile_2 !='')
					$customer_mobile[]=$res->mobile_2;
				if($res->mobile_3 !='')
					$customer_mobile[]=$res->mobile_3;

					$customer_email = array();
					if($res->email !='')
					$customer_email[] = $res->email;
					if($res->email_2 !='')
					$customer_email[] = $res->email_2;

				$action = "<a title='View' class='btn bg-olive btn-flat btn-sm' href='".$this->controller."/view/".$this->utility->encode($res->inquiry_id)."' class='btn btn-warning'><i class='glyphicon glyphicon-eye-open icon-white'></i></a>&nbsp;";

				if($this->role_id == 1)
				{
					$action .= "<a title='Edit' class='btn bg-purple btn-flat btn-sm' href='".$this->controller."/editSource/".$this->utility->encode($res->inquiry_id)."' class='btn btn-warning'><i class='glyphicon glyphicon-edit icon-white'></i></a>&nbsp;";
				}
				else
				{
					$permission = Data_model::get_permission($this->module_name);
					if($permission[0]->edit == 1)
					{
						$action .= "<a title='Edit' class='btn bg-purple btn-flat btn-sm' href='".$this->controller."/editSource/".$this->utility->encode($res->inquiry_id)."' class='btn btn-warning'><i class='glyphicon glyphicon-edit icon-white'></i></a>&nbsp;";
					}
				}

				$nestedData['id'] = $t;
				$nestedData['inquiry_no'] = $res->inquiry_no;
				$nestedData['inquiry_date'] = date('d-m-Y',strtotime($res->inquiry_date));
				$nestedData['product_name'] = $res->product_name;
				$nestedData['name'] = $res->name;
				$nestedData['mobile'] = implode('<br />',$customer_mobile);
				$nestedData['email'] = implode('<br />',$customer_email);
				$nestedData['username'] = $res->username;

				/* action start */
				$nestedData['action'] = $action;

                $data[] = $nestedData;
				$t++;
            }
		}

		$json_data = array(
			"draw"            => intval($request->input('draw')),
			"recordsTotal"    => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data"            => $data
			);

		echo json_encode($json_data);

	}
	public function get_cancel_inq(Request $request)
	{
		$columns = array(
			0 => 'inquiry_id',
			1 => 'inquiry_no',
			2 => 'inquiry_date',
			3 => 'product_master.product_name',
			4 => 'customer_master.name',
			5 => 'customer_master.mobile',
			6 => 'customer_master.email',
			7 => 'u1.username',
			8 => 'cancel_date',
			9 => 'u2.username'
		);

		$totalData = $result =  DB::table($this->table)
			->join('customer_master', 'customer_master.customer_id', '=', $this->table.'.customer_id')
			->join('category_master', 'category_master.category_id', '=', $this->table.'.category_id')
			->join('product_master', 'product_master.product_id', '=', $this->table.'.product_id')
			->join('users as u1', 'u1.id', '=', $this->table.'.added_by')
			->join('users as u2', 'u2.id', '=', $this->table.'.delete_status')
			->select($this->table.'.*','product_master.product_name','category_master.category_name','customer_master.name','customer_master.prefix','customer_master.mobile','customer_master.mobile_2','customer_master.mobile_3',
			'customer_master.email','customer_master.email_2','u1.username as inq_person','u2.username as delete_person')
			->Where('inquiry.delete_status','!=',0)
			->Where('inquiry.first_quatation_id','=',0)
			->orderBy($this->primary_id,'DESC')
			->count();

		$totalFiltered = $totalData;


		$limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');


		if(empty($request->input('search.value')))
        {
			$result = DB::table($this->table)
			->join('customer_master', 'customer_master.customer_id', '=', $this->table.'.customer_id')
			->join('category_master', 'category_master.category_id', '=', $this->table.'.category_id')
			->join('product_master', 'product_master.product_id', '=', $this->table.'.product_id')
			->join('users as u1', 'u1.id', '=', $this->table.'.added_by')
			->join('users as u2', 'u2.id', '=', $this->table.'.delete_status')
			->select($this->table.'.*','product_master.product_name','category_master.category_name','customer_master.name','customer_master.prefix','customer_master.mobile','customer_master.mobile_2','customer_master.mobile_3',
			'customer_master.email','customer_master.email_2','u1.username as inq_person','u2.username as delete_person')
			->Where('inquiry.delete_status','!=',0)
			->Where('inquiry.first_quatation_id','=',0)
			->offset($start)
			->limit($limit)
			->orderBy('inquiry.inquiry_id','desc')
			->orderBy($order,$dir)
			->get();

        }else{
			$search = $request->input('search.value');

			$result =  DB::table($this->table)
			->join('customer_master', 'customer_master.customer_id', '=', $this->table.'.customer_id')
			->join('category_master', 'category_master.category_id', '=', $this->table.'.category_id')
			->join('product_master', 'product_master.product_id', '=', $this->table.'.product_id')
			->join('users as u1', 'u1.id', '=', $this->table.'.added_by')
			->join('users as u2', 'u2.id', '=', $this->table.'.delete_status')
			->select($this->table.'.*','product_master.product_name','category_master.category_name','customer_master.name','customer_master.prefix','customer_master.mobile','customer_master.mobile_2','customer_master.mobile_3','customer_master.email','customer_master.email_2','u1.username as inq_person','u2.username as delete_person')
			->Where('inquiry.delete_status','!=',0)
			->Where('inquiry.first_quatation_id','=',0)
			->where(function($query) use($search) {
				$query->orWhere('inquiry.inquiry_no', 'LIKE',"%{$search}%")
				->orWhere('inquiry.inquiry_date', 'LIKE',"%{$search}%")
				->orWhere('product_master.product_name', 'LIKE',"%{$search}%")
				->orWhere('customer_master.name', 'LIKE',"%{$search}%")
				->orWhere('customer_master.mobile', 'LIKE',"%{$search}%")
				->orWhere('customer_master.mobile_2', 'LIKE',"%{$search}%")
				->orWhere('customer_master.mobile_3', 'LIKE',"%{$search}%")
				->orWhere('customer_master.email', 'LIKE',"%{$search}%")
				->orWhere('customer_master.email_2', 'LIKE',"%{$search}%")
				->orWhere('u1.username', 'LIKE',"%{$search}%")
				->orWhere('u2.username', 'LIKE',"%{$search}%");
			})
			->offset($start)
			->limit($limit)
			->orderBy('inquiry.inquiry_id','desc')
			->orderBy($order,$dir)
			->get();


            $totalFiltered = DB::table($this->table)
			->join('customer_master', 'customer_master.customer_id', '=', $this->table.'.customer_id')
			->join('category_master', 'category_master.category_id', '=', $this->table.'.category_id')
			->join('product_master', 'product_master.product_id', '=', $this->table.'.product_id')
			->join('users as u1', 'u1.id', '=', $this->table.'.added_by')
			->join('users as u2', 'u2.id', '=', $this->table.'.delete_status')
			->select($this->table.'.*','product_master.product_name','category_master.category_name','customer_master.name','customer_master.prefix','customer_master.mobile','customer_master.mobile_2','customer_master.mobile_3','customer_master.email','customer_master_email_2','u1.username as inq_person','u2.username as delete_person')
			->Where('inquiry.delete_status','!=',0)
			->Where('inquiry.first_quatation_id','=',0)
			->where(function($query) use($search) {
				$query->orWhere('inquiry.inquiry_no', 'LIKE',"%{$search}%")
				->orWhere('inquiry.inquiry_date', 'LIKE',"%{$search}%")
				->orWhere('product_master.product_name', 'LIKE',"%{$search}%")
				->orWhere('customer_master.name', 'LIKE',"%{$search}%")
				->orWhere('customer_master.mobile', 'LIKE',"%{$search}%")
				->orWhere('customer_master.mobile_2', 'LIKE',"%{$search}%")
				->orWhere('customer_master.mobile_3', 'LIKE',"%{$search}%")
				->orWhere('customer_master.email', 'LIKE',"%{$search}%")
				->orWhere('customer_master.email_2', 'LIKE',"%{$search}%")
				->orWhere('u1.username', 'LIKE',"%{$search}%")
				->orWhere('u2.username', 'LIKE',"%{$search}%");
			})
			->orderBy('inquiry.inquiry_id','desc')
			->orderBy($order,$dir)
			->count();
		}


		$data = array();
        if(!empty($result))
        {
			$t=$start+1;
            foreach ($result as $res)
            {
				$customer_mobile=array();
				if($res->mobile !='')
					$customer_mobile[]=$res->mobile;
				if($res->mobile_2 !='')
					$customer_mobile[]=$res->mobile_2;
				if($res->mobile_3 !='')
					$customer_mobile[]=$res->mobile_3;
					$customer_email=array();
				if($res->email !='')
					$customer_email[]=$res->email;
				if($res->email_2 !='')
					$customer_email[]=$res->email_2;

				$action = "<a title='View' class='btn bg-olive btn-flat btn-sm' href='".$this->controller."/view/".$this->utility->encode($res->inquiry_id)."' ><i class='glyphicon glyphicon-eye-open icon-white'></i></a>&nbsp;";

				if($this->role_id == 1)
				{
					$action .= "<a title='Active' class='btn bg-purple btn-flat btn-sm confirm-delete' href='".$this->controller."/inquiry_active/".$this->utility->encode($res->inquiry_id)."'><i class='fa fa-check'></i></a>";
				} else {
					$permission = Data_model::get_permission($this->module_name);
					if($permission[0]->active == 1)
					{
						$action .= "<a title='Active' class='btn bg-purple btn-flat btn-sm confirm-delete' href='".$this->controller."/inquiry_active/".$this->utility->encode($res->inquiry_id)."'><i class='fa fa-check'></i></a>";
					}
				}

				if($res->cancel_date != ''){
					$cancel_date = date('d-m-Y',strtotime($res->cancel_date));
				}else{
					$cancel_date = '';
				}

				$nestedData['id'] = $t;
				$nestedData['inquiry_no'] = $res->inquiry_no;
				$nestedData['inquiry_date'] = date('d-m-Y',strtotime($res->inquiry_date));
				$nestedData['product_name'] = $res->product_name;
				$nestedData['name'] = $res->name;
				$nestedData['mobile'] = implode('<br />',$customer_mobile);
				$nestedData['email'] = implode('<br />',$customer_email);
				$nestedData['username'] = $res->inq_person;
				$nestedData['cancel_date'] = $cancel_date;
				$nestedData['delete_by'] = $res->delete_person;

				/* action start */
				$nestedData['action'] = $action;

                $data[] = $nestedData;
				$t++;
            }
		}

		$json_data = array(
			"draw"            => intval($request->input('draw')),
			"recordsTotal"    => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data"            => $data
			);

		echo json_encode($json_data);

	}
	public function get_delete_inq(Request $request)
	{
		$columns = array(
			0 => 'inquiry_id',
			1 => 'inquiry_no',
			2 => 'inquiry_date',
			3 => 'product_master.product_name',
			4 => 'customer_master.name',
			5 => 'customer_master.mobile',
			6 => 'customer_master.email',
			7 => 'u1.username',
			8 => 'u2.username'
		);

		$totalData =  DB::table($this->table)
			->join('customer_master', 'customer_master.customer_id', '=', $this->table.'.customer_id')
			->join('category_master', 'category_master.category_id', '=', $this->table.'.category_id')
			->join('product_master', 'product_master.product_id', '=', $this->table.'.product_id')
			->join('users as u1', 'u1.id', '=', $this->table.'.added_by')
			->join('users as u2', 'u2.id', '=', $this->table.'.remove_status')
			->select($this->table.'.*','product_master.product_name','category_master.category_name','customer_master.name','customer_master.prefix','customer_master.mobile','customer_master.mobile_2','customer_master.mobile_3','customer_master.email','customer_master.email_2','u1.username as inq_person','u2.username as delete_person')
			->Where('inquiry.remove_status','!=',0)
			->count();

		$totalFiltered = $totalData;


		$limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');


		if(empty($request->input('search.value')))
        {
			$result = DB::table($this->table)
			->join('customer_master', 'customer_master.customer_id', '=', $this->table.'.customer_id')
			->join('category_master', 'category_master.category_id', '=', $this->table.'.category_id')
			->join('product_master', 'product_master.product_id', '=', $this->table.'.product_id')
			->join('users as u1', 'u1.id', '=', $this->table.'.added_by')
			->join('users as u2', 'u2.id', '=', $this->table.'.remove_status')
			->select($this->table.'.*','product_master.product_name','category_master.category_name','customer_master.name','customer_master.prefix','customer_master.mobile','customer_master.mobile_2','customer_master.mobile_3','customer_master.email','customer_master.email_2','u1.username as inq_person','u2.username as delete_person')
			->Where('inquiry.remove_status','!=',0)
			->offset($start)
			->limit($limit)
			->orderBy('inquiry.inquiry_id','desc')
			->orderBy($order,$dir)
			->get();

        }else{
			$search = $request->input('search.value');

			$result =  DB::table($this->table)
			->join('customer_master', 'customer_master.customer_id', '=', $this->table.'.customer_id')
			->join('category_master', 'category_master.category_id', '=', $this->table.'.category_id')
			->join('product_master', 'product_master.product_id', '=', $this->table.'.product_id')
			->join('users as u1', 'u1.id', '=', $this->table.'.added_by')
			->join('users as u2', 'u2.id', '=', $this->table.'.remove_status')
			->select($this->table.'.*','product_master.product_name','category_master.category_name','customer_master.name','customer_master.prefix','customer_master.mobile','customer_master.mobile_2','customer_master.mobile_3',
			'customer_master.email','customer_master.email_2','u1.username as inq_person','u2.username as delete_person')
			->Where('inquiry.remove_status','!=',0)
			->where(function($query) use($search) {
				$query->orWhere('inquiry.inquiry_no', 'LIKE',"%{$search}%")
				->orWhere('inquiry.inquiry_date', 'LIKE',"%{$search}%")
				->orWhere('product_master.product_name', 'LIKE',"%{$search}%")
				->orWhere('customer_master.name', 'LIKE',"%{$search}%")
				->orWhere('customer_master.mobile', 'LIKE',"%{$search}%")
				->orWhere('customer_master.mobile_2', 'LIKE',"%{$search}%")
				->orWhere('customer_master.mobile_3', 'LIKE',"%{$search}%")
				->orWhere('customer_master.email', 'LIKE',"%{$search}%")
				->orWhere('customer_master.email_2', 'LIKE',"%{$search}%")
				->orWhere('u1.username', 'LIKE',"%{$search}%")
				->orWhere('u2.username', 'LIKE',"%{$search}%");
			})
			->offset($start)
			->limit($limit)
			->orderBy('inquiry.inquiry_id','desc')
			->orderBy($order,$dir)
			->get();


            $totalFiltered = DB::table($this->table)
			->join('customer_master', 'customer_master.customer_id', '=', $this->table.'.customer_id')
			->join('category_master', 'category_master.category_id', '=', $this->table.'.category_id')
			->join('product_master', 'product_master.product_id', '=', $this->table.'.product_id')
			->join('users as u1', 'u1.id', '=', $this->table.'.added_by')
			->join('users as u2', 'u2.id', '=', $this->table.'.remove_status')
			->select($this->table.'.*','product_master.product_name','category_master.category_name','customer_master.name','customer_master.prefix','customer_master.mobile','customer_master.mobile_2','customer_master.mobile_3',
			'customer_master.email','customer_master.email_2','u1.username as inq_person','u2.username as delete_person')
			->Where('inquiry.remove_status','!=',0)
			->where(function($query) use($search) {
				$query->orWhere('inquiry.inquiry_no', 'LIKE',"%{$search}%")
				->orWhere('inquiry.inquiry_date', 'LIKE',"%{$search}%")
				->orWhere('product_master.product_name', 'LIKE',"%{$search}%")
				->orWhere('customer_master.name', 'LIKE',"%{$search}%")
				->orWhere('customer_master.mobile', 'LIKE',"%{$search}%")
				->orWhere('customer_master.mobile_2', 'LIKE',"%{$search}%")
				->orWhere('customer_master.mobile_3', 'LIKE',"%{$search}%")
				->orWhere('customer_master.email', 'LIKE',"%{$search}%")
				->orWhere('customer_master.email_2', 'LIKE',"%{$search}%")
				->orWhere('u1.username', 'LIKE',"%{$search}%")
				->orWhere('u2.username', 'LIKE',"%{$search}%");
			})
			->offset($start)
			->limit($limit)
			->orderBy('inquiry.inquiry_date','desc')
			->orderBy($order,$dir)
			->count();
		}


		$data = array();
        if(!empty($result))
        {
			$t=$start+1;
            foreach ($result as $res)
            {
				$customer_mobile=array();
				if($res->mobile !='')
					$customer_mobile[]=$res->mobile;
				if($res->mobile_2 !='')
					$customer_mobile[]=$res->mobile_2;
				if($res->mobile_3 !='')
					$customer_mobile[]=$res->mobile_3;
				$customer_email=array();
					if($res->email !='')
					$customer_email[]=$res->email;
					if($res->email_2 !='')
					$customer_email[]=$res->email_2;
					$action = "<a title='View' class='btn bg-olive btn-flat btn-sm' href='".$this->controller."/view/".$this->utility->encode($res->inquiry_id)."' ><i class='glyphicon glyphicon-eye-open icon-white'></i></a>&nbsp;";

				$nestedData['id'] = $t;
				$nestedData['inquiry_no'] = $res->inquiry_no;
				$nestedData['inquiry_date'] = date('d-m-Y',strtotime($res->inquiry_date));
				$nestedData['product_name'] = $res->product_name;
				$nestedData['name'] = $res->name;
				$nestedData['mobile'] = implode('<br />',$customer_mobile);
				$nestedData['email'] = implode('<br />',$customer_email);
				$nestedData['username'] = $res->inq_person;
				$nestedData['delete_by'] = $res->delete_person;

				/* action start */
				$nestedData['action'] = $action;

                $data[] = $nestedData;
				$t++;
            }
		}

		$json_data = array(
			"draw"            => intval($request->input('draw')),
			"recordsTotal"    => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data"            => $data
			);

		echo json_encode($json_data);

	}
}
