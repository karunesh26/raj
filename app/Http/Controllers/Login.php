<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
 //use Illuminate\Support\Facades\Mail;
use Session;
use DB;
use Redirect;
use Validator;
use Mail;
class Login extends Controller
{
	public function index()
	{
		if (Session::has('raj_user_id'))
		{
				return redirect()->to('Dashboard');
		}
		else
		{
			return View('login');
		}
	}
		public function doLogin(Request $request)
		{
				$username = $request->input('username');
				$password = $request->input('password');
				$validator = Validator::make($request->all(), array(
                'username' => 'required',
				'password' => 'required',
				 ));
				 if ($validator->fails())
    			 {
					$msg = array('error' => 'Please Fill all Details');
					return redirect('login')->with($msg );
				 }
				 else
				 {
					$password = md5($password );
					$check = DB::table("users")
					->where("username", "=", $username) // "=" is optional
					->where("password", "=",  $password) // "=" is optional
					->get();
					if(count($check) == 1)
					{
						if($check[0]->role_id != 0)
						{
							$user_id=$check[0]->id;
							$role_id=$check[0]->role_id;
							$emp_id = $check[0]->emp_id;
							$username = $check[0]->username;
							if($role_id == 1)
							{
								Session::set('raj_user_id', $user_id);
								Session::set('raj_role_id', $role_id);
								Session::set('raj_user', $username);
								$get_zone = DB::table("employee")->where("emp_id", "=", $emp_id)->get();
								if(!empty($get_zone))
								{
									Session::set('raj_zone_id', $get_zone[0]->zone_id);
								}
								else
								{
									Session::set('raj_zone_id', '0');
								}
							}
							else
							{
								$check_active = DB::table("employee")->where("emp_id", "=", $emp_id)->get();
								if($check_active[0]->delete_status == 0)
								{
									Session::set('raj_user_id', $user_id);
									Session::set('raj_role_id', $role_id);
									Session::set('raj_user', $username);
									Session::set('raj_zone_id', $check_active[0]->zone_id);

									/* 22-05-2018 , Sneha Doshi , Get Zone Of Transfer Emp */
									/* $get_transfer_zone = DB::table("emp_transfer")
									->select('emp_id')
									->where("transfer_emp_id", "=", $emp_id)
									->where('transfer_from_date','<=',date("Y-m-d"))
									->where('transfer_to_date','>=',date("Y-m-d"))
									->pluck('emp_id');
									$get_transfer_zone[] = $emp_id;
									$get_zone = DB::table("employee")->whereIN("emp_id",$get_transfer_zone)->pluck('zone_id');
									$zones = implode(",",$get_zone);
									$zones_Arr = array_unique(explode(",",$zones));
									$zones = implode(",",$zones_Arr);
									if(!empty($get_zone))
									{
										Session::set('raj_zone_id', $zones);
									}
									else
									{
										Session::set('raj_zone_id', '0');
									} */
								}
								else
								{
									$msg = array('error' => 'You Are Not Active To Login');
									return redirect('login')->with($msg);
								}
							}
							return redirect()->to('Dashboard');
						}
						else
						{
							$msg = array('error' => 'You Are Not Authorized To Access');
							return redirect('login')->with($msg);
						}
					}
					else
					{
						$msg = array('error' => 'Invalid Email Or Password!');
						return redirect('login')->with($msg);
					}
				}
		}
	public function forgot_password()
	{
		return View('forgot_password');
	}
	public function update_password(Request $request)
	{
		$username = $request->input('username');
		$check = DB::table("users")
				->where("username", "=", $username)
				->get();
		$email = $check[0]->email;
		$str = '0123456789';
		$shuffled = str_shuffle($str);
		$data['shuffled'] = substr($shuffled, -6);
		$new_pwd = md5($data['shuffled']);
		DB::table('users')
			->where("username", $username)
            ->where('email', $email )
            ->update(array('password'=>$new_pwd));
		if($email!='')
		{
			Mail::send('forgot_password_mail',$data, function($message) use ($email)
			{
			 	$message->to($email,$username)->subject('Your New Password');
				 $message->from('api@angelspearlinfotech.com','Raj Water');
			});
			/*Mail::send('forgot_password_mail', $shuffled, function($message)
			{
				 $message->to($email,'sneha')->subject('Your New Password');
				 $message->from('api@angelspearlinfotech.com','Raj Water');
			});*/
			$msg = array('success' => 'Your New Password Is Sent To Your Mail');
			return redirect('login')->with($msg);
		}
	}
}
