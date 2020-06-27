<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Data_model;
use App\Libraries\Utility;

use Session;
use DB;
use Redirect;
use Validator;


class Hot_list extends Controller
{
	public $table="follow_up";
	public $primary_id="follow_up_id";
	public $foreign_table = "inquiry";
	public $foreign_id = "inquiry_id";
	public $msgName = "Hot List";
	public $view = "hot_list";
	public $controller = "Hot_list";
	public $utility;
	public $role_id;
	public $zone_id;
	public $user_id;
	public $module_name = "hot_list";

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
		$this->zone_id = Session::get('raj_zone_id');
	}

	public function index($id)
	{
		if($this->role_id != '1')
		{
			$permission = Data_model::get_permission($this->module_name);
			$data['add_permission'] =  $permission[0]->add;
			$data['edit_permission'] =  $permission[0]->edit;
			$data['print_permission'] =  $permission[0]->print;
			$data['delete_permission'] =  $permission[0]->delete;
		}
		$data['role_id'] = $this->role_id;
		$data['utility'] = $this->utility;


		$holiday = Data_model::retrive('minimum_days','*',array('type'=>'holiday'),'type');
		$h_day=$holiday[0]->days;
		$day_array = array(0=>'Sunday',1=>'Monday',2=>'Tuesday',3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday');
		if(date('l')==date('l',strtotime('next day',strtotime("$day_array[$h_day] 01:15"))))
		{
			$quatation_date = date('Y-m-d',strtotime(date('Y-m-d')." - 2 day"));
		}
		else
		{
			$quatation_date = date('Y-m-d',strtotime(date('Y-m-d')." - 1 day"));
		}
		if($id == '-1')
		{
			if($this->role_id==1)
			{
				$data['inquiry'] =  DB::table($this->foreign_table)
					->select($this->foreign_table.'.*','customer_master.*','quatation.quatation_id','quatation.quatation_no','quatation.follow_up_status')
					->join('customer_master', 'customer_master.customer_id', '=', $this->foreign_table.'.customer_id')
					->join('quatation', 'quatation.'.$this->foreign_id, '=', $this->foreign_table.'.'.$this->foreign_id)
					->where('quatation.quatation_date','=',$quatation_date)
					->Where('inquiry.order_status','=',0)
					->Where('inquiry.regret_status','=',0)
					->get();
			}
			else
			{
				$where1 ="find_in_set(`quatation`.zone_id,'$this->zone_id') AND `quatation`.quatation_date = '".$quatation_date."' ";

				$data['inquiry'] =  DB::table($this->foreign_table)
					->select($this->foreign_table.'.*','customer_master.*','quatation.quatation_id','quatation.quatation_no','quatation.follow_up_status')
					->join('customer_master', 'customer_master.customer_id', '=', $this->foreign_table.'.customer_id')
					->join('quatation', 'quatation.'.$this->foreign_id, '=', $this->foreign_table.'.'.$this->foreign_id)
					->whereRaw($where1)
					->Where('inquiry.order_status','=',0)
					->Where('inquiry.regret_status','=',0)
					->get();
			}
			$data['auto_click'] = 'no';
		}
		else
		{
			$id = $this->utility->decode($id);
			$inq_id = $id;

			if($this->role_id==1)
			{
				$data['inquiry'] =  DB::table($this->foreign_table)
					->select($this->foreign_table.'.*','customer_master.*','quatation.quatation_id','quatation.quatation_no','quatation.follow_up_status')
					->join('customer_master', 'customer_master.customer_id', '=', $this->foreign_table.'.customer_id')
					->join('quatation', 'quatation.'.$this->foreign_id, '=', $this->foreign_table.'.'.$this->foreign_id)
					->Where('inquiry.inquiry_id','=',$inq_id)
					->get();
			}
			else
			{
				$where1 ="find_in_set(`quatation`.zone_id,'$this->zone_id') AND `inquiry`.inquiry_id = ".$inq_id." ";

				$data['inquiry'] =  DB::table($this->foreign_table)
					->select($this->foreign_table.'.*','customer_master.*','quatation.quatation_id','quatation.quatation_no','quatation.follow_up_status')
					->join('customer_master', 'customer_master.customer_id', '=', $this->foreign_table.'.customer_id')
					->join('quatation', 'quatation.'.$this->foreign_id, '=', $this->foreign_table.'.'.$this->foreign_id)
					->whereRaw($where1)
					->get();
			}
			$data['auto_click'] = 'yes';
		}


		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		$data['primary_id']=$this->primary_id;
		return view($this->view.'/form',$data);
	}

	public function hot_list_data(Request $request)
	{
		$week_number = $request->week_number;
		$hotlistYear = $request->hotlistYear;


		if($this->role_id == 1)
		{
			$data['hot_list_data'] =  DB::table($this->foreign_table)
				->select($this->foreign_table.'.*','customer_master.*','quatation.quatation_id','quatation.quatation_no','quatation.follow_up_status','hot_list_notify.*')
				->join('hot_list_notify','hot_list_notify.inquiry_id', '=', $this->foreign_table.'.inquiry_id')
				->join('customer_master','customer_master.customer_id', '=', $this->foreign_table.'.customer_id')
				->join('quatation', 'quatation.'.$this->foreign_id, '=', $this->foreign_table.'.'.$this->foreign_id)
				->where('hot_list_notify.week_no','=',$week_number)
				->where('hot_list_notify.year','=',$hotlistYear)
				->Where('inquiry.order_status','=',0)
				->Where('inquiry.regret_status','=',0)
				->orderBy('quatation.q_no','DESC')
				->get();
		}
		else
		{
			$where1 ="find_in_set(`quatation`.zone_id,'$this->zone_id')";

			$data['hot_list_data'] =  DB::table($this->foreign_table)
				->select($this->foreign_table.'.*','customer_master.*','quatation.quatation_id','quatation.quatation_no','quatation.follow_up_status','hot_list_notify.*')
				->join('hot_list_notify','hot_list_notify.inquiry_id', '=', $this->foreign_table.'.inquiry_id')
				->join('customer_master', 'customer_master.customer_id', '=', $this->foreign_table.'.customer_id')
				->join('quatation', 'quatation.'.$this->foreign_id, '=', $this->foreign_table.'.'.$this->foreign_id)
				->where('hot_list_notify.week_no','=',$week_number)
				->whereRaw($where1)
				->Where('inquiry.order_status','=',0)
				->Where('inquiry.regret_status','=',0)
				->get();

		}

		$data['utility'] = $this->utility;
		$data['controller_name'] = $this->controller;
		$data['msgName'] = $this->msgName;
		$data['primary_id']=$this->primary_id;
		return view($this->view.'/hot_list_data',$data);
	}

	public function get_inquiry_data(Request $request)
	{
		if($this->role_id != '1')
		{
			$permission = Data_model::get_permission($this->module_name);
			$data['add_permission'] =  $permission[0]->add;
		}
		$data['role_id'] = $this->role_id;
		$inquiry_id = $this->utility->decode($request->enquiry_id);
		$data['minimum_call'] = Data_model::retrive('minimum_days','*',array('type'=>'call'),'id','DESC');
		$data['minimum_email'] = Data_model::retrive('minimum_days','*',array('type'=>'mail'),'id','DESC');
		$data['minimum_daily'] = Data_model::retrive('minimum_days','*',array('type'=>'daily'),'id','DESC');
		$data['holiday_count'] = Data_model::retrive('minimum_days','*',array('type'=>'holiday'),'id','DESC');
		$data['minimum_call_email'] = Data_model::retrive('minimum_days','*',array('type'=>'call_email'),'id','DESC');
		$data['minimum_day_whatsapp'] = Data_model::retrive('minimum_days','*',array('type'=>'whatsapp'),'id','DESC');
		$data['follow_ups'] =  DB::table($this->table)
            ->select($this->table.'.*','followup_way_master.followup_way_name','users.username')
		 	->join('followup_way_master', $this->table.'.followup_way_id', '=', 'followup_way_master.followup_way_id')
			->join('users', $this->table.'.added_by', '=', 'users.id')
			->where($this->table.'.'.$this->foreign_id,'=',$inquiry_id)
			->orderBy('follow_up_id','DESC')
            ->get();

		$data['result'] =  DB::table($this->foreign_table)
            ->select($this->foreign_table.'.*','customer_master.*','quatation.quatation_id','quatation.quatation_no','quatation.quatation_date','quatation.total_amount','quatation.added_by as quatation_person')
		 	->join('customer_master', 'customer_master.customer_id', '=', $this->foreign_table.'.customer_id')
			->join('quatation', 'quatation.'.$this->foreign_id, '=', $this->foreign_table.'.'.$this->foreign_id)
			->where($this->foreign_table.'.'.$this->foreign_id,'=',$inquiry_id)
            ->get();

		$data['country'] = Data_model::retrive('country_master','*',array('delete_status'=>0),'country_name','ASC');
		$data['state'] = Data_model::retrive('state_master','*',array('delete_status'=>0),'state_name','ASC');
		$data['city'] = Data_model::retrive('city_master','*',array('delete_status'=>0),'city_name','ASC');
		$data['employee'] = Data_model::retrive('employee','*',array('delete_status'=>0),'name','ASC');
		$data['customer'] = Data_model::retrive('customer_master','*',array(),'customer_id','DESC');
		$data['source'] = Data_model::retrive('source_master','*',array('delete_status'=>0),'source_name','ASC');
		$data['product'] = Data_model::retrive('product_master','*',array('delete_status'=>0),'product_name','ASC');
		$data['quatation_product'] = Data_model::retrive('quatation_product','*',array('delete_status'=>0),'name','ASC');
		$data['category'] = Data_model::retrive('category_master','*',array('delete_status'=>0),'category_id','DESC');
		$data['client_category_master'] = Data_model::retrive('client_category_master','*',array('delete_status'=>0),'client_category_name','ASC');
		$data['quatation_status_master'] = Data_model::retrive('quatation_status_master','*',array('delete_status'=>0),'quatation_status_name','ASC');
		$data['visit_details_master'] = Data_model::retrive('visit_details_master','*',array('delete_status'=>0),'visit_details_name','ASC');
		$data['payment_mode_master'] = Data_model::retrive('payment_mode_master','*',array('delete_status'=>0),'payment_mode_name','ASC');
		$data['raw_water_master'] = Data_model::retrive('raw_water_master','*',array('delete_status'=>0),'raw_water_name','ASC');
		$data['water_report_master'] = Data_model::retrive('water_report_master','*',array('delete_status'=>0),'water_report_name','ASC');
		$data['project_division_master'] = Data_model::retrive('project_division_master','*',array('delete_status'=>0),'project_division_name','ASC');
		$data['planning_stage_master'] = Data_model::retrive('planning_stage_master','*',array('delete_status'=>0),'planning_stage_name','ASC');
		$data['power_supply_master'] = Data_model::retrive('power_supply_master','*',array('delete_status'=>0),'power_supply_name','ASC');
		$data['site_status_master'] = Data_model::retrive('site_status_master','*',array('delete_status'=>0),'site_status_name','ASC');
		$data['followup_way_master'] = Data_model::retrive('followup_way_master','*',array('delete_status'=>0),'followup_way_name','ASC');
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		$data['primary_id']=$this->primary_id;

		$data['utility'] = $this->utility;

		$data['document_detail'] =  DB::table('document_detail')
            ->select('document_detail.*','users.username','document_name_master.document_name as d')
		 	->join('users','users.id', '=', 'document_detail.added_by')
		 	->join('document_name_master','document_name_master.id', '=', 'document_detail.document_detail')
			->where('document_detail.'.$this->foreign_id,'=',$inquiry_id)
			->orderBy('document_attached_date','DESC')
            ->get();


		$data['visiting_detail'] =  DB::table('visitor_detail')
            ->select('visitor_detail.*','employee.name','address_master.office_name','visitor_form_detail.form_no as vsf_no')
		 	->join('employee','visitor_detail.visitor_attended_by', '=', 'employee.emp_id')
		 	->join('address_master','address_master.id', '=', 'visitor_detail.visit_at')
		 	->join('visitor_form_detail','visitor_form_detail.id', '=', 'visitor_detail.form_no')
			->where('visitor_detail.'.$this->foreign_id,'=',$inquiry_id)
			->orderBy('visit_date','DESC')
            ->get();

		$data['quotation_no'] = Data_model::retrive('quatation','*',array('inquiry_id'=>$inquiry_id),'quatation_id','ASC');

		$data['revise_quot_num'] = Data_model::retrive('revise_quatation','*',array('inquiry_id'=>$inquiry_id),'revise_id','DESC');

		$data['order_by'] = Data_model::retrive('employee','*',array('role_id'=>5,'delete_status'=>0),'emp_id','DESC');

		$data['address_master'] = Data_model::retrive('address_master','*',array(),'office_name','ASC');
		$data['client_category'] = Data_model::retrive('client_category_master','*',array(),'client_category_name','ASC');
		$data['visit_at'] = Data_model::retrive('address_master','*',array(),'office_name','ASC');

		$data_get_emp_id =  DB::table('quatation')
            ->select('quatation.added_by','users.emp_id')
		 	->join('users','quatation.added_by', '=', 'users.id')
			->where('quatation.'.$this->foreign_id,'=',$inquiry_id)
			->orderBy('quatation_date','DESC')
            ->get();

		//check order book or not
		$check_order = Data_model::retrive('order_book','*',array('inquiry_id'=>$inquiry_id),'order_id','ASC');

		if(! empty($check_order))
		{
			$data['order_book'] = 'yes';
		}
		else
		{
			$data['order_book'] = 'no';
		}

		$data['quotation_pers'] = $data_get_emp_id[0]->emp_id;
		$data['inq_id'] = $inquiry_id;
		$data['document_name_master'] = Data_model::retrive('document_name_master','*',array('delete_status'=>'0'),'id','ASC');
		$data['catalog']  = Data_model::retrive('catalog_master','*',array('delete_status'=>0),'id','DESC');

		$get_hot_list = Data_model::retrive('hot_list_notify','*',array('inquiry_id'=>$inquiry_id,'follow_up_status'=>1),'id','ASC');

		if(! empty($get_hot_list))
		{
			$data['hot_list_btn'] = 1;
		}
		else
		{
			$data['hot_list_btn'] = 0;
		}
		return view($this->view.'/inquiry_data1',$data);
	}
	public function visitor_form_no(Request $request)
	{
		$data_get_visitor = Data_model::db_query("select id from `visitor_form_detail` Order By id desc limit 1  ");
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

		if(! empty($data_get_visitor))
		{
			$num = 'RW/'. $new_year.'/VS_'.($data_get_visitor[0]->id + 1);
		}
		else
		{
			$num = 'RW/'. $new_year.'/VS_1';
		}
		echo $num;
	}
	public function send_mail(Request $request)
	{
		$inquiry_id = $request->input('inquiry_id');

		$company_detail = Data_model::retrive('company','*',array(),'name','ASC');
		$role_nm = Data_model::retrive('role_master','*',array('role_id'=>$this->role_id),'role_id','ASC');
		$user_detail = Data_model::retrive('users','*',array('id'=>$this->user_id),'id','ASC');



		// get product detail
		$get_product = Data_model::db_query("select inquiry.inquiry_id,product_master.product_name from inquiry INNER JOIN product_master ON product_master.product_id = inquiry.product_id where inquiry.inquiry_id = ".$inquiry_id." ");

		if(! empty($get_product))
		{
			$product_name = $get_product[0]->product_name;
		}
		else
		{
			$product_name = '';
		}

		$msg = '<span style="color:#001f3f;font-size:16px;font-weight:bold">Inquiry For</span> : <strong>'.$product_name.'</strong><br>';

		$get_zone_id = Data_model::retrive('quatation_master','*',array('inquiry_id'=>$inquiry_id),'q_master_id','ASC');
		$zone_id = $get_zone_id[0]->zone_id;

		$get_zone_email = Data_model::retrive('follow_up_email','*',array('zone'=>$zone_id),'email','ASC');

		$zone_email = $get_zone_email[0]->email;


		$send_email_id = $request->input('send_email_id');
		$title = $request->input('title');

		if( !empty($title))
		{
			$get_mail_format = Data_model::retrive('email_master','*',array('email_title'=>'quotation'),'id','DESC');

			if(! empty($get_mail_format))
			{
				$mail_format = $get_mail_format[0]->email_format;
			}
			else
			{
				$mail_format = '';
			}

			$email = $send_email_id;
			$files = array();
			for($i=0; $i<count($title); $i++)
			{
				$get_file_nm  = Data_model::retrive('catalog_master','*',array('id'=>$title[$i]),'id','DESC');
				$files[] = $get_file_nm[0]->catalog_file;
			}
			$email_to = $email;
			//$email_from = $zone_email;
			$email_from = "info@angelspearlinfotech.com";
			$email_subject = "CRM Raj Water";

			$fileatt_name = 'Raj Water Quotation';
			$fileatt = 'external/catalog/'.$files[0];

			$semi_rand = md5(time());
			$fileatt_type = "application/pdf"; // File Type
			$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

			$headers = "From: ".$email_from;
			$headers .= "\nMIME-Version: 1.0\n" .
			"Content-Type: multipart/mixed;\n" .
			" boundary=\"{$mime_boundary}\"";

			// set email message......................

			$email_message = "Raj Water Quotation.<bR>";
			$email_message = $msg."<bR>";
			$email_message .= $mail_format."<bR>";

			$email_message .= '<br><br><strong>Thanks and Regards</strong> <br> '.$company_detail[0]->name.'<br>'.$user_detail[0]->username.'<br>'.$role_nm[0]->role_name.'<br>'.$user_detail[0]->mobile;


			$email_message .= "Attachment:<br>";// Message that the email has in it

			$email_message .= "This is a multi-part message in MIME format.\n\n" .

			"--{$mime_boundary}\n" .

			"Content-Type:text/html; charset=\"iso-8859-1\"\n" .

			"Content-Transfer-Encoding: 7bit\n\n" .

			$email_message .= "\n\n";
			$email_message .= "--{$mime_boundary}\n";

			for($x=0;$x<count($files);$x++)
			{
				$path = 'external/catalog/'.$files[$x];
				$file = fopen($path,"rb");
				$data = fread($file,filesize($path));
				fclose($file);
				$data = chunk_split(base64_encode($data));


				$email_message .= "Content-Type: {$fileatt_type};\n" . " name=\"$fileatt_name\"\n" .
				  "Content-Disposition: attachment;\n" . " filename=\"$fileatt_name\"\n" .
				  "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
				$email_message .= "--{$mime_boundary}\n";
			}

			$data1['utility'] = $this->utility;
			$data1['with_latterhead'] = 'yes';

			$data1['result'] = Data_model::db_query("SELECT `quatation`.* ,`product_master`.`product_name`,`customer_master`.`mobile` ,`customer_master`.`mobile_2`,`customer_master`.`mobile_3`,`customer_master`.`prefix`,`customer_master`.`email`,`customer_master`.`email_2` ,`customer_master`.`name` as `customer_name` ,`customer_master`.`company`,`customer_master`.`address`,`customer_master`.`country_id`,`role_master`.`role_name` , `users`.`username` ,`employee`.`name` as `employee_name` ,`users`.`mobile` as `user_mobile` FROM `quatation`
			LEFT JOIN `".$this->foreign_table."` ON `quatation`.`".$this->foreign_id."` = `".$this->foreign_table."`.`".$this->foreign_id."`
			LEFT JOIN `product_master` ON `product_master`.`product_id` = `".$this->foreign_table."`.`product_id`
			LEFT JOIN `customer_master` ON `customer_master`.`customer_id` = `".$this->foreign_table."`.`customer_id`
			LEFT JOIN `users` ON `users`.`id` = `quatation`.`added_by`
			LEFT JOIN `role_master` ON `role_master`.`role_id` = `users`.`role_id`
			LEFT JOIN `employee` ON `employee`.`emp_id` = `users`.`emp_id`
			where `quatation`.`quatation_id` = '".$quatation_id."'");

			$get_cur = Data_model::retrive('country_master','*',array('country_id'=>$data1['result'][0]->country_id),'country_name','DESC');

			if(! empty($get_cur))
			{
				$cur_type = $get_cur[0]->cur_type;
			}
			else
			{
				$cur_type = 'inr';
			}
			$data1['cur_type'] = $cur_type;

			$data1['specification'] = Data_model::retrive('specification_master','*',array(),'sequence','ASC');
			$data1['quatation_product'] = Data_model::retrive('quatation_product','*',array(),'name','ASC');
			$data1['terms_condition'] = Data_model::retrive('terms_condition','*',array(),'term_id','ASC');
			$data1['company'] = Data_model::retrive('company','*',array(),'name','ASC');
			$data1['controller_name'] = $this->controller;

			$data1['letter_head'] = Data_model::retrive('letterhead_master','*',array(),'letterhead_name','ASC');

			$pdf = PDF::loadView($this->view.'/print',$data1);
			$pdf = $pdf->stream('Quatation '.date('d-m-Y H:i:s'));

			$attachment = chunk_split(base64_encode($pdf));

			$email_message .= "Content-Type: {$fileatt_type};\n" . " name=\"$fileatt_name\"\n" .
				  "Content-Disposition: attachment;\n" . " filename=\"$fileatt_name\"\n" .
				  "Content-Transfer-Encoding: base64\n\n" . $attachment . "\n\n";
			$email_message .= "--{$mime_boundary}\n";

			$sent = mail($email_to, $email_subject, $email_message, $headers);

			if($sent == 1)
			{
				$response[0]="success";
			}
			else
			{
				$response[0]="Mail Not Sent.";
			}

		}
		else
		{
			$response[0]="Please Select Title";
		}

		echo json_encode($response);
		exit;
	}
	public function send_reminder_mail(Request $request)
	{
		$inquiry_id = $request->input('inquiry_id');
		$next_f_date = date('Y-m-d',strtotime($request->input('next_f_date')));
		$quatation_id = $request->input('quatation_id');
		$send_reminder_mail = $request->input('send_reminder_mail');
		$last_followup_id = $request->input('last_followup_id');

		$company_detail = Data_model::retrive('company','*',array(),'name','ASC');
		$role_nm = Data_model::retrive('role_master','*',array('role_id'=>$this->role_id),'role_id','ASC');
		$user_detail = Data_model::retrive('users','*',array('id'=>$this->user_id),'id','ASC');


		// get product detail
		$get_product = Data_model::db_query("select inquiry.inquiry_id,product_master.product_name from inquiry INNER JOIN product_master ON product_master.product_id = inquiry.product_id where inquiry.inquiry_id = ".$inquiry_id." ");

		if(! empty($get_product))
		{
			$product_name = $get_product[0]->product_name;
		}
		else
		{
			$product_name = '';
		}

		$msg = '<span style="color:#001f3f;font-size:16px;font-weight:bold">Inquiry For</span> : <strong>'.$product_name.'</strong><br>';

		$get_zone_id = Data_model::retrive('quatation_master','*',array('inquiry_id'=>$inquiry_id),'q_master_id','ASC');
		$zone_id = $get_zone_id[0]->zone_id;

		$get_zone_email = Data_model::retrive('follow_up_email','*',array('zone'=>$zone_id),'email','ASC');

		$zone_email = $get_zone_email[0]->email;

		if( !empty($send_reminder_mail))
		{
			$get_mail_format = Data_model::retrive('email_master','*',array('email_title'=>'follow_up'),'id','DESC');

			if(! empty($get_mail_format))
			{
				$mail_format = $get_mail_format[0]->email_format;
			}
			else
			{
				$mail_format = '';
			}
			$subject = 'Reminder Mail';
			$to = $send_reminder_mail;
			$email_from = $zone_email;

			$semi_rand = md5(time());
			$fileatt_type = "application/pdf"; // File Type
			$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

			$content="Raj Water Raminder Mail";
			$headers = "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			$headers .= "From: ".$email_from;
			$headers .= "\nMIME-Version: 1.0\n" .
			"Content-Type: multipart/mixed;\n" .
			" boundary=\"{$mime_boundary}\"";
			$message = '<html><body>';
			$message .= '<span style="color:#001f3f;font-size:16px;font-weight:bold">Subject : </span>'.$subject.'<br>';
			$message .= $msg;
			$message .= $mail_format;
			$message .= '<br><br><strong>Thanks and Regards</strong> <br> '.$company_detail[0]->name.'<br>'.$user_detail[0]->username.'<br>'.$role_nm[0]->role_name.'<br>'.$user_detail[0]->mobile;
			$message .= '</body></html>';


			$message .= "Attachment:<br>";
			$message .= "This is a multi-part message in MIME format.\n\n" .
			"--{$mime_boundary}\n" .
			"Content-Type:text/html; charset=\"iso-8859-1\"\n" .
			"Content-Transfer-Encoding: 7bit\n\n" .
			$message .= "\n\n";
			$message .= "--{$mime_boundary}\n";
			if($request->hasFile('attachment'))
			{
				$file = $request->file('attachment');
				$fileatt_name = $file->getClientOriginalName();
				$attachment = chunk_split(base64_encode($file));

				$message .= "Content-Type: {$fileatt_type};\n" . " name=\"$fileatt_name\"\n" .
					  "Content-Disposition: attachment;\n" . " filename=\"$fileatt_name\"\n" .
					  "Content-Transfer-Encoding: base64\n\n" . $attachment . "\n\n";
				$message .= "--{$mime_boundary}\n";
			}
			$sent = mail($to, $subject, $message, $headers);

			if($sent == 1)
			{
				$response[0]="success";
				$date = date('Y-m-d');
				//$next_follow_date = date('Y-m-d',strtotime($date." + 6 day"));
				$follow_up_data = array(
					'inquiry_id'=>$inquiry_id,
					'quatation_id'=>$quatation_id,
					'detail' => $send_reminder_mail,
					'follow_up_date' => $date,
					'follow_up_time' => date('H:i:s'),
					'followup_way_id' => 2,
					'day_followup_date' => 6,
					'next_followup_date' => $next_f_date,
					'call_receive_remark' => 'Reminder Mail',
					'added_by'=>$this->user_id);

				$follow_up_id = Data_model::store('follow_up',$follow_up_data);

				Data_model::restore('follow_up',array('follow_up_status'=>1),array('follow_up_id'=>$last_followup_id));

				$follow_ups =  DB::table($this->table)
				->select($this->table.'.*','followup_way_master.followup_way_name','users.username')
				->join('followup_way_master', $this->table.'.followup_way_id', '=', 'followup_way_master.followup_way_id')
				->join('users', $this->table.'.added_by', '=', 'users.id')
				->where($this->table.'.'.$this->foreign_id,'=',$inquiry_id)
				->orderBy('follow_up_id','DESC')
				->get();

				$returnHTML = view($this->view.'/follow_up_data')->with('follow_ups', $follow_ups)->render();
				$response[1]= $returnHTML;
			}
			else
			{
				$response[0]="Mail Not Sent.";
			}

		}
		else
		{
			$response[0]="Please Select Email";
		}

		echo json_encode($response);
		exit;


	}
	public function get_quatation_date(Request $request)
	{
		$order_quot_no = explode("-",$request->input('order_quot_no'));

		$quot_id = $order_quot_no[1];

		if($order_quot_no[0] = 'q')
		{
			$gate_date = Data_model::retrive('quatation','*',array('quatation_id'=>$quot_id),'quatation_id','ASC');

			if(! empty($gate_date))
			{
				$date = date('d-m-Y',strtotime($gate_date[0]->quatation_date));
			}
			else
			{
				$date = '';
			}
		}
		else
		{
			$gate_date = Data_model::retrive('revise_quatation','*',array('revise_id'=>$quot_id),'revise_id','ASC');

			if(! empty($gate_date))
			{
				$date = date('d-m-Y',strtotime($gate_date[0]->revise_date));
			}
			else
			{
				$date = '';
			}
		}

		echo $date;exit;
	}

	public function customer_update(Request $request)
	{

		$inquiry_id  = $request->input('inquiry_id');
		$quatation_id = $request->input('quatation_id');

		//Customer Data
		$customer_id = $request->input('customer_id');
		$company = $request->input('company');
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
		$landline = $request->input('landline');
		$email = $request->input('email');
		$email_2 = $request->input('email_2');
		$address = $request->input('address');
		$office_address = $request->input('office_address');

		$mtype1 = $request->input('mtype1');
		$mtype2 = $request->input('mtype2');
		$mtype3 = $request->input('mtype3');

		$customer_data = array(
			'address'=>$address,
			'country_id'=>$country_id,
			'state_id'=>$state_id,
			'city_id'=>$city_id,
			'mobile'=>$mobile,
			'mobile_2'=>$mobile_2,
			'mobile_3'=>$mobile_3,
			'mobile_type1'=>$mtype1,
			'mobile_type2'=>$mtype2,
			'mobile_type3'=>$mtype3,
			'office_address'=>$office_address,
			'landline'=>$landline,
			'company'=>$company,
			'email'=>$email,
			'email_2'=>$email_2);
		Data_model::restore('customer_master',$customer_data,array('customer_id'=>$customer_id));
		//Customer Data End


		//Inquiry Data Update
		$inquiry_remarks = $request->input('inquiry_remarks');
		$client_category_id = $request->input('client_category_id');
		$quatation_status_id = $request->input('quatation_status_id');
		$visit_details_id = $request->input('visit_details_id');
		$project_value = $request->input('project_value');
		$payment_mode_id = $request->input('payment_mode_id');
		$raw_water_id = $request->input('raw_water_id');
		$water_report_id = $request->input('water_report_id');
		$project_division_id = $request->input('project_division_id');
		$planning_stage_id = $request->input('planning_stage_id');
		$power_supply_id = $request->input('power_supply_id');
		$site_status_id = $request->input('site_status_id');
		$customer_remarks = $request->input('customer_remarks');

		$inquiry_data = array(
			'remarks'=>$inquiry_remarks,
			'client_category_id'=>$client_category_id,
			'quatation_status_id'=>$quatation_status_id,
			'visit_details_id'=>$visit_details_id,
			'project_value'=>$project_value,
			'payment_mode_id'=>$payment_mode_id,
			'raw_water_id'=>$raw_water_id,
			'water_report_id'=>$water_report_id,
			'project_division_id'=>$project_division_id,
			'planning_stage_id'=>$planning_stage_id,
			'power_supply_id'=>$power_supply_id,
			'site_status_id'=>$site_status_id,
			'customer_remarks'=>$customer_remarks);
		//Inquiry Data Update End
		Data_model::restore($this->foreign_table,$inquiry_data,array($this->foreign_id=>$inquiry_id));
		echo 'success';
		//$msg = array('success' => 'Customer Details Added Sucessfully');
		//return redirect($this->controller)->with($msg);
	}

	public function revise_send(Request $request)
	{
		$revice_quot_person  = $request->input('revice_quot_person');
		$revice_remark = $request->input('revice_remark');
		$quot_no = $request->input('quot_no');
		$inquiry_id = $request->input('inquiry_id');
		$date = date('Y-m-d H:i:s');


		$data = array(
			'emp_id'=>$revice_quot_person,
			'inquiry_id'=>$inquiry_id,
			'quotation_no'=>$quot_no,
			'remark'=>$revice_remark,
			'added_by'=>$this->user_id,
			'added_date'=>$date);

		if(Data_model::store('revise_quotation_notify',$data))
		{
			$response[0] = 'success';
		}
		else
		{
			$response[0] = 'unsuccess';
		}
		echo json_encode($response);
		exit;
	}
	public function prise_issue(Request $request)
	{
		$quot_person  = $request->input('prisse_issue_person');
		$remark = $request->input('price_remark');
		$quot_no = $request->input('quot_no');
		$inquiry_id = $request->input('inquiry_id');
		$date = date('Y-m-d H:i:s');


		$data = array(
			'emp_id'=>$quot_person,
			'inquiry_id'=>$inquiry_id,
			'quotation_no'=>$quot_no,
			'remark'=>$remark,
			'added_by'=>$this->user_id,
			'added_date'=>$date);

		if(Data_model::store('prise_issue_notify',$data))
		{
			$response[0] = 'success';
		}
		else
		{
			$response[0] = 'unsuccess';
		}
		echo json_encode($response);
		exit;
	}
	public function hot_list(Request $request)
	{
		$week_no  = $request->input('acc_week');
		$hot_list_from_date  = date('Y-m-d',strtotime($request->input('hot_list_from_date')));
		$hot_list_to_date  = date('Y-m-d',strtotime($request->input('hot_list_to_date')));
		$remark = $request->input('hot_list_remark');
		$quot_no = $request->input('quot_no');

		$date = date('Y-m-d H:i:s');

		$data = array(
			'quotation_no'=>$quot_no,
			'week_no'=>$week_no,
			'from_date'=>$hot_list_from_date,
			'to_date'=>$hot_list_to_date,
			'remark'=>$remark,
			'added_by'=>$this->user_id,
			'added_date'=>$date);

		if(Data_model::store('hot_list_notify',$data))
		{
			$response[0] = 'success';
		}
		else
		{
			$response[0] = 'unsuccess';
		}
		echo json_encode($response);
		exit;
	}
	public function order_book(Request $request)
	{
		$quot_no  = $request->input('order_quot_no');
		$order_by  = $request->input('order_by');
		$quot_num = explode("-",$quot_no);

		if($quot_num[0] == 'q')
		{
			$quot_id = $quot_num[1];
			$rq_id = 0;
		}
		else
		{
			$rq_id = $quot_num[1];
			$quot_id = 0;
		}


		$order_quot_date  = date('Y-m-d',strtotime($request->input('order_quot_date')));
		$order_book_date  = date('Y-m-d',strtotime($request->input('order_book_date')));
		$order_day  = $request->input('order_day');
		$order_remark  = $request->input('order_remark');
		$inquiry_id  = $request->input('inquiry_id');

		$data = array('quot_id'=>$quot_id,
					'rquot_id'=>$rq_id,
					'inquiry_id'=>$inquiry_id,
					'quot_date'=>$order_quot_date,
					'order_book_date'=>$order_book_date,
					'order_day'=>$order_day,
					'remark'=>$order_remark,
					'order_by'=>$order_by,
					'added_by'=>$this->user_id);

		if($or_id = Data_model::store('order_book',$data))
		{
			Data_model::restore('inquiry',array('order_status'=>1),array('inquiry_id'=>$inquiry_id));
			$response[0] = 'success';
		}
		else
		{
			$response[0] = 'unsuccess';
		}
		echo json_encode($response);
		exit;
	}

	public function regret_add(Request $request)
	{
		$quot_no  = $request->input('quot_no');
		$regret_allot  = $request->input('regret_allot');
		$regret_remark  = $request->input('regret_remark');
		$inquiry_id  = $request->input('inquiry_id');

		$data = array('quot_no'=>$quot_no,
					'emp_id'=>$regret_allot,
					'regret_remark'=>$regret_remark,
					'added_by'=>$this->user_id);

		if(Data_model::store('followup_regret',$data))
		{
			Data_model::restore('inquiry',array('regret_status'=>1),array('inquiry_id'=>$inquiry_id));
			$response[0] = 'success';
		}
		else
		{
			$response[0] = 'unsuccess';
		}
		echo json_encode($response);
		exit;
	}

	public function send_address(Request $request)
	{
		$follow_up_address_id  = implode(",",$request->input('follow_up_address'));
		$customer_mno  = $request->input('follow_up_mobile');

		// get address
		$get_all_address = Data_model::db_query("select * from `address_master` where id IN (".$follow_up_address_id.") AND `delete_status`= 0 ");

		$address_send = '';
		foreach($get_all_address as $key=>$val)
		{
			$address_send .= $val->address;
			$address_send .= '.';
		}

		for($r=0; $r<count($customer_mno); $r++)
		{
			if($customer_mno[$r] != '')
			{
				$postdata = http_build_query(
						array('username' => 'rajwater',
							'password' => 'rajwater@321',
							'senderid' => 'RWTGPL',
							'route' => '1',
							'unicode' => '2',
							'number' => $customer_mno[$r],
							'message' => $address_send
						)
				);

				$opts = array('http' =>
					array(
						'method'  => 'POST',
						'header'  => 'Content-type: application/x-www-form-urlencoded',
						'content' => $postdata
						)
				);

				$context  = stream_context_create($opts);
				$result = trim(file_get_contents('http://buzz.azmarq.com/http-api.php', false, $context));
			}
		}

		$response[0] = 'success';

		echo json_encode($response);
		exit;
	}
	public function follow_up_add(Request $request)
	{

		$inquiry_id  = $request->input('inquiry_id');
		$quatation_id = $request->input('quatation_id');
		$follow_up_detail_mobile = $request->input('follow_up_detail_mobile');
		$follow_up_detail_email = $request->input('follow_up_detail_email');
		if($follow_up_detail_mobile == '')
		{
			$follow_up_detail = $follow_up_detail_email;
		}
		else
		{
			$follow_up_detail = $follow_up_detail_mobile;
		}

		//Follow Up Data start
		$follow_up_date = date('Y-m-d');
		$follow_up_time = date('H:i:s');
		$followup_way_id = $request->input('followup_way_id');
		$call_type = $request->input('call_type');
		$call_receive_remark = $request->input('call_receive_remark');

		if($call_type == 'Call Not Receive')
		{
			$call_receive_remark = $call_type;
		}
		if($call_type == 'Call Not Connected')
		{
			$call_receive_remark = $call_type;
		}
		if($call_type == 'Call Cut')
		{
			$call_receive_remark = $call_type;
		}
		if($call_type == 'Switch Off')
		{
			$call_receive_remark = $call_type;
		}
		if($call_type == 'Number Busy')
		{
			$call_receive_remark = $call_type;
		}
		if($call_type == 'Call Receive Then Call Cut')
		{
			$call_receive_remark = $call_type;
		}

		$day_followup_date = $request->input('day_followup_date');
		$next_followup_date = date('Y-m-d',strtotime($request->input('next_followup_date')));
		$last_follow_up_id = $request->input('last_follow_id');
		//Follow Up Data End



		$follow_up_data = array(
				'inquiry_id'=>$inquiry_id,
				'quatation_id'=>$quatation_id,
				'detail' => $follow_up_detail,
				'follow_up_date' => $follow_up_date,
				'follow_up_time' => $follow_up_time,
				'followup_way_id' => $followup_way_id,
				'day_followup_date' => $day_followup_date,
				'next_followup_date' => $next_followup_date,
				'call_receive_remark' => $call_receive_remark,
				'hot_list_follow_up_status' =>1,
				'added_by'=>$this->user_id);
		if($follow_up_id = Data_model::store($this->table,$follow_up_data))
		{
			Data_model::restore('hot_list_notify',array('follow_up_status'=>1),array('inquiry_id'=>$inquiry_id));

			$follow_ups =  DB::table($this->table)
            ->select($this->table.'.*','followup_way_master.followup_way_name','users.username')
		 	->join('followup_way_master', $this->table.'.followup_way_id', '=', 'followup_way_master.followup_way_id')
			->join('users', $this->table.'.added_by', '=', 'users.id')
			->where($this->table.'.'.$this->foreign_id,'=',$inquiry_id)
			->orderBy('follow_up_id','DESC')
            ->get();

			$returnHTML = view($this->view.'/follow_up_data')->with('follow_ups', $follow_ups)->render();

			$response[0]="success";
			$response[1]= $returnHTML;
			$response[2]= $call_receive_remark;

		}
		else
		{
			$response[0]="unsuccess";
		}
		echo json_encode($response);
		exit;
	}

	public function download($id)
	{
		$document_file_nm = $id;
		$ext = pathinfo($document_file_nm,PATHINFO_EXTENSION);
		$download_path = 'external/document_data/'.$document_file_nm;
		$headers = array('Content-Type:'.$ext,);
		$file_name = "Document.".$ext;

		return response()->download($download_path,$file_name,$headers);
	}

	public function document_add(Request $request)
	{

		$inquiry_id  = $request->input('inquiry_id');
		$quatation_id = $request->input('quatation_id');

		//Document Data start
		$document_attached_date = date('Y-m-d');
		$document_attached_time = date('H:i:s');
		$document_detail = $request->input('doc_detail');
		$document_attached_employee = $this->user_id;
		if($request->hasFile('attached_document'))
		{
			$file = $request->file('attached_document');
			$file_ext = $file->getClientOriginalExtension();
			$path = 'external/document_data/';
			if(!is_dir($path))
			{
				 mkdir($path, 0777, TRUE);
			}
			$document_file_nm= time().'.'.$file_ext;
			$file->move($path,$document_file_nm);
		}
		else
		{
			$document_file_nm='';
		}
		//Document Data End

		$document_data = array(
			'inquiry_id'=>$inquiry_id,
			'quatation_id'=>$quatation_id,
			'document_attached_date'=>$document_attached_date,
			'document_attached_time'=>$document_attached_time,
			'document_detail'=>$document_detail,
			'document_name'=>$document_file_nm,
			'added_by'=>$this->user_id);

		if(Data_model::store('document_detail',$document_data))
		{
			$document_detail =  DB::table('document_detail')
            ->select('document_detail.*','users.username','document_name_master.document_name as d')
		 	->join('users','users.id', '=', 'document_detail.added_by')
		 	->join('document_name_master','document_name_master.id', '=', 'document_detail.document_detail')
			->where('document_detail.'.$this->foreign_id,'=',$inquiry_id)
			->orderBy('document_attached_date','DESC')
            ->get();

			$returnHTML = view($this->view.'/document_detail')->with('document_detail', $document_detail)->render();

			$response[0] = 'success';
			$response[1] = $returnHTML;
		}
		else
		{
			$response[0] = 'unsuccess';
		}
		echo json_encode($response);
		exit;
	}


	public function visitor_add(Request $request)
	{

		$inquiry_id  = $request->input('inquiry_id');
		$quatation_id = $request->input('quatation_id');
		$visit_category = $request->input('visit_category');
		$form_no_visit = $request->input('form_no_visit');

		// get from no through id
		$get_visitor_id = Data_model::retrive('visitor_form_detail','*',array('form_no'=>$form_no_visit),'id','DESC');

		$form_id = $get_visitor_id[0]->id;

		//Visitor Detail Data start
		$visit_date = date('Y-m-d');
		$visit_time = date('H:i:s');
		$visit_at = $request->input('visit_at');
		$visitor_attended_by = $request->input('visitor_attended_by');
		$visit_status = $request->input('visit_status');
		//Visitor Detail Data End

		$visitor_data = array(
			'inquiry_id'=>$inquiry_id,
			'form_no'=>$form_id,
			'quatation_id'=>$quatation_id,
			'visit_date'=>$visit_date,
			'visit_time'=>$visit_time,
			'visit_at'=>$visit_at,
			'visitor_attended_by'=>$visitor_attended_by,
			'visit_category'=>$visit_category,
			'visit_status'=>$visit_status,
			'added_by'=>$this->user_id);


		if(Data_model::store('visitor_detail',$visitor_data))
		{
			$visiting_detail =  DB::table('visitor_detail')
            ->select('visitor_detail.*','employee.name','address_master.office_address')
		 	->join('employee','visitor_detail.visitor_attended_by', '=', 'employee.emp_id')
		 	->join('address_master','address_master.id', '=', 'visitor_detail.visit_at')
			->where('visitor_detail.'.$this->foreign_id,'=',$inquiry_id)
			->orderBy('visit_date','DESC')
            ->get();

			$returnHTML = view($this->view.'/visiting_detail')->with('visiting_detail', $visiting_detail)->render();

			$response[0] = 'success';
			$response[1] = $returnHTML;
		}
		else
		{
			$response[0] = 'unsuccess';
		}

		echo json_encode($response);
		exit;
	}

	public function visitor_form_data(Request $request)
	{
		$inquiry_id = $request->input('inquiry_id');
		$quatation_id = $request->input('quatation_id');
		$visitor_quot_no = $request->input('visitor_quot_no');
		$visitor_quot_date = date('Y-m-d',strtotime($request->input('visitor_quot_date')));
		$visitor_form_no = $request->input('visitor_form_no');
		$visitor_visit_date = date('Y-m-d',strtotime($request->input('visitor_visit_date')));
		$visitor_visit_time = date('H:i:s',strtotime($request->input('visitor_visit_time')));
		$visitor_visit_at = $request->input('visitor_visit_at');
		$visitor_attended_by = $request->input('visitor_attended_by');
		$visitor_client_name1 = $request->input('visitor_client_name1');
		$visitor_client_mobile1 = $request->input('visitor_client_mobile1');
		$visitor_client_email1 = $request->input('visitor_client_email1');
		$visitor_client_name2 = $request->input('visitor_client_name2');
		$visitor_client_mobile2 = $request->input('visitor_client_mobile2');
		$visitor_client_email2 = $request->input('visitor_client_email2');
		$visitor_client_name3 = $request->input('visitor_client_name3');
		$visitor_client_mobile3 = $request->input('visitor_client_mobile3');
		$visitor_client_email3 = $request->input('visitor_client_email3');
		$visitor_company_name = $request->input('visitor_company_name');
		$visitor_landline_no = $request->input('visitor_landline_no');
		$visitor_address = $request->input('visitor_address');
		$visitor_office_address = $request->input('visitor_office_address');
		$visitor_country = $request->input('visitor_country');
		$visitor_state = $request->input('visitor_state');
		$visitor_city = $request->input('visitor_city');
		$visitor_land = $request->input('visitor_land');
		$visitor_site_status = $request->input('visitor_site_status');
		$visitor_power_supply = $request->input('visitor_power_supply');
		$visitor_water_source = $request->input('visitor_water_source');
		$visitor_water_report = $request->input('visitor_water_report');
		$visitor_project_value = $request->input('visitor_project_value');
		$visitor_payment_mode = $request->input('visitor_payment_mode');
		$visitor_quatation_status = $request->input('visitor_quatation_status');
		$visitor_inquiry_for = $request->input('visitor_inquiry_for');
		$visitor_inquiry_type = $request->input('visitor_inquiry_type');
		$visitor_product_detail = $request->input('visitor_product_detail');
		$visitor_client_category = $request->input('visitor_client_category');
		$visitor_follow_up = $request->input('visitor_follow_up');
		$visitor_remark = $request->input('visitor_remark');

		$data = array('quotation_no'=>$visitor_quot_no,'quot_date'=>$visitor_quot_date,'visit_date'=>$visitor_visit_date,'visit_time'=>$visitor_visit_time,'visit_in'=>$visitor_visit_at,'attend_by'=>$visitor_attended_by,'client_name1'=>$visitor_client_name1,'client_name2'=>$visitor_client_name2,'client_name3'=>$visitor_client_name3,'client_mobile1'=>$visitor_client_mobile1,'client_mobile2'=>$visitor_client_mobile2,'client_mobile3'=>$visitor_client_mobile3,'client_email1'=>$visitor_client_email1,'client_email2'=>$visitor_client_email2,'client_email3'=>$visitor_client_email3,'company_name'=>$visitor_company_name,'landline_no'=>$visitor_landline_no,'address'=>$visitor_address,'office_address'=>$visitor_office_address,'country'=>$visitor_country,'state'=>$visitor_state,'city'=>$visitor_city,'land'=>$visitor_land,'site_status'=>$visitor_site_status,'power'=>$visitor_power_supply,'water_source'=>$visitor_water_source,'water_report'=>$visitor_water_report,'project_value'=>$visitor_project_value,'payment_mode'=>$visitor_payment_mode,'quatation_status'=>$visitor_quatation_status,'product_id'=>$visitor_inquiry_for,'inq_type'=>$visitor_inquiry_type,'product_detail'=>$visitor_product_detail,'client_category'=>$visitor_client_category,'next_follow_up'=>$visitor_follow_up,'remark'=>$visitor_remark);

		if($visitor_follow_up != '')
		{
			$day = $visitor_follow_up;
		}
		else
		{
			$day = 'no';
		}
		if($ins_id = Data_model::store('visitor_form_detail',$data))
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

			$visit_num = 'RW/'. $new_year.'/VS_'.($ins_id);

			Data_model::restore('visitor_form_detail',array('form_no'=>$visit_num),array('id'=>$ins_id));

			$data_visit = array('inquiry_id'=>$inquiry_id,
							'form_no'=>$ins_id,
							'quatation_id'=>$quatation_id,
							'visit_date'=>$visitor_visit_date,
							'visit_time'=>$visitor_visit_time,
							'visit_at'=>$visitor_visit_at,
							'visitor_attended_by'=>$visitor_attended_by,
							'added_by'=>$this->user_id);

			Data_model::store('visitor_detail',$data_visit);

			if($day != 'no')
			{
				$next_follow_up = date('Y-m-d',strtotime($visitor_visit_date." + ".$day." day"));

				$date = date('Y-m-d');
				$time = date('H:i:s');

				$follow_up_data = array('inquiry_id'=>$inquiry_id,
									'quatation_id'=>$quatation_id,
									'follow_up_date'=>$date,
									'follow_up_time'=>$time,
									'followup_way_id'=>0,
									'day_followup_date'=>$day,
									'next_followup_date'=>$next_follow_up,
									'call_receive_remark'=>$visitor_remark,
									'follow_up_status'=>0,
									'detail'=>$visitor_client_mobile1,
									'added_by'=>$this->user_id);
				Data_model::store('follow_up',$follow_up_data);
			}

			$visiting_detail =  DB::table('visitor_detail')
            ->select('visitor_detail.*','employee.name','address_master.office_name','visitor_form_detail.form_no as vsf_no')
		 	->join('employee','visitor_detail.visitor_attended_by', '=', 'employee.emp_id')
		 	->join('address_master','address_master.id', '=', 'visitor_detail.visit_at')
		 	->join('visitor_form_detail','visitor_form_detail.id', '=', 'visitor_detail.form_no')
			->where('visitor_detail.'.$this->foreign_id,'=',$inquiry_id)
			->orderBy('visit_date','DESC')
            ->get();

			$returnHTML = view($this->view.'/visiting_detail')->with('visiting_detail', $visiting_detail)->render();

			$response[0] = 'success';
			$response[1] = $returnHTML;
		}
		else
		{
			$response[0] = 'unsuccess';
		}

		echo json_encode($response);
		exit;
	}
	public function get_state(Request $request)
	{
		$country_id = $request->input('country_id');
		$state = Data_model::retrive('state_master','*',array('country_id'=>$country_id),'state_name');
		echo '<option>Select</option>';
		foreach($state as $key=>$val)
		{
			echo '<option value='.$val->state_id.'>'.$val->state_name.'</option>';
		}
	}
	public function get_city(Request $request)
	{
		$state_id = $request->input('state_id');
		$city = Data_model::retrive('city_master','*',array('state_id'=>$state_id),'city_name');
		echo '<option>Select</option>';
		foreach($city as $key=>$val)
		{
			echo '<option value='.$val->city_id.'>'.$val->city_name.'</option>';
		}
	}

	public function visitor_view($id)
	{
		$data['controller_name'] = $this->controller;
		$data['msgName'] = 'Visitor';
		$data['site_status_master'] = Data_model::retrive('site_status_master','*',array('delete_status'=>0),'site_status_name','ASC');
		$data['power_supply_master'] = Data_model::retrive('power_supply_master','*',array('delete_status'=>0),'power_supply_name','ASC');
		$data['raw_water_master'] = Data_model::retrive('raw_water_master','*',array('delete_status'=>0),'raw_water_name','ASC');
		$data['water_report_master'] = Data_model::retrive('water_report_master','*',array('delete_status'=>0),'water_report_name','ASC');
		$data['payment_mode_master'] = Data_model::retrive('payment_mode_master','*',array('delete_status'=>0),'payment_mode_name','ASC');
		$data['quatation_status_master'] = Data_model::retrive('quatation_status_master','*',array('delete_status'=>0),'quatation_status_name','ASC');
		$data['product'] = Data_model::retrive('product_master','*',array('delete_status'=>0),'product_name','ASC');
		$data['category'] = Data_model::retrive('category_master','*',array('delete_status'=>0),'category_id','DESC');
		$data['quatation_product'] = Data_model::retrive('quatation_product','*',array('delete_status'=>0),'name','ASC');
		$data['country'] = Data_model::retrive('country_master','*',array('delete_status'=>0),'country_name','ASC');
		$data['state'] = Data_model::retrive('state_master','*',array('delete_status'=>0),'state_name','ASC');
		$data['city'] = Data_model::retrive('city_master','*',array('delete_status'=>0),'city_name','ASC');
		$data['employee'] = Data_model::retrive('employee','*',array('delete_status'=>0),'name','ASC');
		$data['visit_at'] = Data_model::retrive('address_master','*',array(),'office_name','ASC');
		$data['result'] = Data_model::db_query("select * from `visitor_form_detail` where id=".$id." ");
		return view($this->view.'/view_visitor',$data);
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
		$data['category'] = Data_model::retrive('category_master','*',array('delete_status'=>0),'category_id','DESC');
		$data['product'] = Data_model::retrive('product_master','*',array('delete_status'=>0),'product_id','DESC');

		$data['state'] = Data_model::retrive('state_master','*',array('delete_status'=>0),'state_id','DESC');
		$data['city'] = Data_model::retrive('city_master','*',array('delete_status'=>0),'city_id','DESC');
		$data['employee'] = Data_model::retrive('employee','*',array('delete_status'=>0),'emp_id','DESC');
		$data['client_category'] = Data_model::retrive('client_category_master','*',array(),'client_category_id','DESC');

		$data['source'] = Data_model::retrive('source_master','*',array(),'source_id','DESC');

		$data['result'] =  DB::table($this->table)
		 	->join('customer_master', 'customer_master.customer_id', '=', $this->table.'.customer_id')
            ->join('category_master', 'category_master.category_id', '=', $this->table.'.category_id')
            ->join('product_master', 'product_master.product_id', '=', $this->table.'.product_id')
			->join('state_master', 'state_master.state_id', '=', 'customer_master.state_id')
			->join('city_master', 'city_master.city_id', '=', 'customer_master.city_id')

			->join('client_category_master', 'client_category_master.client_category_id', '=', $this->table.'.client_category_id')
			->join('source_master', 'source_master.source_id', '=', $this->table.'.source_id')
            ->select($this->table.'.*', 'customer_master.*','category_master.*', 'product_master.*', 'state_master.*', 'city_master.*', 'client_category_master.*', 'source_master.*')
			  ->where($this->primary_id,'=',$id )
            ->get();



		return view($this->view.'/view',$data);


	}
	public function delete($id)
	{
		$id = $this->utility->decode($id);

		$where = array($this->primary_id=>$id);
		if(Data_model::remove($this->table,$where))
		{
			$msg = array('success' => $this->msgName.' Deleted Successfully');
		}
		else
		{
			$msg = array('error' => 'Some Problem ... Please Try Again');
		}
		return redirect($this->controller)->with($msg);
	}
	//For Customer Mobile Dublicate Validation
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
	//For Customer Email Dublicate Validation
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
}
