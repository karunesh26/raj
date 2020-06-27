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



class Job_card extends Controller

{

	public $table="inquiry";

	public $primary_id="inquiry_id";

	public $field = "Job_card";

	public $msgName = "Job Card";

	public $view = "job_card";

	public $controller = "Job_card";

	public $module_name = "job_card";

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



		$data['result'] = Data_model::db_query("select order_book.order_book_date,order_book.order_id,inquiry.product_id,inquiry.inquiry_id,quatation.quatation_no,quatation.quatation_id,revise_quatation.revise_quatation_no,revise_quatation.revise_id,customer_master.name,customer_master.mobile,customer_master.mobile_2,customer_master.mobile_3,customer_master.email,customer_master.email_2,country_master.country_name,state_master.state_name,city_master.city_name,product_master.product_name,u2.username as quot_user,employee.name as order_by_user from order_book

		INNER JOIN inquiry ON inquiry.inquiry_id = order_book.inquiry_id

		LEFT JOIN revise_quatation ON revise_quatation.revise_id = order_book.rquot_id

		LEFT JOIN quatation ON quatation.quatation_id = order_book.quot_id

		INNER JOIN customer_master ON customer_master.customer_id = inquiry.customer_id

		INNER JOIN country_master ON country_master.country_id = customer_master.country_id

		INNER JOIN product_master ON product_master.product_id = inquiry.product_id

		LEFT JOIN state_master ON state_master.state_id = customer_master.state_id

		LEFT JOIN city_master ON city_master.city_id = customer_master.city_id

		LEFT JOIN employee ON employee.emp_id = order_book.order_by

		LEFT JOIN users as u2 ON u2.id = quatation.added_by where `order_book`.cancel_by = 0 Order By order_book.order_book_date desc");





		$data['controller_name'] = $this->controller;

		$data['msgName']=$this->msgName;

		return view($this->view.'/manage',$data);

	}



	public function get_search_data(Request $request)

	{

		$search  = trim($request->input('search'));

		$from_date  = date('Y-m-d',strtotime($request->input('from_date')));

		$to_date  = date('y-m-d',strtotime($request->input('to_date')));

		$data['utility'] = $this->utility;

		$search_keyword = '%'.$search.'%';



		$from_d = $request->input('from_date');

		$to_d = $request->input('to_date');



		if($from_d != '' && $to_d != '')

		{

			$data['result'] = Data_model::db_query("select order_book.order_book_date,order_book.order_id,inquiry.product_id,inquiry.inquiry_id,quatation.quatation_no,quatation.quatation_id,revise_quatation.revise_quatation_no,revise_quatation.revise_id,customer_master.name,customer_master.mobile,customer_master.mobile_2,customer_master.mobile_3,customer_master.email,customer_master.email_2,country_master.country_name,state_master.state_name,city_master.city_name,product_master.product_name,u2.username as quot_user,employee.name as order_by_user from order_book

			INNER JOIN inquiry ON inquiry.inquiry_id = order_book.inquiry_id

			LEFT JOIN revise_quatation ON revise_quatation.revise_id = order_book.rquot_id

			LEFT JOIN quatation ON quatation.quatation_id = order_book.quot_id

			INNER JOIN customer_master ON customer_master.customer_id = inquiry.customer_id

			INNER JOIN country_master ON country_master.country_id = customer_master.country_id

			INNER JOIN product_master ON product_master.product_id = inquiry.product_id

			LEFT JOIN state_master ON state_master.state_id = customer_master.state_id

			LEFT JOIN city_master ON city_master.city_id = customer_master.city_id

			LEFT JOIN employee ON employee.emp_id = order_book.order_by

			LEFT JOIN users as u2 ON u2.id = quatation.added_by

			where ( order_book.order_book_date >= '".$from_date."' AND order_book.order_book_date <= '".$to_date."' AND `order_book`.cancel_by = 0)  AND ( inquiry.inquiry_no LIKE '".$search_keyword."' OR quatation.quatation_no LIKE '".$search_keyword."' OR u2.username LIKE '".$search_keyword."' OR customer_master.name LIKE '".$search_keyword."' OR customer_master.mobile LIKE '".$search_keyword."' OR customer_master.mobile_2 LIKE '".$search_keyword."' OR customer_master.mobile_3 LIKE '".$search_keyword."' ) Order By order_book.order_book_date desc ");

		}

		else

		{

			$data['result'] = Data_model::db_query("select order_book.order_book_date,order_book.order_id,inquiry.product_id,inquiry.inquiry_id,quatation.quatation_no,quatation.quatation_id,revise_quatation.revise_quatation_no,revise_quatation.revise_id,customer_master.name,customer_master.mobile,customer_master.mobile_2,customer_master.mobile_3,customer_master.email,customer_master.email_2,country_master.country_name,state_master.state_name,city_master.city_name,product_master.product_name,u2.username as quot_user,employee.name as order_by_user from order_book

			INNER JOIN inquiry ON inquiry.inquiry_id = order_book.inquiry_id

			LEFT JOIN revise_quatation ON revise_quatation.revise_id = order_book.rquot_id

			LEFT JOIN quatation ON quatation.quatation_id = order_book.quot_id

			INNER JOIN customer_master ON customer_master.customer_id = inquiry.customer_id

			INNER JOIN country_master ON country_master.country_id = customer_master.country_id

			INNER JOIN product_master ON product_master.product_id = inquiry.product_id

			LEFT JOIN state_master ON state_master.state_id = customer_master.state_id

			LEFT JOIN city_master ON city_master.city_id = customer_master.city_id

			LEFT JOIN employee ON employee.emp_id = order_book.order_by

			LEFT JOIN users as u2 ON u2.id = quatation.added_by

			where `order_book`.cancel_by = 0 AND inquiry.inquiry_no LIKE '".$search_keyword."' OR quatation.quatation_no LIKE '".$search_keyword."' OR u2.username LIKE '".$search_keyword."' OR customer_master.name LIKE '".$search_keyword."' OR customer_master.mobile LIKE '".$search_keyword."' OR customer_master.mobile_2 LIKE '".$search_keyword."' OR customer_master.mobile_3 LIKE '".$search_keyword."' Order By order_book.order_book_date desc ");

		}



		return view($this->view.'/search_data',$data);

	}

	public function print_pdf($id,$type)

	{

		$data['utility'] = $this->utility;

		$order_id = $this->utility->decode($id);

		$type = $this->utility->decode($type);



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



		$data['letter_head'] = Data_model::retrive('letterhead_master','*',array(),'letterhead_name','ASC');



		if($type == 'print')

		{
			
			$pdf = PDF::loadView($this->view.'/print',$data)->setPaper('a4','portrait');

			return $pdf->stream('Job_card_'.date('d-m-Y H:i:s'), 'compress');

		}

		else

		{

			$pdf = PDF::loadView($this->view.'/print',$data)->setPaper('a4','portrait');

			return $pdf->download('Job_card_'.date('d-m-Y H:i:s').'.pdf');

		}



	}

	public function view_job_card($id)

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

