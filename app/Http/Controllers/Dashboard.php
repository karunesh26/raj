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

class Dashboard extends Controller {

    public $controller = "Dashboard";
    public $utility;
    public $role_id;
    public $user_id;
    public $zone_id;
    private $basicInfo;

    public function __construct() {

        if (!Session::has('raj_user_id')) {
            $msg = array('error' => 'You Must First Login To Access');
            Redirect::to('/admin')->send()->with($msg);
        }
        $this->basicInfo['role_id'] = Session::get('raj_role_id');
        $this->basicInfo['user_id'] = Session::get('raj_user_id');
        $this->basicInfo['zone_id'] = Session::get('raj_zone_id');


        date_default_timezone_set("Asia/Kolkata");
        $this->basicInfo['utility'] = new Utility();
        $this->basicInfo['day_array'] = array(0 => 'Sunday', 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday');
    }

    public function index() {
        if ($this->basicInfo['role_id'] == 1) {
            $this->basicInfo['emp_work_detail'] = DB::table('users')->select('users.id', 'employee.name')
                    ->leftJoin('employee', 'employee.emp_id', '=', 'users.emp_id')
                    ->where('employee.delete_status', 0)
                    ->where('employee.zone_id', '!=', '')
                    ->whereIn('users.role_id', array(2, 3, 5))
                    ->get();
            $this->basicInfo['sales_emp_detail'] = DB::table('users')->select('users.id', 'employee.name')
                    ->leftJoin('employee', 'employee.emp_id', '=', 'users.emp_id')
                    ->where('employee.delete_status', 0)
                    ->where('employee.zone_id', '!=', '')
                    ->whereIn('users.role_id', array(4, 6, 11))
                    ->orderBy('employee.name', 'ASC')
                    ->get();
        } else {
            return redirect()->action('Quatation@index');
            /* get emp_id in users */
            $emp_id_data = Data_model::retrive('users', '*', array('id' => $this->basicInfo['user_id']), 'emp_id', 'DESC');
            $date = date('Y-m-d');
            $previous_date = date('Y-m-d', strtotime($date . " - 1 day"));
            if (!empty($emp_id_data)) {
                $employee_id = $emp_id_data[0]->emp_id;
            } else {
                $employee_id = 0;
            }
            $this->basicInfo['emp_detail'] = Data_model::retrive('employee', '*', array('emp_id' => $employee_id), 'emp_id', 'DESC');

            if ($this->basicInfo['role_id'] == '2' || $this->basicInfo['role_id'] == '3' || $this->basicInfo['role_id'] == '5') {
                /* work Detail */

                /* previous pending inquiry */
                $previous_inq = Data_model::db_query("select count(*) as previous_pending_inq from `inquiry` where `first_quatation_id` = 0 AND `project_zone` IN (" . $this->basicInfo['zone_id'] . ") AND `delete_status` = 0 AND `remove_status`= 0 AND DATE(added_time) <= '" . $previous_date . "' ");
                $this->basicInfo['previous_pending_inq'] = $previous_inq[0]->previous_pending_inq;

                /* Inquiry Entry */
                $total_inq_per_day = Data_model::db_query("select count(*) as inquiry_total_per_day from `inquiry` where `added_by` = '" . $this->basicInfo['user_id'] . "' AND DATE(added_time) = '" . $date . "' ");
                $this->basicInfo['inquiry_total_per_day'] = $total_inq_per_day[0]->inquiry_total_per_day;

                /* Inquiry Allot */
                $alloted_inquiry = Data_model::db_query("select count(*) as inquiry_allot from `inquiry` where  DATE(added_time) = '" . $date . "' AND `project_zone` IN (" . $this->basicInfo['zone_id'] . ") ");
                $this->basicInfo['inquiry_allot'] = $alloted_inquiry[0]->inquiry_allot;

                /* inquiry call */
                $call_inquiry = Data_model::db_query("select count(*) as call_inquiry from `inquiry_remark` where  date = '" . $date . "' AND `added_by`='" . $this->basicInfo['user_id'] . "' ");
                $this->basicInfo['call_inquiry'] = $call_inquiry[0]->call_inquiry;

                /* Quotation Generate */
                $generated_quotation = Data_model::db_query("select count(*) as generate_quot from `quatation` where  DATE(added_time) = '" . $date . "' AND `added_by` = '" . $this->basicInfo['user_id'] . "' ");
                $this->basicInfo['generate_quot'] = $generated_quotation[0]->generate_quot;

                /* Total Pending Inquiry */
                $total_pending_inquiry = Data_model::db_query("select count(*) as total_pending_inq from `inquiry` where `first_quatation_id` = 0 AND `project_zone` IN (" . $this->basicInfo['zone_id'] . ") AND `delete_status` = 0 AND `remove_status`= 0 AND DATE(added_time) <= '" . $date . "' ");
                $this->basicInfo['total_pending_inq'] = $total_pending_inquiry[0]->total_pending_inq;

                /* Total Revise Quotation */
                $total_revise_quotation = Data_model::db_query("select count(*) as total_revise_quotation from `revise_quatation` where DATE(added_time) = '" . $date . "' AND `added_by`= '" . $this->basicInfo['user_id'] . "' ");
                $this->basicInfo['total_revise_quotation'] = $total_revise_quotation[0]->total_revise_quotation;

                /* cancel inquiry */
                $cancel_inq = Data_model::db_query("select count(*) as cancel_inq from `inquiry` where `delete_status` = '" . $this->basicInfo['user_id'] . "' AND cancel_date ='" . $date . "' ");
                $this->basicInfo['cancel_inq'] = $cancel_inq[0]->cancel_inq;
            }
            if ($this->basicInfo['role_id'] == '4' || $this->basicInfo['role_id'] == '6' || $this->basicInfo['role_id'] == '11') {
                /* Previous Pending Follow-up */
                $previous_pending_followup = Data_model::db_query("select count(*) as previous_pending_followup ,`inquiry`.`inquiry_id`,  `quatation`.`quatation_id`, `quatation`.`quatation_no`, `follow_up`.`follow_up_status` from `inquiry`  inner join `follow_up` on `follow_up`.`inquiry_id` = `inquiry`.`inquiry_id` inner join `quatation` on `quatation`.`inquiry_id` = `inquiry`.`inquiry_id` where `quatation`.zone_id IN (" . $this->basicInfo['zone_id'] . ") AND `follow_up`.next_followup_date <= '" . $previous_date . "' and `inquiry`.`order_status` = 0 and `inquiry`.`regret_status` = 0 AND `follow_up`.follow_up_status = 0 order by `follow_up`.`follow_up_id` desc ");

                $this->basicInfo['previous_pending_followup'] = $previous_pending_followup[0]->previous_pending_followup;

                $total_follow_up = Data_model::db_query("select count(*) as total_follow_up ,`inquiry`.`inquiry_id`,  `quatation`.`quatation_id`, `quatation`.`quatation_no`, `follow_up`.`follow_up_status` from `inquiry`  inner join `follow_up` on `follow_up`.`inquiry_id` = `inquiry`.`inquiry_id` inner join `quatation` on `quatation`.`inquiry_id` = `inquiry`.`inquiry_id` where `quatation`.zone_id IN (" . $this->basicInfo['zone_id'] . ") AND `follow_up`.next_followup_date = '" . $date . "' and `inquiry`.`order_status` = 0 and `inquiry`.`regret_status` = 0 order by `follow_up`.`follow_up_id` desc");

                $this->basicInfo['total_follow_up'] = $total_follow_up[0]->total_follow_up;

                $pending_follow_up = Data_model::db_query("select count(*) as pending_follow_up ,`inquiry`.`inquiry_id`,  `quatation`.`quatation_id`, `quatation`.`quatation_no`, `follow_up`.`follow_up_status` from `inquiry` inner join `follow_up` on `follow_up`.`inquiry_id` = `inquiry`.`inquiry_id` inner join `quatation` on `quatation`.`inquiry_id` = `inquiry`.`inquiry_id` where `quatation`.zone_id IN (" . $this->basicInfo['zone_id'] . ") AND `follow_up`.next_followup_date = '" . $date . "'  and `follow_up`.follow_up_status = 0 and `inquiry`.`order_status` = 0 and `inquiry`.`regret_status` = 0 order by `follow_up`.`follow_up_id` desc");

                $this->basicInfo['pending_follow_up'] = $pending_follow_up[0]->pending_follow_up;

                $allotment_total = Data_model::db_query("select count(*) as allotment_total,`inquiry`.*, `quatation`.`quatation_id`, `quatation`.`quatation_no`, `quatation`.`follow_up_status` from `inquiry` inner join `quatation` on `quatation`.`inquiry_id` = `inquiry`.`inquiry_id` where `quatation`.zone_id IN (" . $this->basicInfo['zone_id'] . ") AND `quatation`.quatation_date = '" . $previous_date . "'  and `inquiry`.`order_status` = 0 and `inquiry`.`regret_status` = 0");

                $this->basicInfo['allotment_total'] = $allotment_total[0]->allotment_total;
            }

            //$where1 = "(find_in_set(`inquiry`.project_zone,'$this->basicInfo['zone_id']') OR `revise_quotation_notify`.emp_id = " . $employee_id . ") AND `revise_quotation_notify`.clear = 0 ";

            $this->basicInfo['revise_notify'] = DB::table('revise_quotation_notify')
                    ->join('inquiry', 'inquiry.inquiry_id', '=', 'revise_quotation_notify.inquiry_id')
                    ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                    ->join('product_master', 'product_master.product_id', '=', 'inquiry.product_id')
                    ->leftJoin('follow_up', 'follow_up.inquiry_id', '=', 'inquiry.inquiry_id')
                    ->join('users as u1', 'u1.id', '=', 'revise_quotation_notify.added_by')
                    ->join('employee', 'employee.emp_id', '=', 'revise_quotation_notify.emp_id')
                    ->select('revise_quotation_notify.*', 'product_master.product_name', 'customer_master.name', 'customer_master.prefix', 'u1.username as follow_up_by', 'employee.name as allot_to', 'follow_up.added_by')
                    //->whereRaw($where1)
                    ->orderBy('employee.name', 'ASC'
                    )->orderBy('id', 'DESC')
                    ->groupBy('follow_up.inquiry_id')
                    ->get();

            $where2 = "(find_in_set(`inquiry`.project_zone,'$this->basicInfo['zone_id']') OR `prise_issue_notify`.emp_id = " . $employee_id . ") AND `prise_issue_notify`.clear = 0";

            $this->basicInfo['prise_issue_notify'] = DB::table('prise_issue_notify')
                    ->join('inquiry', 'inquiry.inquiry_id', '=', 'prise_issue_notify.inquiry_id')
                    ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                    ->join('product_master', 'product_master.product_id', '=', 'inquiry.product_id')
                    ->leftJoin('follow_up', 'follow_up.inquiry_id', '=', 'inquiry.inquiry_id')
                    ->join('users as u1', 'u1.id', '=', 'prise_issue_notify.added_by')
                    ->join('employee', 'employee.emp_id', '=', 'prise_issue_notify.emp_id')
                    ->select('prise_issue_notify.*', 'product_master.product_name', 'customer_master.name', 'customer_master.prefix', 'u1.username as follow_up_by', 'employee.username as allot_to')
                    ->whereRaw($where2)
                    ->orderBy('employee.name', 'ASC')
                    ->orderBy('id', 'DESC')
                    ->groupBy('follow_up.inquiry_id')
                    ->get();
        }

        $this->basicInfo['utility'] = $this->utility;
        $this->basicInfo['controller_name'] = $this->controller;
        $this->basicInfo['role_id'] = $this->basicInfo['role_id'];

        return view('dashboard', $this->basicInfo);
    }

    public function dashboard_employee($type, $id) {
        $view_name = 'Marketing_Employee_Work_Detail';
        $this->basicInfo['user_id'] = base64_decode($id);
        $this->basicInfo['emp_work_detail'] = DB::table('users')->select('users.id', DB::raw('(SELECT `zone_id` FROM `employee` WHERE `emp_id` = `users`.`emp_id`) AS zone_id'), DB::raw('(SELECT `name` FROM `employee` WHERE `emp_id` = `users`.`emp_id`) AS name'))
                ->where('id', base64_decode($id))
                ->where('role_id', '!=', 1)//excluding Admin record
                ->first();
        $date = date('Y-m-d');
        $previous_date = date('Y-m-d', strtotime($date . " - 1 day"));
        if ($type == 'sales') {
            $view_name = 'Sales_Employee_Work_Detail';
        }
        return view('dashboard.' . $view_name, $this->basicInfo);
    }

    public function marketing_employee_details(Request $request) {
        $data = $request->all();
        $date = date('Y-m-d');
        $previous_date = date('Y-m-d', strtotime($date . " - 1 day"));
        if ($data['type'] == 'previous_pending_inq') {
            $previous_pending_inq = DB::table("inquiry")
                    ->where('first_quatation_id', 0)
                    ->where('delete_status', 0)->where('remove_status', 0)
                    ->where('added_time', '<=', $previous_date)
                    //->where('added_by', $data['user_id'])
                    ->whereIn('project_zone', explode(',',$data['zone_id']))
                    ->count();
            return response(['success' => $previous_pending_inq]);
        }
        if ($data['type'] == 'inquiry_total_per_day') {
            $inquiry_total_per_day = DB::table("inquiry")->where('added_by', $data['user_id'])->where('added_time', 'LIKE', $date . '%')->count();
            return response(['success' => $inquiry_total_per_day]);
        }
        if ($data['type'] == 'inquiry_allot') {
            $inquiry_allot = DB::table("inquiry")->whereIn('project_zone', explode(',',$data['zone_id']))->where('added_time', 'LIKE', $date . '%')->count();
            return response(['success' => $inquiry_allot]);
        }
        if ($data['type'] == 'call_inquiry') {
            $call_inquiry = DB::table("inquiry_remark")->where('date', $date)->where('added_by', $data['user_id'])->count();
            return response(['success' => $call_inquiry]);
        }
        if ($data['type'] == 'generate_quot') {
            $generate_quot = DB::table("quatation")->where('added_time', 'LIKE', $date . '%')->where('added_by', $data['user_id'])->count();
            return response(['success' => $generate_quot]);
        }
        if ($data['type'] == 'total_revise_quotation') {
            $total_revise_quotation = DB::table("revise_quatation")->where('added_time', 'LIKE', $date . '%')->where('added_by', $data['user_id'])->count();
            return response(['success' => $total_revise_quotation]);
        }
        if ($data['type'] == 'cancel_inq') {
            $cancel_inq = DB::table("inquiry")->where('added_time', 'LIKE', $date . '%')->where('delete_status', $data['user_id'])->count();
            return response(['success' => $cancel_inq]);
        }
        if ($data['type'] == 'total_pending_inq') {
            $total_pending_inq = DB::table("inquiry")->where('added_time', '<=', $date . '%')->where('first_quatation_id', 0)->where('delete_status', 0)->where('remove_status', 0)->whereIn('project_zone', explode(',',$data['zone_id']))->count();
            return response(['success' => $total_pending_inq]);
        }
        return response(['success' => 0]);
    }

    public function Sales_Employee_Work_Detail(Request $request) {
        $data = $request->all();
        $date = date('Y-m-d');
        $previous_date = date('Y-m-d', strtotime($date . " - 1 day"));
        if ($data['type'] == 'previous_pending_followup') {
            $previous_pending_followup = DB::table("inquiry")
                    ->leftJoin('follow_up', 'follow_up.inquiry_id', '=', 'inquiry.inquiry_id')
                    ->leftJoin('quatation', 'quatation.inquiry_id', '=', 'inquiry.inquiry_id')
                    ->whereIn('quatation.zone_id', explode(',',$data['zone_id']))
                    ->where('follow_up.next_followup_date', '<=', $previous_date)
                    ->where('inquiry.order_status', 0)
                    ->where('inquiry.regret_status', 0)
                    ->where('inquiry.hot_list', 0)
                    ->where('follow_up.follow_up_status', 0)
                    ->count();
            return response(['success' => $previous_pending_followup]);
        }
        if ($data['type'] == 'allotment_total') {
            $allotment_total = DB::table("inquiry")
                    ->leftJoin('quatation', 'quatation.inquiry_id', '=', 'inquiry.inquiry_id')
                    ->whereIn('quatation.zone_id', explode(',',$data['zone_id']))
                    ->where('quatation.quatation_date', $previous_date)
                    ->where('inquiry.order_status', 0)
                    ->where('inquiry.regret_status', 0)
                    ->count();
            return response(['success' => $allotment_total]);
        }
        if ($data['type'] == 'total_follow_up') {
            $total_follow_up = DB::table("inquiry")
                    ->leftJoin('follow_up', 'follow_up.inquiry_id', '=', 'inquiry.inquiry_id')
                    ->leftJoin('quatation', 'quatation.inquiry_id', '=', 'inquiry.inquiry_id')
                    ->whereIn('quatation.zone_id', explode(',',$data['zone_id']))
                    ->where('follow_up.next_followup_date', $date)
                    ->where('inquiry.order_status', 0)
                    ->where('inquiry.hot_list', 0)
                    ->where('inquiry.regret_status', 0)
                    ->count();
            return response(['success' => $total_follow_up]);
        }
        if ($data['type'] == 'pending_follow_up') {
            $pending_follow_up = DB::table("inquiry")
                    ->leftJoin('follow_up', 'follow_up.inquiry_id', '=', 'inquiry.inquiry_id')
                    ->leftJoin('quatation', 'quatation.inquiry_id', '=', 'inquiry.inquiry_id')
                    ->whereIn('quatation.zone_id', explode(',',$data['zone_id']))
                    ->where('follow_up.next_followup_date', $date)
                    ->where('follow_up.follow_up_status', 0)
                    ->where('inquiry.order_status', 0)
                    ->where('inquiry.hot_list', 0)
                    ->where('inquiry.regret_status', 0)
                    ->count();
            return response(['success' => $pending_follow_up]);
        }
        return response(['success' => 0]);
    }

    public function prise_issue_notify(Request $request) {
        $data = $request->all();
        $prise_issue_notify = DB::table('prise_issue_notify')
                ->join('inquiry', 'inquiry.inquiry_id', '=', 'prise_issue_notify.inquiry_id')
                ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                ->join('product_master', 'product_master.product_id', '=', 'inquiry.product_id')
                ->leftJoin('follow_up', 'follow_up.inquiry_id', '=', 'inquiry.inquiry_id')
                ->join('users as u1', 'u1.id', '=', 'prise_issue_notify.added_by')
                ->join('employee', 'employee.emp_id', '=', 'prise_issue_notify.emp_id')
                ->select('prise_issue_notify.*', 'product_master.product_name', 'customer_master.name', 'customer_master.prefix', 'u1.username as follow_up_by', 'employee.name as allot_to');
        if ($this->basicInfo['role_id'] != 1) {
            $where2 = "(find_in_set(`inquiry`.project_zone,'$this->basicInfo['zone_id']') OR `prise_issue_notify`.emp_id = " . $employee_id . ") AND `prise_issue_notify`.clear = 0";
            $prise_issue_notify = $prise_issue_notify->whereRaw($where2);
        }
        $prise_issue_notify = $prise_issue_notify->where('prise_issue_notify.clear', 0)
                ->orderBy('id', 'DESC')
                ->groupBy('follow_up.inquiry_id')
                ->get();
        $return_val = '';
        foreach ($prise_issue_notify as $key => $value) {
            $href = 'Follow_up/' . $this->utility->encode($value->inquiry_id);
            $href1 = 'Dashboard/clear/' . $this->utility->encode($value->id) . '/' . $this->utility->encode('prise');
            $return_val .= '
                    <tr>
                        <td>' . ($key + 1) . '</td>
                        <td>' . date('d-m-Y', strtotime($value->added_date)) . '</td>
                        <td>' . $value->quotation_no . '</td>
                        <td>' . $value->prefix . ' ' . $value->name . '</td>
                        <td>' . $value->product_name . '</td>
                        <td>' . $value->remark . '</td>
                        <td>' . $value->follow_up_by . '</td>
                        <td>' . $value->allot_to . '</td>
                        <td>
                            <a href="' . $href . '" target="_blank" class="btn bg-maroon btn-sm" ><i class="glyphicon glyphicon-eye-open icon-white"></i> View</a>
                            <a href="' . $href1 . '"  class="btn bg-purple btn-sm" ><i class="fa fa-window-close" aria-hidden="true"></i> Clear</a>
                        </td>
                    </tr>';
        }
        if ($return_val == '') {
            $return_val = '<td colspan="9" style="text-align: center;">Record not fond..!</td>';
        }
        return response(['success' => $return_val]);
    }

    public function revise_notify_data(Request $request) {
        $data = $request->all();
        $revise_notify_data = DB::table('prise_issue_notify')
                ->join('inquiry', 'inquiry.inquiry_id', '=', 'prise_issue_notify.inquiry_id')
                ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                ->join('product_master', 'product_master.product_id', '=', 'inquiry.product_id')
                ->leftJoin('follow_up', 'follow_up.inquiry_id', '=', 'inquiry.inquiry_id')
                ->join('users as u1', 'u1.id', '=', 'prise_issue_notify.added_by')
                ->join('employee', 'employee.emp_id', '=', 'prise_issue_notify.emp_id')
                ->select('prise_issue_notify.*', 'product_master.product_name', 'customer_master.name', 'customer_master.prefix', 'u1.username as follow_up_by', 'employee.username as allot_to');
        if ($this->basicInfo['role_id'] != 1) {
            $where2 = "(find_in_set(`inquiry`.project_zone,'$this->basicInfo['zone_id']') OR `prise_issue_notify`.emp_id = " . $employee_id . ") AND `prise_issue_notify`.clear = 0";
            $revise_notify_data = $revise_notify_data->whereRaw($where1);
        }
        $revise_notify_data = $revise_notify_data->orderBy('employee.name', 'ASC')
                ->orderBy('id', 'DESC')
                ->groupBy('follow_up.inquiry_id')
                ->get();
        $return_val = '';
        foreach ($revise_notify_data as $key => $value) {
            $href = 'Follow_up/' . $this->utility->encode($value->inquiry_id);
            $href1 = 'Dashboard/clear/' . $this->utility->encode($value->id) . '/' . $this->utility->encode('revise');
            $href2 = 'Quatation / revise_quatation_desktop / ' . $utility->encode($value->inquiry_id);
            $return_val .= '
                    <tr>
                        <td>' . ($key + 1) . '</td>
                        <td>' . date('d-m-Y', strtotime($value->added_date)) . '</td>
                        <td>' . $value->quotation_no . '</td>
                        <td>' . $value->prefix . ' ' . $value->name . '</td>
                        <td>' . $value->product_name . '</td>
                        <td>' . $value->remark . '</td>
                        <td>' . $value->follow_up_by . '</td>
                        <td>' . $value->allot_to . '</td>
                        <td>
                            <a href="' . $href . '" target="_blank" class="btn bg-maroon btn-sm" ><i class="glyphicon glyphicon-eye-open icon-white"></i> View</a>
                            <a href="' . $href1 . '" class="btn bg-purple btn-sm" ><i class="fa fa-window-close" aria-hidden="true"></i> Clear</a>
                            <a href="' . $href2 . '" target="_blank" class="btn bg-green btn-sm" ><i class="fa circle-notch" aria-hidden="true"></i> Revise</a>
                        </td>
                    </tr>';
        }
        if ($return_val == '') {
            $return_val = '<td colspan="9" style="text-align: center;">Record not fond..!</td>';
        }
        return response(['success' => $return_val]);
    }

    public function read_clear($id, $type) {
        $id = $this->utility->decode($id);
        $type = $this->utility->decode($type);

        if ($type == 'prise') {
            $data = array('clear' => 1);
            $where = array('id' => $id);
            Data_model::restore('prise_issue_notify', $data, $where);
        } else {
            $data = array('clear' => 1);
            $where = array('id' => $id);
            Data_model::restore('revise_quotation_notify', $data, $where);
        }

        $msg = array('success' => 'Reject Successfully');

        return redirect($this->controller)->with($msg);
    }

}
