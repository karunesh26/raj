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

class Search extends Controller
{
	public $table="payment_mode_master";
	public $primary_id="id";
	public $field = "search";
	public $msgName = "Search";
	public $view = "search";
	public $controller = "Search";
	public $module_name = "search";
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

		$data['country'] = Data_model::retrive('country_master','*',array('delete_status'=>0),'country_name','ASC');
		$data['state'] = Data_model::retrive('state_master','*',array('delete_status'=>0),'state_name','ASC');
		$data['city'] = Data_model::retrive('city_master','*',array('delete_status'=>0),'city_name','ASC');
		$data['client_category'] = Data_model::retrive('client_category_master','*',array('delete_status'=>0),'client_category_name','ASC');
		$data['inq_for'] = Data_model::retrive('product_master','*',array('delete_status'=>0),'product_name','ASC');
		$data['inq_source'] = Data_model::retrive('source_master','*',array('delete_status'=>0),'source_name','ASC');
		$data['inq_type'] = Data_model::retrive('category_master','*',array('delete_status'=>0),'category_name','ASC');
		$data['users'] = Data_model::db_query("select * from `users` where `id` != '1' Order By `username` ASC ");

		$data['role_id'] = $this->role_id;
		$data['utility'] = $this->utility;
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		return view($this->view.'/manage',$data);
	}

	public function get_search_data(Request $request)
	{
		$inq_no  = trim($request->input('inq_no'));
		$quot_no  = trim($request->input('quot_no'));
		$inq_for  = trim($request->input('inq_for'));
		$inq_source  = trim($request->input('inq_source'));
		$inq_person  = trim($request->input('inq_person'));
		$quot_person  = trim($request->input('quot_person'));
		$foll_by  = trim($request->input('foll_by'));
		$client_name  = trim($request->input('client_name'));
		$mobile_no  = trim($request->input('mobile_no'));
		$email_id  = trim($request->input('email_id'));
		$client_category  = trim($request->input('client_category'));
		$country  = trim($request->input('country'));
		$state  = trim($request->input('state'));
		$city  = trim($request->input('city'));
		$inq_type  = trim($request->input('inq_type'));

		$search_keyword = '%'.$quot_no.'%';

		$from_date  = date('Y-m-d',strtotime($request->input('from_date')));
		$to_date  = date('y-m-d',strtotime($request->input('to_date')));
		$data['utility'] = $this->utility;

		$from_d = $request->input('from_date');
		$to_d = $request->input('to_date');

		$res = "select inquiry.inquiry_no,quatation.quatation_no,quatation.inquiry_id,quatation.quatation_date,customer_master.*,source_master.source_name,category_master.category_name,country_master.country_name,state_master.state_name,city_master.city_name,product_master.product_name,client_category_master.client_category_name,follow_up.added_by,u1.username as inq_user,u2.username as quot_user,u3.username as foll_user from inquiry
		INNER JOIN quatation ON quatation.inquiry_id = inquiry.inquiry_id
		INNER JOIN customer_master ON customer_master.customer_id = inquiry.customer_id
		INNER JOIN source_master ON source_master.source_id = inquiry.source_id
		INNER JOIN category_master ON category_master.category_id = inquiry.category_id
		INNER JOIN country_master ON country_master.country_id = customer_master.country_id
		INNER JOIN product_master ON product_master.product_id = inquiry.product_id
		LEFT JOIN state_master ON state_master.state_id = customer_master.state_id
		LEFT JOIN city_master ON city_master.city_id = customer_master.city_id
		LEFT JOIN client_category_master ON client_category_master.client_category_id = inquiry.client_category_id
		LEFT JOIN follow_up ON follow_up.inquiry_id = inquiry.inquiry_id
		LEFT JOIN users as u1 ON u1.id = inquiry.added_by
		LEFT JOIN users as u2 ON u2.id = quatation.added_by
		LEFT JOIN users as u3 ON u3.id = follow_up.added_by where ";

		if($from_d != '' && $to_d != ''){
			$res .= "( quatation.quatation_date >= '".$from_date."' AND quatation.quatation_date <= '".$to_date."' ) AND ";
		}

		$exc = 0;
		if($inq_no != ''){
			$inq_no = '%'.$inq_no.'%';
			if($exc == 0){
				$res .= "inquiry.inquiry_no LIKE '".$inq_no."' ";
			}
			else{
				$res .= "OR inquiry.inquiry_no LIKE '".$inq_no."' ";
			}
			$exc = 1;
		}
		if($inq_type != ''){
			if($exc == 0){
				$res .= "inquiry.category_id = '".$inq_type."' ";
			}
			else{
				$res .= "OR inquiry.category_id = '".$inq_type."' ";
			}
			$exc = 1;
		}
		if($quot_no != ''){

			if($exc == 0){
				$res .= "quatation.q_no = '".$quot_no."' ";
			}
			else{
				$res .= "OR quatation.q_no = '".$quot_no."' ";
			}
			$exc = 1;
		}
		if($inq_for != ''){
			if($exc == 0){
				$res .= "product_master.product_id = '".$inq_for."' ";
			}else{
				$res .= "OR product_master.product_id = '".$inq_for."' ";
			}
			$exc = 1;
		}
		if($inq_source != ''){
			if($exc == 0){
				$res .= "source_master.source_id = '".$inq_source."' ";
			}else{
				$res .= "OR source_master.source_id = '".$inq_source."' ";
			}
			$exc = 1;
		}
		if($inq_person != ''){
			if($exc == 0){
				$res .= "u1.id = '".$inq_person."' ";
			}else{
				$res .= "OR u1.id = '".$inq_person."' ";
			}
			$exc = 1;
		}
		if($quot_person != ''){
			if($exc == 0){
				$res .= "u2.id = '".$quot_person."' ";
			}else{
				$res .= "OR u2.id = '".$quot_person."' ";
			}

			$exc = 1;
		}
		if($foll_by != ''){
			if($exc == 0){
				$res .= "u3.id = '".$foll_by."' ";
			}else{
				$res .= "OR u3.id = '".$foll_by."' ";
			}
			$exc = 1;
		}
		if($client_name != ''){
			$client_name = '%'.$client_name.'%';
			if($exc == 0){
				$res .= "customer_master.name LIKE '".$client_name."' ";
			}
			else{
				$res .= "OR customer_master.name LIKE '".$client_name."' ";
			}
			$exc = 1;
		}
		if($mobile_no != ''){
			$mobile_no = '%'.$mobile_no.'%';
			if($exc == 0){
				$res .= "customer_master.mobile LIKE '".$mobile_no."' ";
				$res .= "OR customer_master.mobile_2 LIKE '".$mobile_no."' ";
				$res .= "OR customer_master.mobile_3 LIKE '".$mobile_no."' ";
			}else{
				$res .= "OR customer_master.mobile LIKE '".$mobile_no."' ";
				$res .= "OR customer_master.mobile_2 LIKE '".$mobile_no."' ";
				$res .= "OR customer_master.mobile_3 LIKE '".$mobile_no."' ";
			}
			$exc = 1;
		}
		if($email_id != ''){
			$email_id = '%'.$email_id.'%';
			if($exc == 0){
				$res .= "customer_master.email LIKE '".$email_id."' ";
				$res .= "OR customer_master.email_2 LIKE '".$email_id."' ";
			}else{
				$res .= "OR customer_master.email LIKE '".$email_id."' ";
				$res .= "OR customer_master.email_2 LIKE '".$email_id."' ";
			}
			$exc = 1;
		}
		if($client_category != ''){
			if($exc == 0){
				$res .= "client_category_master.client_category_id = '".$client_category."' ";
			}else{
				$res .= "OR client_category_master.client_category_id = '".$client_category."' ";
			}
			$exc = 1;
		}
		if($country != ''){
			if($exc == 0){
				$res .= "country_master.country_id = '".$country."' ";
			}else{
				$res .= "OR country_master.country_id = '".$country."' ";
			}

			$exc = 1;
		}
		if($state != ''){
			if($exc == 0){
				$res .= "state_master.state_id = '".$state."' ";
			}else{
				$res .= "OR state_master.state_id = '".$state."' ";
			}
			$exc = 1;
		}
		if($city != ''){
			if($exc == 0){
				$res .= "city_master.city_id = '".$city."' ";
			}else{
				$res .= "OR city_master.city_id = '".$city."' ";
			}
			$exc = 1;
		}
		$res .="Group By follow_up.inquiry_id Order By quatation.quatation_id desc";

		$data['result'] = Data_model::db_query($res);


		return view($this->view.'/search_data',$data);
	}
}
