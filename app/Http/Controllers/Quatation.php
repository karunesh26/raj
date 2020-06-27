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

class Quatation extends Controller {

    public $table = "quatation";
    public $primary_id = "quatation_id";
    public $foreign_table = "inquiry";
    public $foreign_id = "inquiry_id";
    public $msgName = "Quotation";
    public $view = "quatation";
    public $controller = "Quatation";
    public $module_name = "quatation";
    public $utility;
    public $role_id;
    public $user_id;
    public $zone_id;
    private $basicInfo;

    public function __construct() {
        if (!Session::has('raj_user_id')) {
            $msg = array('error' => 'You Must First Login To Access');
            Redirect::to('/')->send()->with($msg);
        }
        $this->role_id = Session::get('raj_role_id');
        $this->user_id = Session::get('raj_user_id');
        $this->zone_id = Session::get('raj_zone_id');

        if ($this->role_id != '1') {
            $permission = Data_model::get_permission($this->module_name);
            if (empty($permission)) {
                Redirect::to('/')->send();
            }
        }
        date_default_timezone_set("Asia/Kolkata");
        $this->utility = new Utility();
    }

    public function index() {
        if ($this->role_id != '1') {
            $permission = Data_model::get_permission($this->module_name);
            $data['add_permission'] = $permission[0]->add;
            $data['edit_permission'] = $permission[0]->edit;
            $data['print_permission'] = $permission[0]->print;
            $data['delete_permission'] = $permission[0]->delete;
        }
        $data['role_id'] = $this->role_id;

        $data['utility'] = $this->utility;

        $data['minimum_days'] = Data_model::retrive('minimum_days', '*', array('type' => 'quatation'), 'id', 'DESC');

        $data['catalog'] = Data_model::retrive('catalog_master', '*', array('delete_status' => 0), 'id', 'DESC');

        $data['address_master'] = Data_model::retrive('address_master', '*', array(), 'office_name', 'ASC');
        $data['controller_name'] = $this->controller;
        $data['msgName'] = $this->msgName;
        return view($this->view . '/manage', $data);
    }

    public function get_customer_email(Request $request) {
        $inq_id = $request->input('inq_id');

        $data_email = Data_model::db_query("select `inquiry`.inquiry_id,`customer_master`.* from `inquiry` INNER JOIN customer_master ON customer_master.customer_id = inquiry.customer_id where `inquiry`.inquiry_id = " . $inq_id . "  ");

        if (!empty($data_email)) {
            echo '<option value="">Select</option>';
            if ($data_email[0]->email != '') {
                echo '<option value="' . $data_email[0]->email . '" >' . $data_email[0]->email . '</option>';
            }
            if ($data_email[0]->email_2 != '') {
                echo '<option value="' . $data_email[0]->email_2 . '" >' . $data_email[0]->email_2 . '</option>';
            }
        } else {
            echo '<option value="">Select</option>';
        }
    }

    public function get_customer_mobile(Request $request) {
        $inq_id = $request->input('inq_id');

        $data_customer = Data_model::db_query("select `inquiry`.inquiry_id,`customer_master`.* from `inquiry` INNER JOIN customer_master ON customer_master.customer_id = inquiry.customer_id where `inquiry`.inquiry_id = " . $inq_id . "  ");

        if (!empty($data_customer)) {
            if ($data_customer[0]->mobile != '') {
                echo '<option value="' . $data_customer[0]->mobile . '" >' . $data_customer[0]->mobile . '</option>';
            }
            if ($data_customer[0]->mobile_2 != '') {
                echo '<option value="' . $data_customer[0]->mobile_2 . '" >' . $data_customer[0]->mobile_2 . '</option>';
            }
            if ($data_customer[0]->mobile_3 != '') {
                echo '<option value="' . $data_customer[0]->mobile_3 . '" >' . $data_customer[0]->mobile_3 . '</option>';
            }
        } else {
            echo '<option value="">Select</option>';
        }
    }

    public function send_address(Request $request) {
        $follow_up_address_id = implode(",", $request->input('follow_up_address'));
        $customer_mno = $request->input('customer_mno');

        /* get address */
        $get_all_address = Data_model::db_query("select * from `address_master` where id IN (" . $follow_up_address_id . ") AND `delete_status`= 0 ");

        $address_send = '';
        foreach ($get_all_address as $key => $val) {
            $address_send .= $val->address;
            $address_send .= '.';
        }

        for ($r = 0; $r < count($customer_mno); $r++) {
            if ($customer_mno[$r] != '') {
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
                        'method' => 'POST',
                        'header' => 'Content-type: application/x-www-form-urlencoded',
                        'content' => $postdata
                    )
                );

                $context = stream_context_create($opts);
                $result = trim(file_get_contents('http://buzz.azmarq.com/http-api.php', false, $context));
            }
        }

        $response[0] = 'success';

        echo json_encode($response);
        exit;
    }

    public function remark_quotation(Request $request) {
        $inq_id = $request->input('inquiry_id');
        $remark_quotation = $request->input('remark_quotation');

        $get_remark = Data_model::retrive('inquiry', '*', array('inquiry_id' => $inq_id), 'inquiry_id');

        if ($inq_id != '' && $remark_quotation != '') {
            if ($get_remark[0]->inq_remark != '') {
                $new_remark = $get_remark[0]->inq_remark . "****" . $remark_quotation;
                $new_by = $get_remark[0]->remark_by . "****" . Session::get('raj_user_id');
                $new_date = $get_remark[0]->remark_date . "****" . date('Y-m-d H:i:s');
            } else {
                $new_remark = $remark_quotation;
                $new_by = Session::get('raj_user_id');
                $new_date = date('Y-m-d H:i:s');
            }
            $data = array('inq_remark' => $new_remark, "remark_by" => $new_by, "remark_date" => $new_date);
            $where = array('inquiry_id' => $inq_id);
            Data_model::restore('inquiry', $data, $where);
            $response[0] = 'success';

            $data_in = array('inquiry_id' => $inq_id, 'remark' => $remark_quotation, 'date' => date('Y-m-d'), 'added_by' => $this->user_id, 'added_date' => date('Y-m-d H:i:s'));
            Data_model::store('inquiry_remark', $data_in);
        } else {
            $response[0] = 'unsuccess';
        }
        echo json_encode($response);
        exit;
    }

    public function get_remark(Request $request) {
        $inq_id = $request->input('inq_id');
        $get_remark = Data_model::retrive('inquiry', '*', array('inquiry_id' => $inq_id), 'inquiry_id');

        if (!empty($get_remark)) {
            $data['remark'] = $get_remark;
        } else {
            $data['remark'] = array();
        }
        $data['emp_detail'] = DB::table('users')->pluck('username', 'id');
        return view($this->view . '/remark_data', $data);
        exit;
    }

    public function send_mail(Request $request) {
        $inquiry_id = $request->input('inquiry_id');

        /*  get send status for quotation  */
        $get_status = Data_model::retrive('quatation', '*', array('inquiry_id' => $inquiry_id), 'q_master_id', 'ASC');

        $send_status_id = $get_status[0]->send_status;
        $quatation_id = $get_status[0]->quatation_id;

        $send_email_id = $request->input('send_email_id');
        $title = $request->input('title');

        /*  get product detail  */

        $get_product = Data_model::db_query("select inquiry.inquiry_id,product_master.product_name from inquiry INNER JOIN product_master ON product_master.product_id = inquiry.product_id where inquiry.inquiry_id = " . $inquiry_id . " ");

        if (!empty($get_product)) {
            $product_name = $get_product[0]->product_name;
        } else {
            $product_name = '';
        }
        $company_detail = Data_model::retrive('company', '*', array(), 'name', 'ASC');
        $role_nm = Data_model::retrive('role_master', '*', array('role_id' => $this->role_id), 'role_id', 'ASC');
        $user_detail = Data_model::retrive('users', '*', array('id' => $this->user_id), 'id', 'ASC');

        $msg = '<span style="color:#001f3f;font-size:16px;font-weight:bold">Inquiry For</span> : <strong>' . $product_name . '</strong><br>';


        $get_zone_id = Data_model::retrive('quatation_master', '*', array('inquiry_id' => $inquiry_id), 'q_master_id', 'ASC');
        $zone_id = $get_zone_id[0]->zone_id;

        $get_zone_email = Data_model::retrive('quotation_email', '*', array('zone' => $zone_id), 'email', 'ASC');

        $zone_email = $get_zone_email[0]->email;


        if (!empty($title)) {
            if (!empty($send_email_id)) {
                $get_mail_format = Data_model::retrive('email_master', '*', array('email_title' => 'quotation'), 'id', 'DESC');

                if (!empty($get_mail_format)) {
                    $mail_format = $get_mail_format[0]->email_format;
                } else {
                    $mail_format = '';
                }

                $email = $send_email_id;
                $files = array();
                for ($i = 0; $i < count($title); $i++) {
                    $get_file_nm = Data_model::retrive('catalog_master', '*', array('id' => $title[$i]), 'id', 'DESC');
                    $files[] = $get_file_nm[0]->catalog_file;
                }
                $email_to = $email;
                $email_from = $zone_email;
                $email_subject = "CRM Raj Water";

                $fileatt_name = 'Raj Water Quotation';
                $fileatt = 'external/catalog/' . $files[0];

                $semi_rand = md5(time());
                $fileatt_type = "application/pdf";
                $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

                $headers = "From: " . $email_from;
                $headers .= "\nMIME-Version: 1.0\n" .
                        "Content-Type: multipart/mixed;\n" .
                        " boundary=\"{$mime_boundary}\"";



                $email_message = "Raj Water Quotation.<bR>";

                $email_message = $msg . "<bR>";
                $email_message .= $mail_format . "<bR>";

                $email_message .= '<br><br><strong>Thanks and Regards</strong> <br> ' . $company_detail[0]->name . '<br>' . $user_detail[0]->username . '<br>' . $role_nm[0]->role_name . '<br>' . $user_detail[0]->mobile;

                $email_message .= "<br><br>Attachment:<br>";

                $email_message .= "This is a multi-part message in MIME format.\n\n" .
                        "--{$mime_boundary}\n" .
                        "Content-Type:text/html; charset=\"iso-8859-1\"\n" .
                        "Content-Transfer-Encoding: 7bit\n\n" .
                        $email_message .= "\n\n";
                $email_message .= "--{$mime_boundary}\n";

                for ($x = 0; $x < count($files); $x++) {
                    $path = 'external/catalog/' . $files[$x];
                    $file = fopen($path, "rb");
                    $data = fread($file, filesize($path));
                    fclose($file);
                    $data = chunk_split(base64_encode($data));
                    $email_message .= "Content-Type: {$fileatt_type};\n" . " name=\"$fileatt_name\"\n" .
                            "Content-Disposition: attachment;\n" . " filename=\"$fileatt_name\"\n" .
                            "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
                    $email_message .= "--{$mime_boundary}\n";
                }

                $data1['utility'] = $this->utility;
                $data1['with_latterhead'] = 'yes';

                $data1['result'] = Data_model::db_query("SELECT `" . $this->table . "`.* ,`product_master`.`product_name`,`customer_master`.`mobile` ,`customer_master`.`mobile_2`,`customer_master`.`mobile_3`,`customer_master`.`prefix`,`customer_master`.`email`,`customer_master`.`email_2` ,`customer_master`.`name` as `customer_name` ,`customer_master`.`company`,`customer_master`.`address`,`customer_master`.`country_id`,`role_master`.`role_name` , `users`.`username` ,`employee`.`name` as `employee_name` ,`users`.`mobile` as `user_mobile`,country_master.country_name,state_master.state_name,city_master.city_name FROM " . $this->table . "
				LEFT JOIN `" . $this->foreign_table . "` ON `" . $this->table . "`.`" . $this->foreign_id . "` = `" . $this->foreign_table . "`.`" . $this->foreign_id . "`
				LEFT JOIN `product_master` ON `product_master`.`product_id` = `" . $this->foreign_table . "`.`product_id`
				LEFT JOIN `customer_master` ON `customer_master`.`customer_id` = `" . $this->foreign_table . "`.`customer_id`
				INNER JOIN country_master ON country_master.country_id = customer_master.country_id
				LEFT JOIN state_master ON state_master.state_id = customer_master.state_id
				LEFT JOIN city_master ON city_master.city_id = customer_master.city_id
				LEFT JOIN `users` ON `users`.`id` = `" . $this->table . "`.`added_by`
				LEFT JOIN `role_master` ON `role_master`.`role_id` = `users`.`role_id`
				LEFT JOIN `employee` ON `employee`.`emp_id` = `users`.`emp_id`
				where `" . $this->table . "`.`" . $this->primary_id . "` = '" . $quatation_id . "'");

                $get_cur = Data_model::retrive('country_master', '*', array('country_id' => $data1['result'][0]->country_id), 'country_name', 'DESC');

                if (!empty($get_cur)) {
                    $cur_type = $get_cur[0]->cur_type;
                } else {
                    $cur_type = 'inr';
                }
                $data1['cur_type'] = $cur_type;

                $data1['specification'] = Data_model::retrive('specification_master', '*', array(), 'sequence', 'ASC');
                $data1['quatation_product'] = Data_model::retrive('quatation_product', '*', array(), 'name', 'ASC');
                $data1['terms_condition'] = Data_model::retrive('terms_condition', '*', array(), 'term_id', 'ASC');
                $data1['company'] = Data_model::retrive('company', '*', array(), 'name', 'ASC');
                $data1['controller_name'] = $this->controller;

                $data1['letter_head'] = Data_model::retrive('letterhead_master', '*', array(), 'letterhead_name', 'ASC');

                $pdf = PDF::loadView($this->view . '/print', $data1);
                $pdf = $pdf->stream('Quotation ' . date('d-m-Y H:i:s'));

                $attachment = chunk_split(base64_encode($pdf));

                $email_message .= "Content-Type: {$fileatt_type};\n" . " name=\"$fileatt_name\"\n" .
                        "Content-Disposition: attachment;\n" . " filename=\"$fileatt_name\"\n" .
                        "Content-Transfer-Encoding: base64\n\n" . $attachment . "\n\n";
                $email_message .= "--{$mime_boundary}\n";

                $sent = mail($email_to, $email_subject, $email_message, $headers);

                if ($sent == 1) {

                    $mail_status = intval($send_status_id) + intval(1);
                    Data_model::restore('quatation', array('send_status' => $mail_status), array('inquiry_id' => $inquiry_id));
                    $response[0] = "success";
                } else {
                    $response[0] = "Mail Not Sent.";
                }
            } else {
                $response[0] = "Please Select Email";
            }
        } else {
            $response[0] = "Please Select Title";
        }

        echo json_encode($response);
        exit;
    }

    public function send_revise_mail(Request $request) {
        $inquiry_id = $request->input('inquiry_id');
        $revice_id = $request->input('revise_id');

        /*  get send status for quotation  */
        $get_status = Data_model::retrive('quatation', '*', array('inquiry_id' => $inquiry_id), 'q_master_id', 'ASC');

        $send_status_id = $get_status[0]->send_status;
        $quatation_id = $get_status[0]->quatation_id;

        $send_email_id = $request->input('send_email_id');
        $title = $request->input('title');

        /*  get product detail  */

        $get_product = Data_model::db_query("select inquiry.inquiry_id,product_master.product_name from inquiry INNER JOIN product_master ON product_master.product_id = inquiry.product_id where inquiry.inquiry_id = " . $inquiry_id . " ");

        if (!empty($get_product)) {
            $product_name = $get_product[0]->product_name;
        } else {
            $product_name = '';
        }
        $company_detail = Data_model::retrive('company', '*', array(), 'name', 'ASC');
        $role_nm = Data_model::retrive('role_master', '*', array('role_id' => $this->role_id), 'role_id', 'ASC');
        $user_detail = Data_model::retrive('users', '*', array('id' => $this->user_id), 'id', 'ASC');

        $msg = '<span style="color:#001f3f;font-size:16px;font-weight:bold">Inquiry For</span> : <strong>' . $product_name . '</strong><br>';


        $get_zone_id = Data_model::retrive('quatation_master', '*', array('inquiry_id' => $inquiry_id), 'q_master_id', 'ASC');
        $zone_id = $get_zone_id[0]->zone_id;

        $get_zone_email = Data_model::retrive('quotation_email', '*', array('zone' => $zone_id), 'email', 'ASC');

        $zone_email = $get_zone_email[0]->email;


        if (!empty($title)) {
            if (!empty($send_email_id)) {
                $get_mail_format = Data_model::retrive('email_master', '*', array('email_title' => 'quotation'), 'id', 'DESC');

                if (!empty($get_mail_format)) {
                    $mail_format = $get_mail_format[0]->email_format;
                } else {
                    $mail_format = '';
                }

                $email = $send_email_id;
                $files = array();
                for ($i = 0; $i < count($title); $i++) {
                    $get_file_nm = Data_model::retrive('catalog_master', '*', array('id' => $title[$i]), 'id', 'DESC');
                    $files[] = $get_file_nm[0]->catalog_file;
                }
                $email_to = $email;
                $email_from = $zone_email;
                $email_subject = "CRM Raj Water";

                $fileatt_name = 'Raj Water Quotation';
                $fileatt = 'external/catalog/' . $files[0];

                $semi_rand = md5(time());
                $fileatt_type = "application/pdf";
                $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

                $headers = "From: " . $email_from;
                $headers .= "\nMIME-Version: 1.0\n" .
                        "Content-Type: multipart/mixed;\n" .
                        " boundary=\"{$mime_boundary}\"";



                $email_message = "Raj Water Quotation.<bR>";

                $email_message = $msg . "<bR>";
                $email_message .= $mail_format . "<bR>";

                $email_message .= '<br><br><strong>Thanks and Regards</strong> <br> ' . $company_detail[0]->name . '<br>' . $user_detail[0]->username . '<br>' . $role_nm[0]->role_name . '<br>' . $user_detail[0]->mobile;

                $email_message .= "<br><br>Attachment:<br>";

                $email_message .= "This is a multi-part message in MIME format.\n\n" .
                        "--{$mime_boundary}\n" .
                        "Content-Type:text/html; charset=\"iso-8859-1\"\n" .
                        "Content-Transfer-Encoding: 7bit\n\n" .
                        $email_message .= "\n\n";
                $email_message .= "--{$mime_boundary}\n";

                for ($x = 0; $x < count($files); $x++) {
                    $path = 'external/catalog/' . $files[$x];
                    $file = fopen($path, "rb");
                    $data = fread($file, filesize($path));
                    fclose($file);
                    $data = chunk_split(base64_encode($data));
                    $email_message .= "Content-Type: {$fileatt_type};\n" . " name=\"$fileatt_name\"\n" .
                            "Content-Disposition: attachment;\n" . " filename=\"$fileatt_name\"\n" .
                            "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
                    $email_message .= "--{$mime_boundary}\n";
                }

                $data1['utility'] = $this->utility;
                $data1['with_latterhead'] = 'yes';

                $data1['result'] = Data_model::db_query("SELECT `revise_quatation`.* ,`product_master`.`product_name`,`customer_master`.`mobile` ,`customer_master`.`mobile_2` ,`customer_master`.`mobile_3`,`customer_master`.`email` ,`customer_master`.`email_2`,`customer_master`.`prefix`,`customer_master`.`name` as `customer_name`,`customer_master`.`company`,`customer_master`.`address` ,`customer_master`.`country_id`,`role_master`.`role_name` ,`employee`.`name` as `employee_name` ,`users`.`username` , `users`.`mobile` as `user_mobile` FROM revise_quatation
				LEFT JOIN `" . $this->foreign_table . "` ON `revise_quatation`.`" . $this->foreign_id . "` = `" . $this->foreign_table . "`.`" . $this->foreign_id . "`
				LEFT JOIN `product_master` ON `product_master`.`product_id` = `" . $this->foreign_table . "`.`product_id`
				LEFT JOIN `customer_master` ON `customer_master`.`customer_id` = `" . $this->foreign_table . "`.`customer_id`
				LEFT JOIN `users` ON `users`.`id` = `revise_quatation`.`added_by`
				LEFT JOIN `role_master` ON `role_master`.`role_id` = `users`.`role_id`
				LEFT JOIN `employee` ON `employee`.`emp_id` = `users`.`emp_id`
				where `revise_quatation`.`revise_id` = '$revice_id'");

                $get_cur = Data_model::retrive('country_master', '*', array('country_id' => $data1['result'][0]->country_id), 'country_name', 'DESC');

                if (!empty($get_cur)) {
                    $cur_type = $get_cur[0]->cur_type;
                } else {
                    $cur_type = 'inr';
                }
                $data1['cur_type'] = $cur_type;

                $data1['specification'] = Data_model::retrive('specification_master', '*', array(), 'sequence', 'ASC');
                $data1['quatation_product'] = Data_model::retrive('quatation_product', '*', array(), 'name', 'ASC');
                $data1['terms_condition'] = Data_model::retrive('terms_condition', '*', array(), 'term_id', 'ASC');
                $data1['company'] = Data_model::retrive('company', '*', array(), 'name', 'ASC');
                $data1['controller_name'] = $this->controller;

                $data1['letter_head'] = Data_model::retrive('letterhead_master', '*', array(), 'letterhead_name', 'ASC');

                $pdf = PDF::loadView($this->view . '/revise_print', $data1);
                $pdf = $pdf->stream('Quotation ' . date('d-m-Y H:i:s'));

                $attachment = chunk_split(base64_encode($pdf));

                $email_message .= "Content-Type: {$fileatt_type};\n" . " name=\"$fileatt_name\"\n" .
                        "Content-Disposition: attachment;\n" . " filename=\"$fileatt_name\"\n" .
                        "Content-Transfer-Encoding: base64\n\n" . $attachment . "\n\n";
                $email_message .= "--{$mime_boundary}\n";

                $sent = mail($email_to, $email_subject, $email_message, $headers);

                if ($sent == 1) {
                    /*  set mail flag  */
                    $mail_status = intval($send_status_id) + intval(1);
                    Data_model::restore('quatation', array('send_status' => $mail_status), array('inquiry_id' => $inquiry_id));
                    $response[0] = "success";
                } else {
                    $response[0] = "Mail Not Sent.";
                }
            } else {
                $response[0] = "Please Select Email";
            }
        } else {
            $response[0] = "Please Select Title";
        }

        echo json_encode($response);
        exit;
    }

    public function send_reminder_mail(Request $request) {
        $inquiry_id = $request->input('inquiry_id');
        $title = $request->input('title');

        $get_product = Data_model::db_query("select inquiry.inquiry_id,product_master.product_name from inquiry INNER JOIN product_master ON product_master.product_id = inquiry.product_id where inquiry.inquiry_id = " . $inquiry_id . " ");

        $role_nm = Data_model::retrive('role_master', '*', array('role_id' => $this->role_id), 'role_id', 'ASC');
        $user_detail = Data_model::retrive('users', '*', array('id' => $this->user_id), 'id', 'ASC');

        if (!empty($get_product)) {
            $product_name = $get_product[0]->product_name;
        } else {
            $product_name = '';
        }

        $msg = '<span style="color:#001f3f;font-size:16px;font-weight:bold">Inquiry For</span> : <strong>' . $product_name . '</strong><br>';

        $get_zone_id = Data_model::retrive('quatation_master', '*', array('inquiry_id' => $inquiry_id), 'q_master_id', 'ASC');
        $zone_id = $get_zone_id[0]->zone_id;

        $get_zone_email = Data_model::retrive('quotation_email', '*', array('zone' => $zone_id), 'email', 'ASC');

        $zone_email = $get_zone_email[0]->email;

        $send_email_id = $request->input('reminder_email');

        if (!empty($send_email_id)) {
            $get_mail_format = Data_model::retrive('email_master', '*', array('email_title' => 'inquiry'), 'id', 'DESC');
            $company_detail = Data_model::retrive('company', '*', array(), 'name', 'ASC');

            if (!empty($get_mail_format)) {
                $mail_format = $get_mail_format[0]->email_format;
            } else {
                $mail_format = '';
            }

            $files = array();
            for ($i = 0; $i < count($title); $i++) {
                $get_file_nm = Data_model::retrive('catalog_master', '*', array('id' => $title[$i]), 'id', 'DESC');
                $files[] = $get_file_nm[0]->catalog_file;
            }

            $subject = 'Reminder Mail';
            $to = $send_email_id;
            $email_from = $zone_email;

            $fileatt_name = 'Raj Water Reminder mail';
            $semi_rand = md5(time());
            $fileatt_type = "application/pdf"; // File Type
            $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

            $content = "Raj Water Raminder Mail";
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            $headers .= "From: " . $email_from;
            $headers .= "\nMIME-Version: 1.0\n" .
                    "Content-Type: multipart/mixed;\n" .
                    " boundary=\"{$mime_boundary}\"";

            $message = '<html><body>';
            $message .= '<span style="color:#001f3f;font-size:16px;font-weight:bold">Subject : </span>' . $subject . '<br>';
            $message .= $msg . '<bR>';
            $message .= $mail_format;

            $message .= '<br><br><strong>Thanks and Regards</strong> <br> ' . $company_detail[0]->name . '<br>' . $user_detail[0]->username . '<br>' . $role_nm[0]->role_name . '<br>' . $user_detail[0]->mobile;
            $message .= '</body></html>';

            $message .= "Attachment:<br>";
            $message .= "This is a multi-part message in MIME format.\n\n" .
                    "--{$mime_boundary}\n" .
                    "Content-Type:text/html; charset=\"iso-8859-1\"\n" .
                    "Content-Transfer-Encoding: 7bit\n\n" .
                    $message .= "\n\n";
            $message .= "--{$mime_boundary}\n";
            for ($x = 0; $x < count($files); $x++) {
                $path = 'external/catalog/' . $files[$x];
                $file = fopen($path, "rb");
                $data = fread($file, filesize($path));
                fclose($file);
                $data = chunk_split(base64_encode($data));
                $message .= "Content-Type: {$fileatt_type};\n" . " name=\"$fileatt_name\"\n" .
                        "Content-Disposition: attachment;\n" . " filename=\"$fileatt_name\"\n" .
                        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
                $message .= "--{$mime_boundary}\n";
            }

            $sent = mail($to, $subject, $message, $headers);

            if ($sent == 1) {
                $response[0] = "success";
            } else {
                $response[0] = "Mail Not Sent.";
            }
        } else {
            $response[0] = "Please Select Email";
        }

        echo json_encode($response);
        exit;
    }

    public function generate_quatation($inquiry_id, $q_master_id) {
        $data['utility'] = $this->utility;
        $inquiry_id = $this->utility->decode($inquiry_id);
        $q_master_id = $this->utility->decode($q_master_id);
        $count = DB::select("Select * from " . $this->table . " order by `q_no` DESC LIMIT 1");

        if (date('m') >= 4) {
            $last_year = date('Y');
            $year = date('y') + 1;
        } else {
            $year = date('y');
            $last_year = date('Y') - 1;
        }
        $new_year = $last_year . "-" . $year;

        if (!empty($count)) {
            $data['quatation_no'] = 'RW/' . $new_year . '/Q_' . ($count[0]->q_no + 1);
        } else {
            $data['quatation_no'] = 'RW/' . $new_year . '/Q_1';
        }

        $data['action'] = "update";
        $data['controller_name'] = $this->controller;
        $data['msgName'] = $this->msgName;
        $data['inquiry_id'] = $inquiry_id;
        $data['q_master_id'] = $q_master_id;

        $data['result'] = DB::table($this->foreign_table)
                ->join('customer_master', 'customer_master.customer_id', '=', $this->foreign_table . '.customer_id')
                ->join('users', 'users.id', '=', $this->foreign_table . '.added_by')
                ->select($this->foreign_table . '.*', 'customer_master.*', 'users.username')
                ->where($this->foreign_id, "=", $inquiry_id)
                ->get();

        $get_cur = Data_model::retrive('country_master', '*', array('country_id' => $data['result'][0]->country_id), 'country_name', 'DESC');

        if (!empty($get_cur)) {
            $cur_type = $get_cur[0]->cur_type;
        } else {
            $cur_type = 'inr';
        }
        $data['cur_type'] = $cur_type;
        $data['category'] = Data_model::retrive('category_master', '*', array('delete_status' => 0), 'category_id', 'DESC');
        $data['product'] = Data_model::retrive('product_master', '*', array('delete_status' => 0), 'product_name', 'ASC');
        $data['quatation_product'] = Data_model::retrive('quatation_product', '*', array('delete_status' => 0), 'name', 'ASC');
        $data['country'] = Data_model::retrive('country_master', '*', array('delete_status' => 0), 'country_name', 'ASC');
        $data['state'] = Data_model::retrive('state_master', '*', array('delete_status' => 0), 'state_name', 'ASC');
        $data['city'] = Data_model::retrive('city_master', '*', array('delete_status' => 0), 'city_name', 'ASC');
        $data['employee'] = Data_model::retrive('employee', '*', array('delete_status' => 0), 'name', 'ASC');
        $data['customer'] = Data_model::retrive('customer_master', '*', array(), 'customer_id', 'DESC');
        $data['source'] = Data_model::retrive('source_master', '*', array('delete_status' => 0), 'source_name', 'ASC');
        $data['specification'] = Data_model::retrive('specification_master', '*', array('delete_status' => 0), 'specification', 'ASC');

        $data['sample_quatation'] = Data_model::retrive('sample_quatation', '*', array('delete_status' => 0), 'name', 'ASC');

        $get_rate = Data_model::retrive('rate_master', '*', array('cur_type' => 'Doller'), 'cur_type', 'ASC');
        $data['doller_rate'] = $get_rate[0]->rate;
        return view($this->view . '/generate_quatation', $data);
    }

    public function get_inq_data(Request $request) {
        $inquiry_id = trim($request->inquiry_id);
        $country_id = trim($request->country_id);
        $get_cur = Data_model::retrive('country_master', '*', array('country_id' => $country_id), 'country_name', 'DESC');

        if (!empty($get_cur)) {
            $cur_type = $get_cur[0]->cur_type;
        } else {
            $cur_type = 'inr';
        }
        $data['cur_type'] = $cur_type;
        $get_rate = Data_model::retrive('rate_master', '*', array('cur_type' => 'Doller'), 'cur_type', 'ASC');
        $data['doller_rate'] = $get_rate[0]->rate;
        $data['quatation_product'] = Data_model::retrive('quatation_product', '*', array('delete_status' => 0), 'name', 'ASC');
        $data['result'] = Data_model::retrive('inquiry', '*', array('inquiry_id' => $inquiry_id), 'inquiry_id', 'DESC');
        $data['specification'] = Data_model::retrive('specification_master', '*', array('delete_status' => 0), 'specification', 'ASC');
        return view($this->view . '/get_inq_data', $data);
    }

    public function get_sample_quatation(Request $request) {
        $sq_id = trim($request->sq_id);
        $country_id = trim($request->country_id);
        $get_cur = Data_model::retrive('country_master', '*', array('country_id' => $country_id), 'country_name', 'DESC');

        if (!empty($get_cur)) {
            $cur_type = $get_cur[0]->cur_type;
        } else {
            $cur_type = 'inr';
        }
        $data['cur_type'] = $cur_type;
        $get_rate = Data_model::retrive('rate_master', '*', array('cur_type' => 'Doller'), 'cur_type', 'ASC');
        $data['doller_rate'] = $get_rate[0]->rate;
        $data['specification'] = Data_model::retrive('specification_master', '*', array('delete_status' => 0), 'specification', 'ASC');
        $data['quatation_product'] = Data_model::retrive('quatation_product', '*', array('delete_status' => 0), 'name', 'ASC');
        $data['sample_quatation'] = Data_model::retrive('sample_quatation', '*', array('delete_status' => 0), 'name', 'ASC');
        $data['result'] = Data_model::retrive("sample_quatation", '*', array('sq_id' => $sq_id, 'delete_status' => 0), 'sq_id', 'ASC');
        return view($this->view . '/sample_quatation_change', $data);
    }

    public function get_customer_data(Request $request) {
        $customer_id = $request->input('customer_id');
        $customer = Data_model::retrive('customer_master', '*', array('customer_id' => $customer_id), 'customer_id', 'DESC');

        $st = '';

        $st .= $customer[0]->address . "**" . $customer[0]->state_id . "**" . $customer[0]->city_id . "**" . $customer[0]->mobile;

        if ($customer[0]->mobile_2 != '') {
            $st .= "**" . $customer[0]->mobile_2;
        } else {
            $st .= "**null";
        }

        if ($customer[0]->mobile_3 != '') {
            $st .= "**" . $customer[0]->mobile_3;
        } else {
            $st .= "**null";
        }
        if ($customer[0]->office_address != '') {
            $st .= "**" . $customer[0]->office_address;
        } else {
            $st .= "**null";
        }
        if ($customer[0]->landline != '') {
            $st .= "**" . $customer[0]->landline;
        } else {
            $st .= "**null";
        }
        if ($customer[0]->company != '') {
            $st .= "**" . $customer[0]->company;
        } else {
            $st .= "**null";
        }
        if ($customer[0]->phone_no != '') {
            $st .= "**" . $customer[0]->phone_no;
        } else {
            $st .= "**null";
        }
        if ($customer[0]->email != '') {
            $st .= "**" . $customer[0]->email;
        } else {
            $st .= "**null";
        }
        if ($customer[0]->email_2 != '') {
            $st .= "**" . $customer[0]->email_2;
        } else {
            $st .= "**null";
        }
        if ($customer[0]->country_id != '') {
            $st .= "**" . $customer[0]->country_id;
            $get_country_zone = Data_model::retrive('country_master', 'zone_id', array('country_id' => $customer[0]->country_id), 'country_id', '');

            $data[1] = $get_country_zone[0]->zone_id;
        } else {
            $st .= "**null";
        }
        $data[0] = $st;
        echo json_encode($data);
    }

    public function get_city(Request $request) {
        $state_id = $request->input('state_id');
        $get_city = Data_model::retrive('city_master', '*', array('state_id' => $state_id, 'delete_status' => 0), 'city_name', 'ASC');
        echo '<option  value="">Select</option>';
        foreach ($get_city as $k => $v) {
            echo '<option value="' . $v->city_id . '" >' . $v->city_name . '</option>';
        }
    }

    public function mobile_check(Request $request) {
        $customer_id = trim($request->customer_id);
        $mobile = trim($request->mobile);

        if ($customer_id == '') {
            $check = Data_model::db_query("SELECT * FROM `customer_master` WHERE `mobile`='$mobile' OR `mobile_2` = '$mobile' OR `mobile_3` = '$mobile'");
            if (count($check)) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            $check = Data_model::db_query("SELECT * FROM `customer_master` WHERE (`mobile`='$mobile' OR `mobile_2` = '$mobile' OR `mobile_3` = '$mobile') AND `customer_id` != $customer_id");
            if (count($check)) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    public function email_check(Request $request) {
        $customer_id = trim($request->customer_id);
        $email = trim($request->email);

        if ($customer_id == '') {
            $check = Data_model::db_query("SELECT * FROM `customer_master` WHERE `email`='$email' OR `email_2` = '$email'");
            if (count($check)) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            $check = Data_model::db_query("SELECT * FROM `customer_master` WHERE (`email`='$email' OR `email_2` = '$email') AND `customer_id` != $customer_id");
            if (count($check)) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    public function get_country_zone(Request $request) {
        $country_id = trim($request->country_id);
        $check = Data_model::retrive("country_master", '*', array('country_id' => $country_id), 'country_id', '');
        $state = Data_model::retrive("state_master", '*', array('country_id' => $country_id, 'delete_status' => 0), 'state_name', 'ASC');
        $data[0] = $check[0]->zone_id;
        $data[1] = '<option  value="">Select</option>';
        foreach ($state as $k => $v) {
            $data[1] .= '<option value="' . $v->state_id . '">' . $v->state_name . '</option>';
        }
        echo json_encode($data);
    }

    public function get_rate(Request $request) {
        $quatation_product_id = $request->quatation_product_id;
        $country_id = trim($request->country_id);
        $get_cur = Data_model::retrive('country_master', '*', array('country_id' => $country_id), 'country_name', 'DESC');

        if (!empty($get_cur)) {
            $cur_type = $get_cur[0]->cur_type;
        } else {
            
        }

        $get_rate = Data_model::retrive('rate_master', '*', array('cur_type' => 'Doller'), 'cur_type', 'ASC');
        $doller_rate = $get_rate[0]->rate;

        $get_rate = Data_model::retrive('quatation_product', '*', array('p_id' => $quatation_product_id), 'p_id', '');
        if (count($get_rate)) {
            if ($cur_type == 'dollar') {
                $new_rate = number_format((float) floatval($get_rate[0]->rate) / floatval($doller_rate), 2, '.', '');
                $new_rate = ceil($new_rate);
                $data[0] = $new_rate;
            } else {
                $data[0] = $get_rate[0]->rate;
            }
        } else {
            $data[0] = 0;
        }

        echo json_encode($data);
    }

    public function add_quatation(Request $request) {
        if (date('m') >= 4) {
            $last_year = date('Y');
            $year = date('y') + 1;
        } else {
            $year = date('y');
            $last_year = date('Y') - 1;
        }
        $new_year = $last_year . "-" . $year;

        $inquiry_id = $request->input("inquiry_id");
        $q_master_id = $request->input("q_master_id");

        $inquiry_no = $request->input('inquiry_no');
        $inquiry_date = date("Y-m-d", strtotime($request->input('inquiry_date')));
        $quatation_no = $request->input('quatation_no');
        $explode_q_no = explode('_', $quatation_no);
        $q_no = $explode_q_no[1];
        $quatation_date = date("Y-m-d");
        $quatation_time = date("H:i:s");

        $quatation_product_id = implode(',', $request->quatation_product_id);
        $rate = implode(',', $request->rate);
        $qty = implode(',', $request->qty);
        $amount = implode(',', $request->amount);
        /* $gross_amount = $request->gross_amount;
          $gst_amount = $request->gst_amount; */
        $discount = $request->input('discount');
        $total = $request->input('total');
        $total_amount = $request->total_amount;

        $user_id = Session::get('raj_user_id');
        $added_time = date("Y-m-d H:i:s");

        if (!empty($request->specification_id)) {
            $specification_id = implode("*****", $request->specification_id);
            $specification_arr = $request->specification_id;

            $spe_name_arr = array();
            $spe_value_arr = array();
            $sequence_arr = array();
            foreach ($specification_arr as $key => $value) {
                $sequence_arr[] .= $request->input($value . "_seqence");
                $nm = $request->input($value . "_spe_name");
                $spe_name_arr[] .= implode("+++++", $nm);

                $val = $request->input($value . "_spe_value");
                $spe_value_arr[] .= implode("+++++", $val);
            }
            $spe_name = implode("*****", $spe_name_arr);
            $spe_value = implode("*****", $spe_value_arr);
        } else {
            $specification_id = '';
            $spe_name = '';
            $spe_value = '';
        }


        $product_id = $request->input('product_id');
        $category_id = $request->input('category_id');
        $p_id = implode(",", $request->input('quatation_product_id'));
        $p_rate = implode(",", $request->input('rate'));

        $insentive = $request->input('insentive');
        if ($insentive == 'no') {
            $attended_by = 0;
        } else {
            $attended_by = $request->input('attended_by');
        }

        $customer_id = $request->input('customer_id');
        $name = $request->input('name');
        $address = $request->input('address');
        $country_id = $request->input('country_id');
        $state_id = $request->input('state_id');
        $city_id = $request->input('city_id');
        $get_country_zone = Data_model::retrive('country_master', '*', array('country_id' => $country_id), 'country_id', '');

        $get_country_name = $get_country_zone[0]->country_name;



        if ($get_country_zone[0]->zone_id != 0) {
            $inquery_zone_id = $get_country_zone[0]->zone_id;
            $state_id = 0;
            $city_id = 0;
        } else {
            $get_zone = Data_model::retrive('state_master', 'zone_id', array('state_id' => $state_id), 'state_id', 'DESC');
            $inquery_zone_id = $get_zone[0]->zone_id;
        }


        $get_currency = Data_model::retrive('country_master', 'cur_type', array('country_id' => $country_id), 'country_id', '');
        if (!empty($get_currency)) {
            $cur_type = $get_currency[0]->cur_type;
        } else {
            $cur_type = '';
        }

        if ($cur_type == 'dollar') {
            $get_amount = Data_model::retrive('rate_master', 'rate', array('cur_type' => 'Doller'), 'id', '');
            $doller_rate = $get_amount[0]->rate;
            $check_amount = floatval($doller_rate) * floatval($total_amount);
        } else {
            $check_amount = $total_amount;
        }


        if ($get_country_name == 'India') {

            $get_amount = Data_model::retrive('big_zone_amount', 'rate', array(), 'id', '');
            $big_zone_amount = $get_amount[0]->rate;

            if (floatval($check_amount) >= floatval($big_zone_amount)) {
                $get_big_zone = Data_model::retrive('zone_master', 'zone_id', array('zone_name' => 'BIG PROJECT'), 'zone_id', '');
                $inquery_zone_id = $get_big_zone[0]->zone_id;
            }
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

        $customer_prefix = $request->input('customer_prefix');
        $mtype1 = $request->input('mtype1');
        $mtype2 = $request->input('mtype2');
        $mtype3 = $request->input('mtype3');

        $customer_type = $request->input('customer_type');

        if ($customer_type == 'new') {
            $customer_data = array('prefix' => $customer_prefix, 'name' => $name, 'address' => $address, 'country_id' => $country_id, 'state_id' => $state_id, 'city_id' => $city_id, 'mobile' => $mobile, 'mobile_2' => $mobile_2, 'mobile_3' => $mobile_3, 'mobile_type1' => $mtype1, 'mobile_type2' => $mtype2, 'mobile_type3' => $mtype3, 'office_address' => $office_address, 'landline' => $landline, 'company' => $company, 'email' => $email, 'email_2' => $email_2);
        } else {
            $customer_data = array('address' => $address, 'country_id' => $country_id, 'state_id' => $state_id, 'city_id' => $city_id, 'mobile' => $mobile, 'mobile_2' => $mobile_2, 'mobile_3' => $mobile_3, 'mobile_type1' => $mtype1, 'mobile_type2' => $mtype2, 'mobile_type3' => $mtype3, 'office_address' => $office_address, 'landline' => $landline, 'company' => $company, 'email' => $email, 'email_2' => $email_2);
        }

        $data = array(
            'q_master_id' => $q_master_id, 'inquiry_id' => $inquiry_id, 'zone_id' => $inquery_zone_id, 'inquiry_no' => $inquiry_no, 'inquiry_date' => $inquiry_date, /* 'q_no'=>$q_no,'quatation_no'=>$quatation_no, */ 'quatation_date' => $quatation_date, 'quatation_time' => $quatation_time, 'quatation_product_id' => $quatation_product_id, 'rate' => $rate, 'qty' => $qty, 'amount' => $amount, /* 'gross_amount'=>$gross_amount,'gst_amount'=>$gst_amount, */ 'total' => $total, 'discount' => $discount, 'total_amount' => $total_amount, 'specification_id' => $specification_id, 'spe_name' => $spe_name, 'spe_value' => $spe_value,
            'added_by' => $user_id, 'added_time' => $added_time, 'year' => $new_year);

        $quotCheck = Data_model::retrive('quatation', '*', array('inquiry_id' => $inquiry_id), 'q_no', '');
        if (!empty($quotCheck)) {
            $msg = array('error' => 'Quotation Already Generated.');
            return redirect($this->controller)->with($msg);
        }
        if ($id = Data_model::store($this->table, $data)) {
            /* year wise get quotation number */
            $get_num = Data_model::db_query("select * from `quatation` where `year`='" . $new_year . "' Order By `q_no` desc limit 1 ");

            if (empty($get_num)) {
                $yearNo = '1';
            } else {
                $yearNo = $get_num[0]->q_no + 1;
            }

            $explode_q_no = explode('_', $quatation_no);
            $quotation_number = $explode_q_no[0] . '_' . $yearNo;
            $q_no = $yearNo;

            Data_model::restore($this->table, array('q_no' => $q_no, 'quatation_no' => $quotation_number), array('quatation_id' => $id));

            Data_model::restore('customer_master', $customer_data, array('customer_id' => $customer_id));

            Data_model::restore('inquiry', array('product_id' => $product_id, 'p_id' => $p_id, /* 'p_rate'=>$p_rate, */ 'category_id' => $category_id, 'insentive' => $insentive, 'attended_by' => $attended_by, 'customer_type' => $customer_type, 'customer_id' => $customer_id, 'source_id' => $source_id, 'project_zone' => $inquery_zone_id, 'project_state' => $project_state, 'project_city' => $project_city, 'remarks' => $remarks, 'updated_by' => $user_id, 'first_quatation_id' => $id), array('inquiry_id' => $inquiry_id));

            Data_model::restore('quatation_master', array('zone_id' => $inquery_zone_id), array('inquiry_id' => $inquiry_id));

            $msg = array('success' => 'Quotation Added Sucessfully');
        } else {
            $msg = array('error' => 'Some Problem ... Please Try Again');
        }
        return redirect($this->controller)->with($msg);
    }

    public function view($id) {
        $data['utility'] = $this->utility;
        $data['msg'] = $this->msgName;
        $quatation_id = $this->utility->decode($id);

        $data['result'] = Data_model::db_query("SELECT `" . $this->table . "`.* ,`product_master`.`product_name`,`customer_master`.`mobile` ,`customer_master`.`mobile_2` ,`customer_master`.`mobile_3` ,`customer_master`.`prefix` ,`customer_master`.`email` ,`customer_master`.`email_2`,`customer_master`.`name` as `customer_name`,`customer_master`.`company`,`customer_master`.`address`,`customer_master`.`country_id` ,`role_master`.`role_name` , `users`.`username` , `employee`.`name` as `employee_name`, `users`.`mobile` as `user_mobile`,`country_master`.`country_name`,`state_master`.`state_name`,`city_master`.`city_name` FROM " . $this->table . "
		LEFT JOIN `" . $this->foreign_table . "` ON `" . $this->table . "`.`" . $this->foreign_id . "` = `" . $this->foreign_table . "`.`" . $this->foreign_id . "`
		LEFT JOIN `product_master` ON `product_master`.`product_id` = `" . $this->foreign_table . "`.`product_id`
		LEFT JOIN `customer_master` ON `customer_master`.`customer_id` = `" . $this->foreign_table . "`.`customer_id`
		LEFT JOIN `users` ON `users`.`id` = `" . $this->table . "`.`added_by`
		LEFT JOIN `role_master` ON `role_master`.`role_id` = `users`.`role_id`
		LEFT JOIN `employee` ON `employee`.`emp_id` = `users`.`emp_id`
		LEFT JOIN `country_master` ON `country_master`.country_id = `customer_master`.`country_id`
		LEFT JOIN `state_master` ON `state_master`.state_id = `customer_master`.`state_id`
		LEFt JOIN `city_master` On `city_master`.city_id = `customer_master`.`city_id`
		where `" . $this->table . "`.`" . $this->primary_id . "` = '" . $quatation_id . "'");

        $get_cur = Data_model::retrive('country_master', '*', array('country_id' => $data['result'][0]->country_id), 'country_name', 'DESC');

        if (!empty($get_cur)) {
            $cur_type = $get_cur[0]->cur_type;
        } else {
            $cur_type = 'inr';
        }
        $data['cur_type'] = $cur_type;

        $data['specification'] = Data_model::retrive('specification_master', '*', array(), 'sequence', 'ASC');
        $data['quatation_product'] = Data_model::retrive('quatation_product', '*', array(), 'name', 'ASC');
        $data['terms_condition'] = Data_model::retrive('terms_condition', '*', array(), 'term_id', 'ASC');
        $data['company'] = Data_model::retrive('company', '*', array(), 'name', 'ASC');
        $data['controller_name'] = $this->controller;
        $data['msgName'] = $this->msgName;
        return view($this->view . '/view', $data);
    }

    public function print_quatation($id, $type, $with_latterhead) {
        $data['utility'] = $this->utility;
        $quatation_id = $this->utility->decode($id);
        $type = $this->utility->decode($type);
        $data['with_latterhead'] = $this->utility->decode($with_latterhead);

//        $data['result'] = Data_model::db_query("SELECT `" . $this->table . "`.* ,`product_master`.`product_name`,`customer_master`.`mobile` ,`customer_master`.`mobile_2`,`customer_master`.`mobile_3`,`customer_master`.`prefix`,`customer_master`.`email`,`customer_master`.`email_2` ,`customer_master`.`name` as `customer_name` ,`customer_master`.`company`,`customer_master`.`address`,`customer_master`.`country_id`,`role_master`.`role_name` , `users`.`username` ,`employee`.`name` as `employee_name` ,`users`.`mobile` as `user_mobile`,country_master.country_name,state_master.state_name,city_master.city_name FROM " . $this->table . "
//		LEFT JOIN `" . $this->foreign_table . "` ON `" . $this->table . "`.`" . $this->foreign_id . "` = `" . $this->foreign_table . "`.`" . $this->foreign_id . "`
//		LEFT JOIN `product_master` ON `product_master`.`product_id` = `" . $this->foreign_table . "`.`product_id`
//		LEFT JOIN `customer_master` ON `customer_master`.`customer_id` = `" . $this->foreign_table . "`.`customer_id`
//		INNER JOIN country_master ON country_master.country_id = customer_master.country_id
//		LEFT JOIN state_master ON state_master.state_id = customer_master.state_id
//		LEFT JOIN city_master ON city_master.city_id = customer_master.city_id
//		LEFT JOIN `users` ON `users`.`id` = `" . $this->table . "`.`added_by`
//		LEFT JOIN `role_master` ON `role_master`.`role_id` = `users`.`role_id`
//		LEFT JOIN `employee` ON `employee`.`emp_id` = `users`.`emp_id`
//		where `" . $this->table . "`.`" . $this->primary_id . "` = '" . $quatation_id . "'");

        $data['result'] = DB::table($this->table)->select($this->table . ".*", "product_master.product_name", "customer_master.mobile", "customer_master.mobile_2", "customer_master.mobile_3", "customer_master.prefix", "customer_master.email", "customer_master.email_2", "customer_master.name AS customer_name", "customer_master.company", "customer_master.address", "customer_master.country_id", "role_master.role_name", "users.username", "employee.name AS employee_name", "users.mobile AS user_mobile", "country_master.country_name", "state_master.state_name", "city_master.city_name")
                ->leftJoin($this->foreign_table, $this->foreign_table . "." . $this->foreign_id, '=', $this->table . "." . $this->foreign_id)
                ->leftJoin('product_master', 'product_master.product_id', '=', $this->foreign_table . ".product_id")
                ->leftJoin('customer_master', 'customer_master.customer_id', '=', $this->foreign_table . ".customer_id")
                ->leftJoin('country_master', 'country_master.country_id', '=', "customer_master.country_id")
                ->leftJoin('state_master', 'state_master.state_id', '=', "customer_master.state_id")
                ->leftJoin('city_master', 'city_master.city_id', '=', "customer_master.city_id")
                ->leftJoin('users', 'users.id', '=', $this->table . ".added_by")
                ->leftJoin('role_master', 'role_master.role_id', '=', "users.role_id")
                ->leftJoin('employee', 'employee.emp_id', '=', "users.emp_id")
                ->where($this->table . "." . $this->primary_id, '=', $quatation_id)
                ->first();
        $get_cur = Data_model::retrive('country_master', '*', array('country_id' => $data['result']->country_id), 'country_name', 'DESC');

        if (!empty($get_cur)) {
            $cur_type = $get_cur[0]->cur_type;
        } else {
            $cur_type = 'inr';
        }
        $data['cur_type'] = $cur_type;

        $data['specification'] = Data_model::retrive('specification_master', '*', array(), 'sequence', 'ASC');
        $data['quatation_product'] = Data_model::retrive('quatation_product', '*', array(), 'name', 'ASC');
        $data['terms_condition'] = Data_model::retrive('terms_condition', '*', array(), 'term_id', 'ASC');
        $data['company'] = Data_model::retrive('company', '*', array(), 'name', 'ASC');
        $data['controller_name'] = $this->controller;

        $data['letter_head'] = Data_model::retrive('letterhead_master', '*', array(), 'letterhead_name', 'ASC');
        if ($type == 'print') {
            $pdf = PDF::loadView($this->view . '/print', $data);
            return $pdf->stream('Quotation ' . date('d-m-Y H:i:s'));
        } else {
            $pdf = PDF::loadView($this->view . '/print', $data);
            return $pdf->download('Quotation_' . date('d-m-Y H:i:s') . '.pdf');
        }
    }

    public function revise_quatation($inquiry_id, $quatation_id) {
        $data['utility'] = $this->utility;
        $inquiry_id = $this->utility->decode($inquiry_id);
        $quatation_id = $this->utility->decode($quatation_id);


        $data['quatation_result'] = DB::table($this->table)
                ->join('users', 'users.id', '=', $this->table . '.added_by')
                ->select($this->table . '.*', 'users.username')
                ->where($this->primary_id, "=", $quatation_id)
                ->get();

        $count = Data_model::retrive('revise_quatation', '*', array($this->primary_id => $quatation_id), 'rq_no', 'DESC');

        if (count($count)) {
            $data['revise_quatation_no'] = $data['quatation_result'][0]->quatation_no . '/R_' . ($count[0]->rq_no + 1);
            $data['revice_quatation_result'] = $count;
        } else {
            $data['revise_quatation_no'] = $data['quatation_result'][0]->quatation_no . '/R_1';
        }
        $data['action'] = "update";
        $data['controller_name'] = $this->controller;
        $data['msgName'] = $this->msgName;
        $data['inquiry_id'] = $inquiry_id;
        $data['quatation_id'] = $quatation_id;

        $data['result'] = DB::table($this->foreign_table)
                ->join('customer_master', 'customer_master.customer_id', '=', $this->foreign_table . '.customer_id')
                ->join('users', 'users.id', '=', $this->foreign_table . '.added_by')
                ->select($this->foreign_table . '.*', 'customer_master.*', 'users.username')
                ->where($this->foreign_id, "=", $inquiry_id)
                ->get();

        $get_cur = Data_model::retrive('country_master', '*', array('country_id' => $data['result'][0]->country_id), 'country_name', 'DESC');

        if (!empty($get_cur)) {
            $cur_type = $get_cur[0]->cur_type;
        } else {
            $cur_type = 'inr';
        }
        $data['cur_type'] = $cur_type;

        $data['category'] = Data_model::retrive('category_master', '*', array('delete_status' => 0), 'category_id', 'DESC');
        $data['product'] = Data_model::retrive('product_master', '*', array('delete_status' => 0), 'product_name', 'ASC');
        $data['quatation_product'] = Data_model::retrive('quatation_product', '*', array('delete_status' => 0), 'name', 'ASC');
        $data['country'] = Data_model::retrive('country_master', '*', array('delete_status' => 0), 'country_name', 'ASC');
        $data['state'] = Data_model::retrive('state_master', '*', array('delete_status' => 0), 'state_name', 'ASC');
        $data['city'] = Data_model::retrive('city_master', '*', array('delete_status' => 0), 'city_name', 'ASC');
        $data['employee'] = Data_model::retrive('employee', '*', array('delete_status' => 0), 'name', 'ASC');
        $data['customer'] = Data_model::retrive('customer_master', '*', array(), 'customer_id', 'DESC');
        $data['source'] = Data_model::retrive('source_master', '*', array('delete_status' => 0), 'source_name', 'ASC');
        $data['specification'] = Data_model::retrive('specification_master', '*', array('delete_status' => 0), 'specification', 'ASC');
        $data['sample_quatation'] = Data_model::retrive('sample_quatation', '*', array('delete_status' => 0), 'name', 'ASC');
        return view($this->view . '/revise_generate_quatation', $data);
    }

    public function revise_quatation_desktop($inquiry_id) {
        $data['utility'] = $this->utility;
        $inquiry_id = $this->utility->decode($inquiry_id);

        /* 	get quatation id */
        $get_quatation_id = Data_model::retrive('quatation', '*', array('inquiry_id' => $inquiry_id), 'inquiry_id', 'ASC');
        if (!empty($get_quatation_id)) {
            $quatation_id = $get_quatation_id[0]->quatation_id;
        } else {
            $quatation_id = 0;
        }


        $data['quatation_result'] = DB::table($this->table)
                ->join('users', 'users.id', '=', $this->table . '.added_by')
                ->select($this->table . '.*', 'users.username')
                ->where($this->primary_id, "=", $quatation_id)
                ->get();

        $count = Data_model::retrive('revise_quatation', '*', array($this->primary_id => $quatation_id), 'rq_no', 'DESC');

        if (count($count)) {
            $data['revise_quatation_no'] = $data['quatation_result'][0]->quatation_no . '/R_' . ($count[0]->rq_no + 1);
            $data['revice_quatation_result'] = $count;
        } else {
            $data['revise_quatation_no'] = $data['quatation_result'][0]->quatation_no . '/R_1';
        }
        $data['action'] = "update";
        $data['controller_name'] = $this->controller;
        $data['msgName'] = $this->msgName;
        $data['inquiry_id'] = $inquiry_id;
        $data['quatation_id'] = $quatation_id;

        $data['result'] = DB::table($this->foreign_table)
                ->join('customer_master', 'customer_master.customer_id', '=', $this->foreign_table . '.customer_id')
                ->join('users', 'users.id', '=', $this->foreign_table . '.added_by')
                ->select($this->foreign_table . '.*', 'customer_master.*', 'users.username')
                ->where($this->foreign_id, "=", $inquiry_id)
                ->get();

        $get_cur = Data_model::retrive('country_master', '*', array('country_id' => $data['result'][0]->country_id), 'country_name', 'DESC');

        if (!empty($get_cur)) {
            $cur_type = $get_cur[0]->cur_type;
        } else {
            $cur_type = 'inr';
        }
        $data['cur_type'] = $cur_type;

        $data['category'] = Data_model::retrive('category_master', '*', array('delete_status' => 0), 'category_id', 'DESC');
        $data['product'] = Data_model::retrive('product_master', '*', array('delete_status' => 0), 'product_name', 'ASC');
        $data['quatation_product'] = Data_model::retrive('quatation_product', '*', array('delete_status' => 0), 'name', 'ASC');
        $data['country'] = Data_model::retrive('country_master', '*', array('delete_status' => 0), 'country_name', 'ASC');
        $data['state'] = Data_model::retrive('state_master', '*', array('delete_status' => 0), 'state_name', 'ASC');
        $data['city'] = Data_model::retrive('city_master', '*', array('delete_status' => 0), 'city_name', 'ASC');
        $data['employee'] = Data_model::retrive('employee', '*', array('delete_status' => 0), 'name', 'ASC');
        $data['customer'] = Data_model::retrive('customer_master', '*', array(), 'customer_id', 'DESC');
        $data['source'] = Data_model::retrive('source_master', '*', array('delete_status' => 0), 'source_name', 'ASC');
        $data['specification'] = Data_model::retrive('specification_master', '*', array('delete_status' => 0), 'specification', 'ASC');
        $data['sample_quatation'] = Data_model::retrive('sample_quatation', '*', array('delete_status' => 0), 'name', 'ASC');
        return view($this->view . '/revise_generate_quatation', $data);
    }

    public function get_cur_type(Request $request) {
        $country_id = $request->input("country_id");
        $get_cur = Data_model::retrive('country_master', '*', array('country_id' => $country_id), 'country_name', 'DESC');

        if (!empty($get_cur)) {
            $cur_type = $get_cur[0]->cur_type;
        } else {
            $cur_type = 'inr';
        }
        $data['cur_type'] = $cur_type;
        return view($this->view . '/cur_type', $data);
    }

    public function add_revise_quatation(Request $request) {
        if (date('m') >= 4) {
            $last_year = date('Y');
            $year = date('y') + 1;
        } else {
            $year = date('y');
            $last_year = date('Y') - 1;
        }
        $new_year = $last_year . "-" . $year;

        $inquiry_id = $request->input("inquiry_id");
        $quatation_id = $request->input("quatation_id");


        $revise_quatation_no = $request->input('revise_quatation_no');
        $revise_date = date("Y-m-d");
        $revise_time = date("H:i:s");

        $rq_explode = explode('/', $revise_quatation_no);
        $rq_no_explode = explode('_', $rq_explode[3]);
        $rq_no = $rq_no_explode[1];

        $inquiry_no = $request->input('inquiry_no');
        $inquiry_date = date("Y-m-d", strtotime($request->input('inquiry_date')));

        $quatation_no = $request->input('quatation_no');
        $quatation_date = date("Y-m-d");
        $quatation_time = date("H:i:s");

        $quatation_product_id = implode(',', $request->quatation_product_id);
        $rate = implode(',', $request->rate);
        $qty = implode(',', $request->qty);
        $amount = implode(',', $request->amount);
        /* $gross_amount = $request->gross_amount;
          $gst_amount = $request->gst_amount; */
        $total = $request->input('total');
        $discount = $request->input('discount');
        $total_amount = $request->input('total_amount');

        $user_id = Session::get('raj_user_id');
        $added_time = date("Y-m-d H:i:s");

        if (!empty($request->specification_id)) {
            $specification_id = implode("*****", $request->specification_id);
            $specification_arr = $request->specification_id;
            $spe_name_arr = array();
            $spe_value_arr = array();
            $sequence_arr = array();
            foreach ($specification_arr as $key => $value) {
                $sequence_arr[] .= $request->input($value . "_seqence");
                $nm = $request->input($value . "_spe_name");
                $spe_name_arr[] .= implode("+++++", $nm);

                $val = $request->input($value . "_spe_value");
                $spe_value_arr[] .= implode("+++++", $val);
            }
            $spe_name = implode("*****", $spe_name_arr);
            $spe_value = implode("*****", $spe_value_arr);
        } else {
            $specification_id = '';
            $spe_name = '';
            $spe_value = '';
        }

        $product_id = $request->input('product_id');
        $category_id = $request->input('category_id');
        $p_id = implode(",", $request->input('quatation_product_id'));
        $p_rate = implode(",", $request->input('rate'));

        $insentive = $request->input('insentive');
        if ($insentive == 'no') {
            $attended_by = 0;
        } else {
            $attended_by = $request->input('attended_by');
        }

        $customer_id = $request->input('customer_id');
        $name = $request->input('name');
        $address = $request->input('address');
        $country_id = $request->input('country_id');
        $state_id = $request->input('state_id');
        $city_id = $request->input('city_id');
        $get_country_zone = Data_model::retrive('country_master', '*', array('country_id' => $country_id), 'country_id', '');

        $get_country_name = $get_country_zone[0]->country_name;

        if ($get_country_zone[0]->zone_id != 0) {
            $inquery_zone_id = $get_country_zone[0]->zone_id;
            $state_id = 0;
            $city_id = 0;
        } else {
            $get_zone = Data_model::retrive('state_master', 'zone_id', array('state_id' => $state_id), 'state_id', 'DESC');
            $inquery_zone_id = $get_zone[0]->zone_id;
        }


        $get_currency = Data_model::retrive('country_master', 'cur_type', array('country_id' => $country_id), 'country_id', '');
        if (!empty($get_currency)) {
            $cur_type = $get_currency[0]->cur_type;
        } else {
            $cur_type = '';
        }

        if ($cur_type == 'dollar') {
            $get_amount = Data_model::retrive('rate_master', 'rate', array('cur_type' => 'Doller'), 'id', '');
            $doller_rate = $get_amount[0]->rate;
            $check_amount = floatval($doller_rate) * floatval($total_amount);
        } else {
            $check_amount = $total_amount;
        }

        if ($get_country_name == 'India') {

            $get_amount = Data_model::retrive('big_zone_amount', 'rate', array(), 'id', '');
            $big_zone_amount = $get_amount[0]->rate;


            if (floatval($big_zone_amount) <= floatval($check_amount)) {
                $get_big_zone = Data_model::retrive('zone_master', 'zone_id', array('zone_name' => 'BIG PROJECT'), 'zone_id', '');
                $inquery_zone_id = $get_big_zone[0]->zone_id;
            }
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

        $customer_prefix = $request->input('customer_prefix');
        $mtype1 = $request->input('mtype1');
        $mtype2 = $request->input('mtype2');
        $mtype3 = $request->input('mtype3');


        $customer_type = $request->input('customer_type');

        if ($customer_type == 'new') {
            $customer_data = array('prefix' => $customer_prefix, 'name' => $name, 'address' => $address, 'country_id' => $country_id, 'state_id' => $state_id, 'city_id' => $city_id, 'mobile' => $mobile, 'mobile_2' => $mobile_2, 'mobile_3' => $mobile_3, 'mobile_type1' => $mtype1, 'mobile_type2' => $mtype2, 'mobile_type3' => $mtype3, 'office_address' => $office_address, 'landline' => $landline, 'company' => $company, 'email' => $email, 'email_2' => $email_2);
        } else {
            $customer_data = array('address' => $address, 'country_id' => $country_id, 'state_id' => $state_id, 'city_id' => $city_id, 'mobile' => $mobile, 'mobile_2' => $mobile_2, 'mobile_3' => $mobile_3, 'mobile_type1' => $mtype1, 'mobile_type2' => $mtype2, 'mobile_type3' => $mtype3, 'office_address' => $office_address, 'landline' => $landline, 'company' => $company, 'email' => $email, 'email_2' => $email_2);
        }

        $data = array(
            'rq_no' => $rq_no,
            'revise_quatation_no' => $revise_quatation_no,
            'revise_date' => $revise_date,
            'revise_time' => $revise_time,
            'inquiry_id' => $inquiry_id,
            'quatation_id' => $quatation_id,
            'quatation_product_id' => $quatation_product_id,
            'rate' => $rate, 'qty' => $qty,
            'amount' => $amount,
            /* 'gross_amount'=>$gross_amount,
              'gst_amount'=>$gst_amount, */
            'total' => $total,
            'discount' => $discount,
            'total_amount' => $total_amount,
            'specification_id' => $specification_id,
            'spe_name' => $spe_name,
            'spe_value' => $spe_value,
            'added_by' => $user_id,
            'added_time' => $added_time,
            'year' => $new_year);

        $count = Data_model::retrive('revise_quatation', '*', array($this->primary_id => $quatation_id), 'rq_no', 'DESC');
        if ($id = Data_model::store('revise_quatation', $data)) {
            if (count($count)) {
                $quot_num = $quatation_no . '/R_' . ($count[0]->rq_no + 1);
                $rq_num = $count[0]->rq_no + 1;
            } else {
                $quot_num = $quatation_no . '/R_1';
                $rq_num = 1;
            }

            Data_model::restore('revise_quatation', array('rq_no' => $rq_num, 'revise_quatation_no' => $quot_num), array('revise_id' => $id));

            Data_model::restore('customer_master', $customer_data, array('customer_id' => $customer_id));

            Data_model::restore('inquiry', array('product_id' => $product_id, 'p_id' => $p_id, 'p_rate' => $p_rate, 'category_id' => $category_id, 'insentive' => $insentive, 'attended_by' => $attended_by, 'customer_type' => $customer_type, 'customer_id' => $customer_id, 'source_id' => $source_id, 'project_zone' => $inquery_zone_id, 'project_state' => $project_state, 'project_city' => $project_city, 'remarks' => $remarks, 'updated_by' => $user_id, 'first_quatation_id' => $id), array('inquiry_id' => $inquiry_id));

            Data_model::restore('quatation_master', array('zone_id' => $inquery_zone_id), array('inquiry_id' => $inquiry_id));
            Data_model::restore('quatation', array('zone_id' => $inquery_zone_id), array('inquiry_id' => $inquiry_id));

            $msg = array('success' => 'Quotation Added Sucessfully');
        } else {
            $msg = array('error' => 'Some Problem ... Please Try Again');
        }
        return redirect($this->controller)->with($msg);
    }

    public function revise_quatation_index($id) {
        $data['utility'] = $this->utility;
        $role_session = Session::get('raj_role_id');
        $zone_session = Session::get('raj_zone_id');

        $quatation_id = $this->utility->decode($id);

        $masterTable = 'revise_quatation';
        $parentTable = $this->table;
        $select = array($masterTable . '.*', $parentTable . '.*');
        $condition_1 = $masterTable . '.' . $this->primary_id;
        $condition_2 = $parentTable . '.' . $this->primary_id;
        $orderField = $masterTable . '.revise_id';
        $orderType = 'DESC';

        if ($role_session == '1') {
            $where = array($masterTable . '.' . $this->primary_id => $quatation_id);
            $data['result'] = Data_model::singleJoin($masterTable, $parentTable, $select, $condition_1, $condition_2, $where, $orderField, $orderType);
        } else {

            $where = "find_in_set(" . $parentTable . ".zone_id,'$zone_session') AND $masterTable.quatation_id = " . $quatation_id . " ";

            $data['result'] = Data_model::singleJoin_raw($masterTable, $parentTable, $select, $condition_1, $condition_2, $where, $orderField, $orderType);
        }



        $data['controller_name'] = $this->controller;
        $data['msgName'] = 'Revise Quotation';


        return view($this->view . '/revise_manage', $data);
    }

    public function revise_quatation_view($id) {
        $data['utility'] = $this->utility;
        $data['msg'] = $this->msgName;
        $revise_quatation_id = $this->utility->decode($id);

        $data['result'] = Data_model::db_query("SELECT `revise_quatation`.* ,`product_master`.`product_name`,`customer_master`.`mobile`,`customer_master`.`mobile_2`,`customer_master`.`mobile_3` ,`customer_master`.`email` ,`customer_master`.`email_2` ,`customer_master`.`prefix` ,`customer_master`.`name` as `customer_name`,`customer_master`.`company`,`customer_master`.`address`,`customer_master`.`country_id` ,`role_master`.`role_name` , `users`.`username` ,`employee`.name as `employee_name` ,`users`.`mobile` as `user_mobile` FROM revise_quatation
		LEFT JOIN `" . $this->foreign_table . "` ON `revise_quatation`.`" . $this->foreign_id . "` = `" . $this->foreign_table . "`.`" . $this->foreign_id . "`
		LEFT JOIN `product_master` ON `product_master`.`product_id` = `" . $this->foreign_table . "`.`product_id`
		LEFT JOIN `customer_master` ON `customer_master`.`customer_id` = `" . $this->foreign_table . "`.`customer_id`
		LEFT JOIN `users` ON `users`.`id` = `revise_quatation`.`added_by`
		LEFT JOIN `role_master` ON `role_master`.`role_id` = `users`.`role_id`
		LEFT JOIN `employee` ON `employee`.`emp_id` = `users`.`emp_id`
		where `revise_quatation`.`revise_id` = '$revise_quatation_id'");

        $get_cur = Data_model::retrive('country_master', '*', array('country_id' => $data['result'][0]->country_id), 'country_name', 'DESC');

        if (!empty($get_cur)) {
            $cur_type = $get_cur[0]->cur_type;
        } else {
            $cur_type = 'inr';
        }
        $data['cur_type'] = $cur_type;

        $data['specification'] = Data_model::retrive('specification_master', '*', array(), 'sequence', 'ASC');

        $data['quatation_product'] = Data_model::retrive('quatation_product', '*', array(), 'name', 'ASC');
        $data['terms_condition'] = Data_model::retrive('terms_condition', '*', array(), 'term_id', 'ASC');
        $data['company'] = Data_model::retrive('company', '*', array(), 'name', 'ASC');
        $data['controller_name'] = $this->controller;
        $data['msgName'] = $this->msgName;
        return view($this->view . '/revise_view', $data);
    }

    public function revise_quatation_print($id, $type, $with_latterhead) {
        $data['utility'] = $this->utility;
        $data['msg'] = $this->msgName;
        $revise_quatation_id = $this->utility->decode($id);
        $type = $this->utility->decode($type);
        $data['with_latterhead'] = $this->utility->decode($with_latterhead);

        $data['result'] = Data_model::db_query("SELECT `revise_quatation`.* ,`product_master`.`product_name`,`customer_master`.`mobile` ,`customer_master`.`mobile_2` ,`customer_master`.`mobile_3`,`customer_master`.`email` ,`customer_master`.`email_2`,`customer_master`.`prefix`,`customer_master`.`name` as `customer_name`,`customer_master`.`company`,`customer_master`.`address` ,`customer_master`.`country_id`,`role_master`.`role_name` ,`employee`.`name` as `employee_name` ,`users`.`username` , `users`.`mobile` as `user_mobile` FROM revise_quatation
		LEFT JOIN `" . $this->foreign_table . "` ON `revise_quatation`.`" . $this->foreign_id . "` = `" . $this->foreign_table . "`.`" . $this->foreign_id . "`
		LEFT JOIN `product_master` ON `product_master`.`product_id` = `" . $this->foreign_table . "`.`product_id`
		LEFT JOIN `customer_master` ON `customer_master`.`customer_id` = `" . $this->foreign_table . "`.`customer_id`
		LEFT JOIN `users` ON `users`.`id` = `revise_quatation`.`added_by`
		LEFT JOIN `role_master` ON `role_master`.`role_id` = `users`.`role_id`
		LEFT JOIN `employee` ON `employee`.`emp_id` = `users`.`emp_id`
		where `revise_quatation`.`revise_id` = '$revise_quatation_id'");

        $get_cur = Data_model::retrive('country_master', '*', array('country_id' => $data['result'][0]->country_id), 'country_name', 'DESC');

        if (!empty($get_cur)) {
            $cur_type = $get_cur[0]->cur_type;
        } else {
            $cur_type = 'inr';
        }
        $data['cur_type'] = $cur_type;

        $data['specification'] = Data_model::retrive('specification_master', '*', array(), 'sequence', 'ASC');
        $data['letter_head'] = Data_model::retrive('letterhead_master', '*', array(), 'letterhead_name', 'ASC');
        $data['quatation_product'] = Data_model::retrive('quatation_product', '*', array(), 'name', 'ASC');
        $data['terms_condition'] = Data_model::retrive('terms_condition', '*', array(), 'term_id', 'ASC');
        $data['company'] = Data_model::retrive('company', '*', array(), 'name', 'ASC');
        $data['controller_name'] = $this->controller;
        $data['msgName'] = $this->msgName;

        if ($type == 'print') {
            $pdf = PDF::loadView($this->view . '/revise_print', $data);
            return $pdf->stream('Quotation ' . date('d-m-Y H:i:s'));
        } else {
            $pdf = PDF::loadView($this->view . '/revise_print', $data);
            return $pdf->download('Quotation_' . date('d-m-Y H:i:s') . '.pdf');
        }
    }

    public function delete($id) {
        $id = $this->utility->decode($id);
        $where = array($this->primary_id => $id);
        if (Data_model::remove($this->table, $where)) {
            $msg = array('success' => $this->msgName . ' Deleted Sucessfully');
        } else {
            $msg = array('error' => 'Some Problem ... Please Try Again');
        }
        return redirect($this->controller)->with($msg);
    }

    public function cancel_inquiry($inquiry_id, $q_master_id) {
        $data['utility'] = $this->utility;
        $inquiry_id = $this->utility->decode($inquiry_id);
        $q_master_id = $this->utility->decode($q_master_id);
        $data['inquiry_id'] = $inquiry_id;
        $data['q_master_id'] = $q_master_id;
        $data['action'] = "update";
        $data['controller_name'] = $this->controller;
        $data['msgName'] = $this->msgName;
        $data['inquiry_id'] = $inquiry_id;
        $data['q_master_id'] = $q_master_id;
        $data['result'] = DB::table($this->foreign_table)
                ->join('customer_master', 'customer_master.customer_id', '=', $this->foreign_table . '.customer_id')
                ->join('users', 'users.id', '=', $this->foreign_table . '.added_by')
                ->select($this->foreign_table . '.*', 'customer_master.*', 'users.username')
                ->where($this->foreign_id, "=", $inquiry_id)
                ->get();

        $data['category'] = Data_model::retrive('category_master', '*', array('delete_status' => 0), 'category_id', 'DESC');
        $data['product'] = Data_model::retrive('product_master', '*', array('delete_status' => 0), 'product_name', 'ASC');
        $data['quatation_product'] = Data_model::retrive('quatation_product', '*', array('delete_status' => 0), 'name', 'ASC');
        $data['country'] = Data_model::retrive('country_master', '*', array('delete_status' => 0), 'country_name', 'ASC');
        $data['state'] = Data_model::retrive('state_master', '*', array('delete_status' => 0), 'state_name', 'ASC');
        $data['city'] = Data_model::retrive('city_master', '*', array('delete_status' => 0), 'city_name', 'ASC');
        $data['employee'] = Data_model::retrive('employee', '*', array('delete_status' => 0), 'name', 'ASC');
        $data['customer'] = Data_model::retrive('customer_master', '*', array(), 'customer_id', 'DESC');
        $data['source'] = Data_model::retrive('source_master', '*', array('delete_status' => 0), 'source_name', 'ASC');
        $data['specification'] = Data_model::retrive('specification_master', '*', array('delete_status' => 0), 'specification_id', 'DESC');
        return view($this->view . '/inquiry_cancel', $data);
    }

    public function cancel_inquiry_view($inquiry_id) {
        $data['utility'] = $this->utility;
        $inquiry_id = $this->utility->decode($inquiry_id);
        $data['inquiry_id'] = $inquiry_id;
        $data['action'] = "update";
        $data['controller_name'] = $this->controller;
        $data['msgName'] = $this->msgName;
        $data['inquiry_id'] = $inquiry_id;
        $data['result'] = DB::table($this->foreign_table)
                ->join('customer_master', 'customer_master.customer_id', '=', $this->foreign_table . '.customer_id')
                ->join('users', 'users.id', '=', $this->foreign_table . '.added_by')
                ->select($this->foreign_table . '.*', 'customer_master.*', 'users.username')
                ->where($this->foreign_id, "=", $inquiry_id)
                ->get();

        $data['category'] = Data_model::retrive('category_master', '*', array('delete_status' => 0), 'category_id', 'DESC');
        $data['product'] = Data_model::retrive('product_master', '*', array('delete_status' => 0), 'product_name', 'ASC');
        $data['quatation_product'] = Data_model::retrive('quatation_product', '*', array('delete_status' => 0), 'name', 'ASC');
        $data['country'] = Data_model::retrive('country_master', '*', array('delete_status' => 0), 'country_name', 'ASC');
        $data['state'] = Data_model::retrive('state_master', '*', array('delete_status' => 0), 'state_name', 'ASC');
        $data['city'] = Data_model::retrive('city_master', '*', array('delete_status' => 0), 'city_name', 'ASC');
        $data['employee'] = Data_model::retrive('employee', '*', array('delete_status' => 0), 'name', 'ASC');
        $data['customer'] = Data_model::retrive('customer_master', '*', array(), 'customer_id', 'DESC');
        $data['source'] = Data_model::retrive('source_master', '*', array('delete_status' => 0), 'source_name', 'ASC');
        $data['specification'] = Data_model::retrive('specification_master', '*', array('delete_status' => 0), 'specification_id', 'DESC');
        return view($this->view . '/inquiry_cancel_view', $data);
    }

    public function cancel_inquiry_update(Request $request) {
        $date = date('Y-m-d');
        $inquiry_id = $request->input('inquiry_id');
        $q_master_id = $request->input('q_master_id');
        $cancel_reason = $request->input('cancel_reason');
        $user_id = Session::get('raj_user_id');
        Data_model::restore($this->foreign_table, array('cancel_reason' => $cancel_reason, 'delete_status' => $user_id, 'cancel_date' => $date), array($this->foreign_id => $inquiry_id));

        Data_model::restore('quatation_master', array('delete_status' => $user_id), array('q_master_id' => $q_master_id));
        $msg = array('success' => 'Inquiry Canceled Sucessfully');
        return redirect($this->controller)->with($msg);
    }

    public function inquiry_active($id) {
        $id = $this->utility->decode($id);
        $where = array($this->foreign_id => $id);
        if (Data_model::restore($this->foreign_table, array('delete_status' => 0), $where)) {
            Data_model::restore('quatation_master', array('delete_status' => 0), $where);
            $msg = array('success' => 'Inquiry Active Sucessfully');
        } else {
            $msg = array('error' => 'Some Problem ... Please Try Again');
        }
        return redirect($this->controller)->with($msg);
    }

    public function get_inq_pending(Request $request) {
        $zone_session = $this->zone_id;
        $masterTable1 = 'quatation_master';
        $parentTable1 = $this->foreign_table;
        $select1 = array($masterTable1 . '.*', $parentTable1 . '.*');
        $condition_11 = $masterTable1 . '.inquiry_id';
        $condition_21 = $parentTable1 . '.inquiry_id';
        $orderField1 = $masterTable1 . '.q_master_id';
        $orderType1 = 'DESC';
        $columns = array(
            0 => 'id',
            1 => 'inquiry_no',
            2 => 'inquiry_date',
            3 => 'users.username',
            4 => 'customer_master.name',
            5 => 'customer_master.mobile',
            6 => 'customer_master.email',
        );

        if ($this->role_id == '1') {

            $totalData = DB::table('quatation_master')
                    ->join('inquiry', 'inquiry.inquiry_id', '=', 'quatation_master.inquiry_id')
                    ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                    ->join('users', 'users.id', '=', 'inquiry.added_by')
                    ->select('quatation_master.*', 'inquiry.*', 'customer_master.name', 'customer_master.prefix', 'customer_master.mobile', 'customer_master.mobile_2', 'customer_master.mobile_3', 'customer_master.email', 'customer_master.email_2', 'users.username')
                    ->Where('inquiry.first_quatation_id', '=', 0)
                    ->Where('inquiry.delete_status', '=', 0)
                    ->Where('inquiry.remove_status', '=', 0)
                    ->count();
        } else {

            $empId = DB::table('users')->where('id', $this->user_id)->first();
            $extraWork = DB::table('emp_transfer')->where('transfer_emp_id', $empId->emp_id)->first();

            if ($extraWork) {

                $transferFromDate = $extraWork->transfer_from_date;
                $transferToDate = $extraWork->transfer_to_date;
                $transferZone = $extraWork->zone_id;

                $where1 = "find_in_set(" . $masterTable1 . ".zone_id,'$zone_session') AND $parentTable1.first_quatation_id = 0 AND `" . $parentTable1 . "`.`delete_status`= 0 AND `" . $parentTable1 . "`.`remove_status` = 0 OR ($parentTable1.inquiry_date >= '" . $transferFromDate . "' AND $parentTable1.inquiry_date <= '" . $transferToDate . "' AND find_in_set(" . $masterTable1 . ".zone_id,'$transferZone') )";
            } else {
                $where1 = "find_in_set(" . $masterTable1 . ".zone_id,'$zone_session') AND $parentTable1.first_quatation_id = 0 AND `" . $parentTable1 . "`.`delete_status`= 0 AND `" . $parentTable1 . "`.`remove_status` = 0";
            }

            $totalData = DB::table('quatation_master')
                    ->join('inquiry', 'inquiry.inquiry_id', '=', 'quatation_master.inquiry_id')
                    ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                    ->join('users', 'users.id', '=', 'inquiry.added_by')
                    ->select('quatation_master.*', 'inquiry.*', 'customer_master.name', 'customer_master.prefix', 'customer_master.mobile', 'customer_master.mobile_2', 'customer_master.mobile_3', 'customer_master.email', 'customer_master.email_2', 'users.username')
                    ->whereRaw($where1)
                    ->count();
        }

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');


        if (empty($request->input('search.value'))) {
            if ($this->role_id == '1') {
                $result = DB::table('quatation_master')
                        ->join('inquiry', 'inquiry.inquiry_id', '=', 'quatation_master.inquiry_id')
                        ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                        ->join('users', 'users.id', '=', 'inquiry.added_by')
                        ->select('quatation_master.*', 'inquiry.*', 'customer_master.name', 'customer_master.prefix', 'customer_master.mobile', 'customer_master.mobile_2', 'customer_master.mobile_3', 'customer_master.email', 'customer_master.email_2', 'users.username')
                        ->Where('inquiry.first_quatation_id', '=', 0)
                        ->Where('inquiry.delete_status', '=', 0)
                        ->Where('inquiry.remove_status', '=', 0)
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy('inquiry.inquiry_id', 'desc')
                        ->get();
            } else {

                $empId = DB::table('users')->where('id', $this->user_id)->first();
                $extraWork = DB::table('emp_transfer')->where('transfer_emp_id', $empId->emp_id)->first();

                if ($extraWork) {

                    $transferFromDate = $extraWork->transfer_from_date;
                    $transferToDate = $extraWork->transfer_to_date;
                    $transferZone = $extraWork->zone_id;

                    $where1 = "find_in_set(" . $masterTable1 . ".zone_id,'$zone_session') AND $parentTable1.first_quatation_id = 0 AND `" . $parentTable1 . "`.`delete_status`=0 AND `" . $parentTable1 . "`.`remove_status` = 0 OR ($parentTable1.inquiry_date >= '" . $transferFromDate . "' AND $parentTable1.inquiry_date <= '" . $transferToDate . "' AND find_in_set(" . $masterTable1 . ".zone_id,'$transferZone') )";
                } else {

                    $where1 = "find_in_set(" . $masterTable1 . ".zone_id,'$zone_session') AND $parentTable1.first_quatation_id = 0 AND `" . $parentTable1 . "`.`delete_status`=0 AND `" . $parentTable1 . "`.`remove_status` = 0";
                }

                $result = DB::table('quatation_master')
                        ->join('inquiry', 'inquiry.inquiry_id', '=', 'quatation_master.inquiry_id')
                        ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                        ->join('users', 'users.id', '=', 'inquiry.added_by')
                        ->select('quatation_master.*', 'inquiry.*', 'customer_master.name', 'customer_master.prefix', 'customer_master.mobile', 'customer_master.mobile_2', 'customer_master.mobile_3', 'customer_master.email', 'customer_master.email_2', 'users.username')
                        ->whereRaw($where1)
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy('inquiry.inquiry_id', 'desc')
                        ->orderBy($order, $dir)
                        ->get();
            }
        } else {
            $search = $request->input('search.value');

            if ($this->role_id == '1') {
                $result = DB::table('quatation_master')
                        ->join('inquiry', 'inquiry.inquiry_id', '=', 'quatation_master.inquiry_id')
                        ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                        ->join('users', 'users.id', '=', 'inquiry.added_by')
                        ->select('quatation_master.*', 'inquiry.*', 'customer_master.name', 'customer_master.prefix', 'customer_master.mobile', 'customer_master.mobile_2', 'customer_master.mobile_3', 'customer_master.email', 'customer_master.email_2', 'users.username')
                        ->Where('inquiry.first_quatation_id', '=', 0)
                        ->Where('inquiry.delete_status', '=', 0)
                        ->Where('inquiry.remove_status', '=', 0)
                        ->where(function($query) use($search) {
                            $query->orWhere('inquiry.inquiry_no', 'LIKE', "%{$search}%")
                            ->orWhere('inquiry.inquiry_date', 'LIKE', "%{$search}%")
                            ->orWhere('users.username', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.name', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.mobile', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.mobile_2', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.mobile_3', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.email', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.email_2', 'LIKE', "%{$search}%");
                        })
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->get();


                $totalFiltered = DB::table('quatation_master')
                        ->join('inquiry', 'inquiry.inquiry_id', '=', 'quatation_master.inquiry_id')
                        ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                        ->join('users', 'users.id', '=', 'inquiry.added_by')
                        ->select('quatation_master.*', 'inquiry.*', 'customer_master.name', 'customer_master.prefix', 'customer_master.mobile', 'customer_master.mobile_2', 'customer_master.mobile_3', 'customer_master.email', 'customer_master.email_2', 'users.username')
                        ->Where('inquiry.first_quatation_id', '=', 0)
                        ->Where('inquiry.delete_status', '=', 0)
                        ->Where('inquiry.remove_status', '=', 0)
                        ->where(function($query) use($search) {
                            $query->orWhere('inquiry.inquiry_no', 'LIKE', "%{$search}%")
                            ->orWhere('inquiry.inquiry_date', 'LIKE', "%{$search}%")
                            ->orWhere('users.username', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.name', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.mobile', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.mobile_2', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.mobile_3', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.email', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.email_2', 'LIKE', "%{$search}%");
                        })
                        ->orderBy($order, $dir)
                        ->count();
            } else {

                $empId = DB::table('users')->where('id', $this->user_id)->first();
                $extraWork = DB::table('emp_transfer')->where('transfer_emp_id', $empId->emp_id)->first();

                if ($extraWork) {

                    $transferFromDate = $extraWork->transfer_from_date;
                    $transferToDate = $extraWork->transfer_to_date;
                    $transferZone = $extraWork->zone_id;

                    $where1 = "find_in_set(" . $masterTable1 . ".zone_id,'$zone_session') AND $parentTable1.first_quatation_id = 0 AND `" . $parentTable1 . "`.`delete_status`=0 AND `" . $parentTable1 . "`.`remove_status` = 0 OR ($parentTable1.inquiry_date >= '" . $transferFromDate . "' AND $parentTable1.inquiry_date <= '" . $transferToDate . "' AND find_in_set(" . $masterTable1 . ".zone_id,'$transferZone') )";
                } else {

                    $where1 = "find_in_set(" . $masterTable1 . ".zone_id,'$zone_session') AND $parentTable1.first_quatation_id = 0 AND `" . $parentTable1 . "`.`delete_status`=0 AND `" . $parentTable1 . "`.`remove_status` = 0";
                }

                $result = DB::table('quatation_master')
                        ->join('inquiry', 'inquiry.inquiry_id', '=', 'quatation_master.inquiry_id')
                        ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                        ->join('users', 'users.id', '=', 'inquiry.added_by')
                        ->select('quatation_master.*', 'inquiry.*', 'customer_master.name', 'customer_master.prefix', 'customer_master.mobile', 'customer_master.mobile_2', 'customer_master.mobile_3', 'customer_master.email', 'customer_master.email_2', 'users.username')
                        ->whereRaw($where1)
                        ->where(function($query) use($search) {
                            $query->orWhere('inquiry.inquiry_no', 'LIKE', "%{$search}%")
                            ->orWhere('inquiry.inquiry_date', 'LIKE', "%{$search}%")
                            ->orWhere('users.username', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.name', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.mobile', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.mobile_2', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.mobile_3', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.email', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.email_2', 'LIKE', "%{$search}%");
                        })
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->get();


                $totalFiltered = DB::table('quatation_master')
                        ->join('inquiry', 'inquiry.inquiry_id', '=', 'quatation_master.inquiry_id')
                        ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                        ->join('users', 'users.id', '=', 'inquiry.added_by')
                        ->select('quatation_master.*', 'inquiry.*', 'customer_master.name', 'customer_master.prefix', 'customer_master.mobile', 'customer_master.mobile_2', 'customer_master.mobile_3', 'customer_master.email', 'customer_master.email_2', 'users.username')
                        ->whereRaw($where1)
                        ->where(function($query) use($search) {
                            $query->orWhere('inquiry.inquiry_no', 'LIKE', "%{$search}%")
                            ->orWhere('inquiry.inquiry_date', 'LIKE', "%{$search}%")
                            ->orWhere('users.username', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.name', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.mobile', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.mobile_2', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.mobile_3', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.email', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.email_2', 'LIKE', "%{$search}%");
                        })
                        ->orderBy($order, $dir)
                        ->count();
            }
        }


        $data = array();
        if (!empty($result)) {
            $t = $start + 1;
            foreach ($result as $res) {
                $customer_mobile = array();
                if ($res->mobile != '')
                    $customer_mobile[] = $res->mobile;
                if ($res->mobile_2 != '')
                    $customer_mobile[] = $res->mobile_2;
                if ($res->mobile_3 != '')
                    $customer_mobile[] = $res->mobile_3;


                $customer_email = array();
                if ($res->email != '')
                    $customer_email[] = $res->email;
                if ($res->email_2 != '')
                    $customer_email[] = $res->email_2;


                $minimum_days = Data_model::retrive('minimum_days', '*', array('type' => 'quatation'), 'id', 'DESC');
                $final_date = date('Y-m-d', strtotime($res->inquiry_date . ' +' . $minimum_days[0]->days . ' day'));
                if (date("Y-m-d") <= $final_date) {
                    $color = '';
                } else {
                    $color = '#ff0000';
                }

                if ($this->role_id == 1) {
                    $action = "<a  class='btn bg-purple btn-flat btn-sm' href='" . $this->controller . "/generate_quatation/" . $this->utility->encode($res->inquiry_id) . "/" . $this->utility->encode($res->q_master_id) . "'>Generate Quotation</a>&nbsp;";

                    $action .= "<a class='btn bg-maroon btn-flat btn-sm' href='" . $this->controller . "/cancel_inquiry/" . $this->utility->encode($res->inquiry_id) . "/" . $this->utility->encode($res->q_master_id) . "'>Cancel Inquery</a>&nbsp;";
                } else {
                    $action = '';
                    $permission = Data_model::get_permission($this->module_name);
                    if ($permission[0]->add == 1) {
                        $action .= "<a  class='btn bg-purple btn-flat btn-sm' href='" . $this->controller . "/generate_quatation/" . $this->utility->encode($res->inquiry_id) . "/" . $this->utility->encode($res->q_master_id) . "'>Generate Quotation</a>&nbsp;";
                    }
                    $action .= "<a class='btn bg-maroon btn-flat btn-sm' href='" . $this->controller . "/cancel_inquiry/" . $this->utility->encode($res->inquiry_id) . "/" . $this->utility->encode($res->q_master_id) . "'>Cancel Inquery</a>&nbsp;";
                }

                $action .= "<a id='quotation_remark' data-toggle='modal'  data-target='#remark_model' class='btn bg-green btn-flat btn-sm' ><i class='glyphicon glyphicon-upload icon-white'></i> Remark</a>&nbsp;";

                $action .= "<a id='send_reminder_mail' data-toggle='modal'  data-target='#mail_model' class='btn btn-danger btn-flat btn-sm' ><i class='glyphicon glyphicon-upload icon-white'></i> Reminder Mail</a>&nbsp;";

                $action .= "<a id='send_address' data-toggle='modal' data-target='#send_address_model' class='btn bg-yellow btn-flat btn-sm' ><i class='glyphicon glyphicon-upload icon-white'></i> Send Address</a>&nbsp;";


                $nestedData['id'] = $t;
                $nestedData['inquiry_no'] = $res->inquiry_no;
                $nestedData['inquiry_id'] = $res->inquiry_id;
                $nestedData['inquiry_color'] = $color;
                $nestedData['inquiry_date'] = date('d-m-Y', strtotime($res->inquiry_date));
                $nestedData['inquiry_person'] = $res->username;
                $nestedData['name'] = $res->name;
                $nestedData['mobile'] = implode('<br />', $customer_mobile);
                $nestedData['email'] = implode('<br />', $customer_email);

                /* action start */
                $nestedData['action'] = $action;

                $data[] = $nestedData;
                $t++;
            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function get_inq_quotation(Request $request) {
        $zone_session = $this->zone_id;
        $masterTable = $this->table;
        $parentTable = $this->foreign_table;
        $select = array($masterTable . '.*', $masterTable . '.added_by as add_by', $parentTable . '.*');
        $condition_1 = $masterTable . '.inquiry_id';
        $condition_2 = $parentTable . '.inquiry_id';
        $orderField = $masterTable . '.' . $this->primary_id;
        $orderType = 'DESC';
        $columns = array(
            0 => 'id',
            1 => 'inquiry_no',
            2 => 'inquiry_date',
            3 => 'quatation_no',
            4 => 'quatation_date',
            5 => 'customer_master.name',
            6 => 'users.username',
        );

        if ($this->role_id == '1') {
            $totalData = DB::table('quatation')
                    ->join('inquiry', 'inquiry.inquiry_id', '=', 'quatation.inquiry_id')
                    ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                    ->join('users', 'users.id', '=', 'quatation.added_by')
                    ->select('quatation.quatation_id', 'quatation.quatation_no', 'quatation.quatation_date', 'quatation.quatation_time', 'quatation.send_status', 'inquiry.inquiry_no', 'inquiry.inquiry_id', 'inquiry.inquiry_date', 'inquiry.inquiry_time', 'customer_master.name', 'customer_master.prefix', 'users.username')
                    ->count();
        } else {
            $empId = DB::table('users')->where('id', $this->user_id)->first();
            $extraWork = DB::table('emp_transfer')->where('transfer_emp_id', $empId->emp_id)->first();

            if ($extraWork) {

                $transferFromDate = $extraWork->transfer_from_date;
                $transferToDate = $extraWork->transfer_to_date;
                $transferZone = $extraWork->zone_id;

                $where = "find_in_set(" . $masterTable . ".zone_id,'$zone_session') OR find_in_set(" . $masterTable . ".zone_id,'$transferZone')";
            } else {

                $where = "find_in_set(" . $masterTable . ".zone_id,'$zone_session')";
            }
            $totalData = DB::table('quatation')
                    ->join('inquiry', 'inquiry.inquiry_id', '=', 'quatation.inquiry_id')
                    ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                    ->join('users', 'users.id', '=', 'quatation.added_by')
                    ->select('quatation.quatation_id', 'quatation.quatation_no', 'quatation.quatation_date', 'quatation.quatation_time', 'quatation.send_status', 'inquiry.inquiry_no', 'inquiry.inquiry_id', 'inquiry.inquiry_date', 'inquiry.inquiry_time', 'customer_master.name', 'customer_master.prefix', 'users.username')
                    ->whereRaw($where)
                    ->count();
        }
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            if ($this->role_id == '1') {
                $result = DB::table('quatation')
                        ->join('inquiry', 'inquiry.inquiry_id', '=', 'quatation.inquiry_id')
                        ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                        ->join('users', 'users.id', '=', 'quatation.added_by')
                        //->join('revise_quatation', 'revise_quatation.quatation_id', '=', 'quatation.quatation_id')
                        ->select('quatation.quatation_id', 'quatation.quatation_no', 'quatation.quatation_date', 'quatation.quatation_time', 'quatation.send_status', 'inquiry.inquiry_no', 'inquiry.inquiry_id', 'inquiry.inquiry_date', 'inquiry.inquiry_time', 'customer_master.name', 'customer_master.prefix', 'users.username')
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy('quatation.quatation_id', 'desc')
                        ->get();
            } else {
                $empId = DB::table('users')->where('id', $this->user_id)->first();
                $extraWork = DB::table('emp_transfer')->where('transfer_emp_id', $empId->emp_id)->first();
                if ($extraWork) {
                    $transferFromDate = $extraWork->transfer_from_date;
                    $transferToDate = $extraWork->transfer_to_date;
                    $transferZone = $extraWork->zone_id;
                    $where = "find_in_set(" . $masterTable . ".zone_id,'$zone_session') OR find_in_set(" . $masterTable . ".zone_id,'$transferZone')";
                } else {
                    $where = "find_in_set(" . $masterTable . ".zone_id,'$zone_session')";
                }
                $result = DB::table('quatation')
                        ->join('inquiry', 'inquiry.inquiry_id', '=', 'quatation.inquiry_id')
                        ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                        ->join('users', 'users.id', '=', 'quatation.added_by')
                        ->join('revise_quatation', 'revise_quatation.quatation_id', '=', 'quatation.quatation_id')
                        ->select('revise_quatation.revise_id','quatation.quatation_id', 'quatation.quatation_no', 'quatation.quatation_date', 'quatation.quatation_time', 'quatation.send_status', 'inquiry.inquiry_no', 'inquiry.inquiry_id', 'inquiry.inquiry_date', 'inquiry.inquiry_time', 'customer_master.name', 'customer_master.prefix', 'users.username')
                        ->whereRaw($where)
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy('quatation.quatation_id', 'desc')
                        ->get();
            }
        } else {
            $search = $request->input('search.value');
            if ($this->role_id == '1') {
                $result = DB::table('quatation')
                        ->join('inquiry', 'inquiry.inquiry_id', '=', 'quatation.inquiry_id')
                        ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                        ->join('users', 'users.id', '=', 'quatation.added_by')
                        ->join('revise_quatation', 'revise_quatation.quatation_id', '=', 'quatation.quatation_id')
                        ->select('revise_quatation.revise_id','quatation.quatation_id', 'quatation.quatation_no', 'quatation.quatation_date', 'quatation.quatation_time', 'quatation.send_status', 'inquiry.inquiry_no', 'inquiry.inquiry_id', 'inquiry.inquiry_date', 'inquiry.inquiry_time', 'customer_master.name', 'customer_master.prefix', 'users.username')
                        ->where(function($query) use($search) {
                            $query->orWhere('inquiry.inquiry_no', 'LIKE', "%{$search}%")
                            ->orWhere('inquiry.inquiry_date', 'LIKE', "%{$search}%")
                            ->orWhere('quatation.quatation_no', 'LIKE', "%{$search}%")
                            ->orWhere('quatation.quatation_date', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.name', 'LIKE', "%{$search}%")
                            ->orWhere('users.username', 'LIKE', "%{$search}%");
                        })
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->get();

                $totalFiltered = DB::table('quatation')
                        ->join('inquiry', 'inquiry.inquiry_id', '=', 'quatation.inquiry_id')
                        ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                        ->join('users', 'users.id', '=', 'quatation.added_by')
                        ->select('quatation.quatation_id', 'quatation.quatation_no', 'quatation.quatation_date', 'quatation.quatation_time', 'quatation.send_status', 'inquiry.inquiry_no', 'inquiry.inquiry_id', 'inquiry.inquiry_date', 'inquiry.inquiry_time', 'customer_master.name', 'customer_master.prefix', 'users.username')
                        ->where(function($query) use($search) {
                            $query->orWhere('inquiry.inquiry_no', 'LIKE', "%{$search}%")
                            ->orWhere('inquiry.inquiry_date', 'LIKE', "%{$search}%")
                            ->orWhere('quatation.quatation_no', 'LIKE', "%{$search}%")
                            ->orWhere('quatation.quatation_date', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.name', 'LIKE', "%{$search}%")
                            ->orWhere('users.username', 'LIKE', "%{$search}%");
                        })
                        ->orderBy($order, $dir)
                        ->count();
            } else {
                $empId = DB::table('users')->where('id', $this->user_id)->first();
                $extraWork = DB::table('emp_transfer')->where('transfer_emp_id', $empId->emp_id)->first();
                if ($extraWork) {
                    $transferFromDate = $extraWork->transfer_from_date;
                    $transferToDate = $extraWork->transfer_to_date;
                    $transferZone = $extraWork->zone_id;
                    $where = "find_in_set(" . $masterTable . ".zone_id,'$zone_session') OR find_in_set(" . $masterTable . ".zone_id,'$transferZone')";
                } else {
                    $where = "find_in_set(" . $masterTable . ".zone_id,'$zone_session')";
                }
                $result = DB::table('quatation')
                        ->join('inquiry', 'inquiry.inquiry_id', '=', 'quatation.inquiry_id')
                        ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                        ->join('users', 'users.id', '=', 'quatation.added_by')
                        ->join('revise_quatation', 'revise_quatation.quatation_id', '=', 'quatation.quatation_id')
                        ->select('revise_quatation.revise_id','quatation.quatation_id', 'quatation.quatation_no', 'quatation.quatation_date', 'quatation.quatation_time', 'quatation.send_status', 'inquiry.inquiry_no', 'inquiry.inquiry_id', 'inquiry.inquiry_date', 'inquiry.inquiry_time', 'customer_master.name', 'customer_master.prefix', 'users.username')
                        ->whereRaw($where)
                        ->where(function($query) use($search) {
                            $query->orWhere('inquiry.inquiry_no', 'LIKE', "%{$search}%")
                            ->orWhere('inquiry.inquiry_date', 'LIKE', "%{$search}%")
                            ->orWhere('quatation.quatation_no', 'LIKE', "%{$search}%")
                            ->orWhere('quatation.quatation_date', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.name', 'LIKE', "%{$search}%")
                            ->orWhere('users.username', 'LIKE', "%{$search}%");
                        })
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->get();
                $totalFiltered = DB::table('quatation')
                        ->join('inquiry', 'inquiry.inquiry_id', '=', 'quatation.inquiry_id')
                        ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                        ->join('users', 'users.id', '=', 'quatation.added_by')
                        ->select('quatation.quatation_id', 'quatation.quatation_no', 'quatation.quatation_date', 'quatation.quatation_time', 'quatation.send_status', 'inquiry.inquiry_no', 'inquiry.inquiry_id', 'inquiry.inquiry_date', 'inquiry.inquiry_time', 'customer_master.name', 'customer_master.prefix', 'users.username')
                        ->whereRaw($where)
                        ->where(function($query) use($search) {
                            $query->orWhere('inquiry.inquiry_no', 'LIKE', "%{$search}%")
                            ->orWhere('inquiry.inquiry_date', 'LIKE', "%{$search}%")
                            ->orWhere('quatation.quatation_no', 'LIKE', "%{$search}%")
                            ->orWhere('quatation.quatation_date', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.name', 'LIKE', "%{$search}%")
                            ->orWhere('users.username', 'LIKE', "%{$search}%");
                        })
                        ->orderBy($order, $dir)
                        ->count();
            }
        }
        $data = array();
        if (!empty($result)) {
            $t = $start + 1;
            foreach ($result as $res) {
                $total_revise = DB::select('SELECT count(`revise_id`) as `total_revise` FROM `revise_quatation` WHERE `quatation_id` = ' . $res->quatation_id);

                $action = "<a target='_blank' title='View' class='btn bg-olive btn-flat btn-sm' href='" . $this->controller . "/view/" . $this->utility->encode($res->quatation_id) . "'><i class='glyphicon glyphicon-eye-open icon-white'></i> View Quotation</a>&nbsp;";

                if ($total_revise[0]->total_revise > 0) {
                    $action .= "<a target='_blank' title='View' class='btn bg-maroon btn-flat btn-sm' href='" . $this->controller . "/revise_quatation_index/" . $this->utility->encode($res->quatation_id) . "'><i class='glyphicon glyphicon-eye-open icon-white'></i> View All Revise</a>&nbsp;";
                }

                $action .= "<a class='btn bg-purple btn-flat btn-sm' href='" . $this->controller . "/revise_quatation/" . $this->utility->encode($res->inquiry_id) . "/" . $this->utility->encode($res->quatation_id) . "'>Revise</a>&nbsp;";

                $action .= "<a target='_blank' class='btn btn-danger btn-flat btn-sm' href='" . $this->controller . "/print_quatation/" . $this->utility->encode($res->quatation_id) . "/" . $this->utility->encode('print') . "/" . $this->utility->encode('yes') . "'><i class='glyphicon glyphicon-print icon-white'></i> Letterhead  Print</a>&nbsp;";

                $action .= "<a target='_blank' class='btn btn-danger btn-flat btn-sm' href='" . $this->controller . "/print_quatation/" . $this->utility->encode($res->quatation_id) . "/" . $this->utility->encode('print') . "/" . $this->utility->encode('no') . "'><i class='glyphicon glyphicon-print icon-white'></i> W/O Letterhead Print</a>&nbsp;";

                $action .= "<a class='btn btn-info btn-flat btn-sm' href='" . $this->controller . "/print_quatation/" . $this->utility->encode($res->quatation_id) . "/" . $this->utility->encode('download') . "/" . $this->utility->encode('yes') . "'><i class='glyphicon glyphicon-download icon-white'></i> Letterhead Download</a>&nbsp;";

                $action .= "<a class='btn btn-info btn-flat btn-sm' href='" . $this->controller . "/print_quatation/" . $this->utility->encode($res->quatation_id) . "/" . $this->utility->encode('download') . "/" . $this->utility->encode('no') . "'><i class='glyphicon glyphicon-download icon-white'></i> W/O Letterhead Download</a>&nbsp;";

                $action .= "<a id='send_quotation' data-toggle='modal'  data-target='#send_model' class='btn bg-purple btn-flat btn-sm' ><i class='glyphicon glyphicon-upload icon-white'></i> Send Quotation</a>&nbsp;";

                $action .= "<a id='send_address_quot' data-toggle='modal'  data-target='#send_address_model' class='btn bg-yellow btn-flat btn-sm' ><i class='glyphicon glyphicon-upload icon-white'></i> Send Address</a>&nbsp;";

                $action .= "<a target='_blank' class='btn btn-primary btn-flat btn-sm' href='" . $this->controller . "/simple_quatation/" . $this->utility->encode($res->quatation_id) . "'><i class='glyphicon glyphicon-print icon-white'></i> Simple Quotation</a>&nbsp;";

                $action .= "<a target='_blank' class='btn bg-maroon btn-flat btn-sm' href='" . $this->controller . "/gst_quatation/" . $this->utility->encode($res->quatation_id) . "'><i class='glyphicon glyphicon-print icon-white'></i> With GST</a>&nbsp;";

                $action .= "<a id='send_sms_quot' data-toggle='modal'  data-target='#send_sms_model' class='btn bg-green btn-flat btn-sm' ><i class='glyphicon glyphicon-envelope icon-white'></i> Send SMS</a>&nbsp;";
                
                //$action .= "<a class='btn bg-maroon btn-flat btn-sm quotation-delete' href='" . $this->controller . "/delete-quotation/" . $this->utility->encode($res->revise_id) . "'><i class='glyphicon glyphicon-trash icon-white'></i> Delete</a>&nbsp;";


                $nestedData['id'] = $t;
                $nestedData['inquiry_no'] = $res->inquiry_no;
                $nestedData['inquiry_id'] = $res->inquiry_id;
                $nestedData['inquiry_date'] = date("d-m-Y h:i a", strtotime($res->inquiry_date . " " . $res->inquiry_time));
                $nestedData['quotation_no'] = $res->quatation_no;
                $nestedData['quotation_date'] = date("d-m-Y h:i a", strtotime($res->quatation_date . " " . $res->quatation_time));
                $nestedData['customer_name'] = $res->name;
                $nestedData['quotation_by'] = $res->username;
                $nestedData['no_of_send'] = $res->send_status;
                $nestedData['no_of_revise'] = $total_revise[0]->total_revise . ' Revise';

                /* action start */
                $nestedData['action'] = $action;

                $data[] = $nestedData;
                $t++;
            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function get_inq_revise(Request $request) {
        $zone_session = $this->zone_id;
        $masterTable3 = 'revise_quatation';
        $parentTable3 = $this->table;
        $select3 = array($masterTable3 . '.*', $parentTable3 . '.*');
        $condition_13 = $masterTable3 . '.' . $this->primary_id;
        $condition_23 = $parentTable3 . '.' . $this->primary_id;
        $orderField3 = $masterTable3 . '.revise_id';
        $orderType3 = 'DESC';
        $columns = array(
            0 => 'revise_id',
            1 => 'quatation_no',
            2 => 'revise_quatation_no',
            3 => 'revise_date',
        );

        if ($this->role_id == '1') {
            $totalData = DB::table('revise_quatation')
                    ->join('quatation', 'quatation.quatation_id', '=', 'revise_quatation.quatation_id')
                    ->select('revise_quatation.revise_id', 'revise_quatation.revise_quatation_no', 'revise_quatation.revise_date', 'quatation.quatation_no', 'quatation.inquiry_id','quatation.quatation_id')
                    ->orderBy('revise_quatation.revise_id', 'desc')
                    ->count();
        } else {

            $empId = DB::table('users')->where('id', $this->user_id)->first();
            $extraWork = DB::table('emp_transfer')->where('transfer_emp_id', $empId->emp_id)->first();

            if ($extraWork) {
                $transferFromDate = $extraWork->transfer_from_date;
                $transferToDate = $extraWork->transfer_to_date;
                $transferZone = $extraWork->zone_id;
                $where = "find_in_set(" . $parentTable3 . ".zone_id,'$zone_session') OR find_in_set(" . $parentTable3 . ".zone_id,'$transferZone')";
            } else {
                $where = "find_in_set(" . $parentTable3 . ".zone_id,'$zone_session')";
            }

            $totalData = DB::table('revise_quatation')
                    ->join('quatation', 'quatation.quatation_id', '=', 'revise_quatation.quatation_id')
                    ->select('revise_quatation.revise_id', 'revise_quatation.revise_quatation_no', 'revise_quatation.revise_date', 'quatation.quatation_no', 'quatation.inquiry_id','quatation.quatation_id')
                    ->whereRaw($where)
                    ->orderBy('revise_quatation.revise_id', 'desc')
                    ->count();
        }

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');


        if (empty($request->input('search.value'))) {
            if ($this->role_id == '1') {
                $result = DB::table('revise_quatation')
                        ->join('quatation', 'quatation.quatation_id', '=', 'revise_quatation.quatation_id')
                        ->select('revise_quatation.revise_id', 'revise_quatation.revise_quatation_no', 'revise_quatation.revise_date', 'quatation.quatation_no', 'quatation.inquiry_id','quatation.quatation_id')
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy('revise_quatation.revise_id', 'desc')
                        ->get();
            } else {

                $empId = DB::table('users')->where('id', $this->user_id)->first();
                $extraWork = DB::table('emp_transfer')->where('transfer_emp_id', $empId->emp_id)->first();

                if ($extraWork) {
                    $transferFromDate = $extraWork->transfer_from_date;
                    $transferToDate = $extraWork->transfer_to_date;
                    $transferZone = $extraWork->zone_id;
                    $where = "find_in_set(" . $parentTable3 . ".zone_id,'$zone_session') OR find_in_set(" . $parentTable3 . ".zone_id,'$transferZone')";
                } else {
                    $where = "find_in_set(" . $parentTable3 . ".zone_id,'$zone_session')";
                }

                $result = DB::table('revise_quatation')
                        ->join('quatation', 'quatation.quatation_id', '=', 'revise_quatation.quatation_id')
                        ->select('revise_quatation.revise_id', 'revise_quatation.revise_quatation_no', 'revise_quatation.revise_date', 'quatation.quatation_no', 'quatation.inquiry_id','quatation.quatation_id')
                        ->whereRaw($where)
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy('revise_quatation.revise_id', 'desc')
                        ->get();
            }
        } else {
            $search = $request->input('search.value');

            if ($this->role_id == '1') {

                $result = DB::table('revise_quatation')
                        ->join('quatation', 'quatation.quatation_id', '=', 'revise_quatation.quatation_id')
                        ->select('revise_quatation.revise_id', 'revise_quatation.revise_quatation_no', 'revise_quatation.revise_date', 'quatation.quatation_no', 'quatation.inquiry_id','quatation.quatation_id')
                        ->where(function($query) use($search) {
                            $query->orWhere('quatation.quatation_no', 'LIKE', "%{$search}%")
                            ->orWhere('revise_quatation.revise_quatation_no', 'LIKE', "%{$search}%")
                            ->orWhere('revise_quatation.revise_date', 'LIKE', "%{$search}%");
                        })
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->get();


                $totalFiltered = DB::table('revise_quatation')
                        ->join('quatation', 'quatation.quatation_id', '=', 'revise_quatation.quatation_id')
                        ->select('revise_quatation.revise_id', 'revise_quatation.revise_quatation_no', 'revise_quatation.revise_date', 'quatation.quatation_no', 'quatation.inquiry_id')
                        ->where(function($query) use($search) {
                            $query->orWhere('quatation.quatation_no', 'LIKE', "%{$search}%")
                            ->orWhere('revise_quatation.revise_quatation_no', 'LIKE', "%{$search}%")
                            ->orWhere('revise_quatation.revise_date', 'LIKE', "%{$search}%");
                        })
                        ->count();
            } else {

                $empId = DB::table('users')->where('id', $this->user_id)->first();
                $extraWork = DB::table('emp_transfer')->where('transfer_emp_id', $empId->emp_id)->first();

                if ($extraWork) {
                    $transferFromDate = $extraWork->transfer_from_date;
                    $transferToDate = $extraWork->transfer_to_date;
                    $transferZone = $extraWork->zone_id;
                    $where = "find_in_set(" . $parentTable3 . ".zone_id,'$zone_session') OR find_in_set(" . $parentTable3 . ".zone_id,'$transferZone')";
                } else {
                    $where = "find_in_set(" . $parentTable3 . ".zone_id,'$zone_session')";
                }

                $result = DB::table('revise_quatation')
                        ->join('quatation', 'quatation.quatation_id', '=', 'revise_quatation.quatation_id')
                        ->select('revise_quatation.revise_id', 'revise_quatation.revise_quatation_no', 'revise_quatation.revise_date', 'quatation.quatation_no', 'quatation.inquiry_id','quatation.quatation_id')
                        ->whereRaw($where)
                        ->where(function($query) use($search) {
                            $query->orWhere('quatation.quatation_no', 'LIKE', "%{$search}%")
                            ->orWhere('revise_quatation.revise_quatation_no', 'LIKE', "%{$search}%")
                            ->orWhere('revise_quatation.revise_date', 'LIKE', "%{$search}%");
                        })
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->get();


                $totalFiltered = DB::table('revise_quatation')
                        ->join('quatation', 'quatation.quatation_id', '=', 'revise_quatation.quatation_id')
                        ->select('revise_quatation.revise_id', 'revise_quatation.revise_quatation_no', 'revise_quatation.revise_date', 'quatation.quatation_no', 'quatation.inquiry_id')
                        ->whereRaw($where)
                        ->where(function($query) use($search) {
                            $query->orWhere('quatation.quatation_no', 'LIKE', "%{$search}%")
                            ->orWhere('revise_quatation.revise_quatation_no', 'LIKE', "%{$search}%")
                            ->orWhere('revise_quatation.revise_date', 'LIKE', "%{$search}%");
                        })
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->count();
            }
        }


        $data = array();
        if (!empty($result)) {
            $t = $start + 1;
            foreach ($result as $res) {
                $action = "<a target='_blank' title='View' class='btn bg-olive btn-flat btn-sm' href='" . $this->controller . "/revise_quatation_view/" . $this->utility->encode($res->revise_id) . "'><i class='glyphicon glyphicon-eye-open icon-white'></i> View</a>&nbsp;";
                
                $action .= "<a class='btn bg-purple btn-flat btn-sm' href='" . $this->controller . "/revise_quatation/" . $this->utility->encode($res->inquiry_id) . "/" . $this->utility->encode($res->quatation_id) . "'>Revise</a>&nbsp;";

                $action .= "<a target='_blank' class='btn btn-danger btn-flat btn-sm' href='" . $this->controller . "/revise_quatation_print/" . $this->utility->encode($res->revise_id) . "/" . $this->utility->encode('print') . "/" . $this->utility->encode('yes') . "'><i class='glyphicon glyphicon-print icon-white'></i> Latterhead Print</a>&nbsp;";

                $action .= "<a target='_blank' class='btn btn-danger btn-flat btn-sm' href='" . $this->controller . "/revise_quatation_print/" . $this->utility->encode($res->revise_id) . "/" . $this->utility->encode('print') . "/" . $this->utility->encode('no') . "'><i class='glyphicon glyphicon-print icon-white'></i> W/O Letterhead Print</a>&nbsp;";

                $action .= "<a class='btn btn-info btn-flat btn-sm' href='" . $this->controller . "/revise_quatation_print/" . $this->utility->encode($res->revise_id) . "/" . $this->utility->encode('download') . "/" . $this->utility->encode('yes') . "'><i class='glyphicon glyphicon-download icon-white'></i> Letterhead Download</a>&nbsp;";

                $action .= "<a class='btn btn-info btn-flat btn-sm' href='" . $this->controller . "/revise_quatation_print/" . $this->utility->encode($res->revise_id) . "/" . $this->utility->encode('download') . "/" . $this->utility->encode('no') . "'><i class='glyphicon glyphicon-download icon-white'></i> W/O Letterhead Download</a>&nbsp;";

                $action .= "<a target='_blank' class='btn btn-primary btn-flat btn-sm' href='" . $this->controller . "/revice_simple_quatation/" . $this->utility->encode($res->revise_id) . "'><i class='glyphicon glyphicon-print icon-white'></i> Simple Quotation</a>&nbsp;";

                $action .= "<a target='_blank' class='btn bg-maroon btn-flat btn-sm' href='" . $this->controller . "/revice_gst_quatation/" . $this->utility->encode($res->revise_id) . "'><i class='glyphicon glyphicon-print icon-white'></i> GST Quotation</a>&nbsp;";

                $action .= "<a id='send_revise_quotation' data-toggle='modal'  data-target='#send_revise_model' class='btn bg-purple btn-flat btn-sm' ><i class='glyphicon glyphicon-upload icon-white'></i> Send Quotation</a>&nbsp;";

                $action .= "<a id='send_sms_res' data-toggle='modal'  data-target='#send_sms_model' class='btn bg-green btn-flat btn-sm' ><i class='glyphicon glyphicon-envelope icon-white'></i> Send SMS</a>&nbsp;";

                if ($this->role_id == 1) {
                    $action .= "<a class='btn bg-maroon btn-flat btn-sm quotation-delete' href='" . $this->controller . "/delete-quotation/" . $this->utility->encode($res->revise_id) . "'><i class='glyphicon glyphicon-trash icon-white'></i> Delete</a>&nbsp;";
                } else {
                    $permission = Data_model::get_permission($this->module_name);
                    if ($permission[0]->delete == 1) {
                        $action .= "<a class='btn bg-maroon btn-flat btn-sm quotation-delete' href='" . $this->controller . "/delete-quotation/" . $this->utility->encode($res->revise_id) . "'><i class='glyphicon glyphicon-trash icon-white'></i> Delete</a>&nbsp;";
                    }
                }




                $nestedData['id'] = $t;
                $nestedData['quotation_no'] = $res->quatation_no;
                $nestedData['revise_id'] = $res->revise_id;
                $nestedData['inquiry_id'] = $res->inquiry_id;
                $nestedData['revise_quotation_no'] = $res->revise_quatation_no;
                $nestedData['revise_quot_date'] = date('d-m-Y', strtotime($res->revise_date));

                /* action start */
                $nestedData['action'] = $action;

                $data[] = $nestedData;
                $t++;
            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function get_inq_cancel(Request $request) {
        $zone_session = $this->zone_id;
        $parentTable1 = $this->foreign_table;
        $masterTable1 = 'quatation_master';
        $masterTable2 = 'quatation_master';
        $parentTable2 = $this->foreign_table;
        $select2 = array($masterTable1 . '.*', $parentTable1 . '.*');
        $condition_22 = $masterTable1 . '.inquiry_id';
        $condition_2 = $parentTable1 . '.inquiry_id';
        $orderField2 = $masterTable1 . '.q_master_id';
        $orderType2 = 'DESC';
        $columns = array(
            0 => 'quatation_master.q_master_id',
            1 => 'inquiry.inquiry_id',
            2 => 'inquiry.inquiry_date',
            3 => 'users.username',
            4 => 'customer_master.name',
            5 => 'customer_master.mobile',
            6 => 'customer_master.email'
        );

        if ($this->role_id == '1') {
            $where2 = $parentTable2 . ".first_quatation_id = '0' AND " . $parentTable2 . ".delete_status != '0'";
            $totalData = DB::table('quatation_master')
                    ->join('inquiry', 'inquiry.inquiry_id', '=', 'quatation_master.inquiry_id')
                    ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                    ->join('users as u1', 'u1.id', '=', 'inquiry.added_by')
                    ->join('users as u2', 'u2.id', '=', 'inquiry.delete_status')
                    ->whereRaw($where2)
                    ->select('inquiry.inquiry_id', 'inquiry.inquiry_no', 'inquiry.inquiry_date', 'inquiry.inquiry_time', 'inquiry.cancel_date', 'customer_master.prefix', 'customer_master.name', 'customer_master.mobile', 'customer_master.mobile_2', 'customer_master.mobile_3', 'customer_master.email', 'customer_master.email_2', 'u1.username as inq_person', 'u2.username as delete_person')
                    ->orderBy('inquiry.inquiry_id', 'desc')
                    ->count();
        } else {

            $empId = DB::table('users')->where('id', $this->user_id)->first();
            $extraWork = DB::table('emp_transfer')->where('transfer_emp_id', $empId->emp_id)->first();

            if ($extraWork) {
                $transferFromDate = $extraWork->transfer_from_date;
                $transferToDate = $extraWork->transfer_to_date;
                $transferZone = $extraWork->zone_id;

                $where1 = "find_in_set(" . $masterTable2 . ".zone_id,'$zone_session') AND $parentTable1.first_quatation_id = 0 AND " . $parentTable2 . ".delete_status != '0' OR find_in_set(" . $masterTable2 . ".zone_id,'$transferZone')";
            } else {
                $where1 = "find_in_set(" . $masterTable2 . ".zone_id,'$zone_session') AND $parentTable1.first_quatation_id = 0 AND " . $parentTable2 . ".delete_status != '0' ";
            }

            $totalData = DB::table('quatation_master')
                    ->join('inquiry', 'inquiry.inquiry_id', '=', 'quatation_master.inquiry_id')
                    ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                    ->join('users as u1', 'u1.id', '=', 'inquiry.added_by')
                    ->join('users as u2', 'u2.id', '=', 'inquiry.delete_status')
                    ->whereRaw($where1)
                    ->select('inquiry.inquiry_id', 'inquiry.inquiry_no', 'inquiry.inquiry_date', 'inquiry.inquiry_time', 'inquiry.cancel_date', 'inquiry.cancel_date', 'customer_master.prefix', 'customer_master.name', 'customer_master.mobile', 'customer_master.mobile_2', 'customer_master.mobile_3', 'customer_master.email', 'customer_master.email_2', 'u1.username as inq_person', 'u2.username as delete_person')
                    ->orderBy('inquiry.inquiry_id', 'desc')
                    ->count();
        }

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');


        if (empty($request->input('search.value'))) {
            if ($this->role_id == '1') {
                $where2 = $parentTable2 . ".first_quatation_id = '0' AND " . $parentTable2 . ".delete_status != '0'";
                $result = DB::table('quatation_master')
                        ->join('inquiry', 'inquiry.inquiry_id', '=', 'quatation_master.inquiry_id')
                        ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                        ->join('users as u1', 'u1.id', '=', 'inquiry.added_by')
                        ->join('users as u2', 'u2.id', '=', 'inquiry.delete_status')
                        ->select('inquiry.inquiry_id', 'inquiry.inquiry_no', 'inquiry.inquiry_date', 'inquiry.inquiry_time', 'inquiry.cancel_date', 'customer_master.prefix', 'customer_master.name', 'customer_master.mobile', 'customer_master.mobile_2', 'customer_master.mobile_3', 'customer_master.email', 'customer_master.email_2', 'u1.username as inq_person', 'u2.username as delete_person')
                        ->whereRaw($where2)
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy('inquiry.inquiry_id', 'desc')
                        ->get();
            } else {
                $empId = DB::table('users')->where('id', $this->user_id)->first();
                $extraWork = DB::table('emp_transfer')->where('transfer_emp_id', $empId->emp_id)->first();

                if ($extraWork) {
                    $transferFromDate = $extraWork->transfer_from_date;
                    $transferToDate = $extraWork->transfer_to_date;
                    $transferZone = $extraWork->zone_id;

                    $where1 = "find_in_set(" . $masterTable2 . ".zone_id,'$zone_session') AND $parentTable1.first_quatation_id = 0 AND " . $parentTable2 . ".delete_status != '0' OR find_in_set(" . $masterTable2 . ".zone_id,'$transferZone')";
                } else {
                    $where1 = "find_in_set(" . $masterTable2 . ".zone_id,'$zone_session') AND $parentTable1.first_quatation_id = 0 AND " . $parentTable2 . ".delete_status != '0' ";
                }

                $result = DB::table('quatation_master')
                        ->join('inquiry', 'inquiry.inquiry_id', '=', 'quatation_master.inquiry_id')
                        ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                        ->join('users as u1', 'u1.id', '=', 'inquiry.added_by')
                        ->join('users as u2', 'u2.id', '=', 'inquiry.delete_status')
                        ->select('inquiry.inquiry_id', 'inquiry.inquiry_id', 'inquiry.inquiry_no', 'inquiry.inquiry_date', 'inquiry.inquiry_time', 'inquiry.cancel_date', 'inquiry.cancel_date', 'customer_master.prefix', 'customer_master.name', 'customer_master.mobile', 'customer_master.mobile_2', 'customer_master.mobile_3', 'customer_master.email', 'customer_master.email_2', 'u1.username as inq_person', 'u2.username as delete_person')
                        ->whereRaw($where1)
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy('inquiry.inquiry_id', 'desc')
                        ->get();
            }
        } else {
            $search = $request->input('search.value');

            if ($this->role_id == '1') {
                $where2 = $parentTable2 . ".first_quatation_id = '0' AND " . $parentTable2 . ".delete_status != '0'";

                $result = DB::table('quatation_master')
                        ->join('inquiry', 'inquiry.inquiry_id', '=', 'quatation_master.inquiry_id')
                        ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                        ->join('users as u1', 'u1.id', '=', 'inquiry.added_by')
                        ->join('users as u2', 'u2.id', '=', 'inquiry.delete_status')
                        ->select('inquiry.inquiry_id', 'inquiry.inquiry_no', 'inquiry.inquiry_date', 'inquiry.inquiry_time', 'inquiry.cancel_date', 'customer_master.prefix', 'customer_master.name', 'customer_master.mobile', 'customer_master.mobile_2', 'customer_master.mobile_3', 'customer_master.email', 'customer_master.email_2', 'u1.username as inq_person', 'u2.username as delete_person')
                        ->whereRaw($where2)
                        ->where(function($query) use($search) {
                            $query->orWhere('inquiry.inquiry_no', 'LIKE', "%{$search}%")
                            ->orWhere('inquiry.inquiry_date', 'LIKE', "%{$search}%")
                            ->orWhere('u1.username', 'LIKE', "%{$search}%")
                            ->orWhere('u2.username', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.name', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.mobile', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.mobile_2', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.mobile_3', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.email', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.email_2', 'LIKE', "%{$search}%");
                        })
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->get();


                $totalFiltered = DB::table('quatation_master')
                        ->join('inquiry', 'inquiry.inquiry_id', '=', 'quatation_master.inquiry_id')
                        ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                        ->join('users as u1', 'u1.id', '=', 'inquiry.added_by')
                        ->join('users as u2', 'u2.id', '=', 'inquiry.delete_status')
                        ->select('inquiry.inquiry_id', 'inquiry.inquiry_no', 'inquiry.inquiry_date', 'inquiry.inquiry_time', 'inquiry.cancel_date', 'customer_master.prefix', 'customer_master.name', 'customer_master.mobile', 'customer_master.mobile_2', 'customer_master.mobile_3', 'customer_master.email', 'customer_master.email_2', 'u1.username as inq_person', 'u2.username as delete_person')
                        ->whereRaw($where2)
                        ->where(function($query) use($search) {
                            $query->orWhere('inquiry.inquiry_no', 'LIKE', "%{$search}%")
                            ->orWhere('inquiry.inquiry_date', 'LIKE', "%{$search}%")
                            ->orWhere('u1.username', 'LIKE', "%{$search}%")
                            ->orWhere('u2.username', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.name', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.mobile', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.mobile_2', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.mobile_3', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.email', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.email_2', 'LIKE', "%{$search}%");
                        })
                        ->count();
            } else {

                $empId = DB::table('users')->where('id', $this->user_id)->first();
                $extraWork = DB::table('emp_transfer')->where('transfer_emp_id', $empId->emp_id)->first();

                if ($extraWork) {
                    $transferFromDate = $extraWork->transfer_from_date;
                    $transferToDate = $extraWork->transfer_to_date;
                    $transferZone = $extraWork->zone_id;

                    $where1 = "find_in_set(" . $masterTable2 . ".zone_id,'$zone_session') AND $parentTable1.first_quatation_id = 0 AND " . $parentTable2 . ".delete_status != '0' OR find_in_set(" . $masterTable2 . ".zone_id,'$transferZone')";
                } else {
                    $where1 = "find_in_set(" . $masterTable2 . ".zone_id,'$zone_session') AND $parentTable1.first_quatation_id = 0 AND " . $parentTable2 . ".delete_status != '0' ";
                }

                $result = DB::table('quatation_master')
                        ->join('inquiry', 'inquiry.inquiry_id', '=', 'quatation_master.inquiry_id')
                        ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                        ->join('users as u1', 'u1.id', '=', 'inquiry.added_by')
                        ->join('users as u2', 'u2.id', '=', 'inquiry.delete_status')
                        ->select('inquiry.inquiry_id', 'inquiry.inquiry_no', 'inquiry.inquiry_date', 'inquiry.inquiry_time', 'inquiry.cancel_date', 'customer_master.prefix', 'customer_master.name', 'customer_master.mobile', 'customer_master.mobile_2', 'customer_master.mobile_3', 'customer_master.email', 'customer_master.email_2', 'u1.username as inq_person', 'u2.username as delete_person')
                        ->whereRaw($where1)
                        ->where(function($query) use($search) {
                            $query->orWhere('inquiry.inquiry_no', 'LIKE', "%{$search}%")
                            ->orWhere('inquiry.inquiry_date', 'LIKE', "%{$search}%")
                            ->orWhere('u1.username', 'LIKE', "%{$search}%")
                            ->orWhere('u2.username', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.name', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.mobile', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.mobile_2', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.mobile_3', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.email', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.email_2', 'LIKE', "%{$search}%");
                        })
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->get();


                $totalFiltered = DB::table('quatation_master')
                        ->join('inquiry', 'inquiry.inquiry_id', '=', 'quatation_master.inquiry_id')
                        ->join('customer_master', 'customer_master.customer_id', '=', 'inquiry.customer_id')
                        ->join('users as u1', 'u1.id', '=', 'inquiry.added_by')
                        ->join('users as u2', 'u2.id', '=', 'inquiry.delete_status')
                        ->select('inquiry.inquiry_id', 'inquiry.inquiry_no', 'inquiry.inquiry_date', 'inquiry.inquiry_time', 'inquiry.cancel_date', 'customer_master.prefix', 'customer_master.name', 'customer_master.mobile', 'customer_master.mobile_2', 'customer_master.mobile_3', 'customer_master.email', 'customer_master.email_2', 'u1.username as inq_person', 'u2.username as delete_person')
                        ->whereRaw($where1)
                        ->where(function($query) use($search) {
                            $query->orWhere('inquiry.inquiry_no', 'LIKE', "%{$search}%")
                            ->orWhere('inquiry.inquiry_date', 'LIKE', "%{$search}%")
                            ->orWhere('u1.username', 'LIKE', "%{$search}%")
                            ->orWhere('u2.username', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.name', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.mobile', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.mobile_2', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.mobile_3', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.email', 'LIKE', "%{$search}%")
                            ->orWhere('customer_master.email_2', 'LIKE', "%{$search}%");
                        })
                        ->count();
            }
        }


        $data = array();
        if (!empty($result)) {
            $t = $start + 1;
            foreach ($result as $res) {
                $action = "<a title='View' class='btn bg-olive btn-flat btn-sm' href='" . $this->controller . "/cancel_inquiry_view/" . $this->utility->encode($res->inquiry_id) . "'><i class='glyphicon glyphicon-eye-open icon-white'></i> View</a>&nbsp;";

                if ($this->role_id == 1) {
                    $action .= "<a class='btn bg-purple btn-flat btn-sm confirm-delete' href='" . $this->controller . "/inquiry_active/" . $this->utility->encode($res->inquiry_id) . "'><i class='fa fa-check'></i> Active</a>&nbsp;";
                } else {
                    $permission = Data_model::get_permission('inquiry');
                    if ($permission[0]->active == 1) {
                        $action .= "<a class='btn bg-purple btn-flat btn-sm confirm-delete' href='" . $this->controller . "/inquiry_active/" . $this->utility->encode($res->inquiry_id) . "'><i class='fa fa-check'></i> Active</a>&nbsp;";
                    }
                }

                $customer_mobile = array();
                if ($res->mobile != '')
                    $customer_mobile[] = $res->mobile;
                if ($res->mobile_2 != '')
                    $customer_mobile[] = $res->mobile_2;
                if ($res->mobile_3 != '')
                    $customer_mobile[] = $res->mobile_3;

                $customer_email = array();
                if ($res->email != '')
                    $customer_email[] = $res->email;
                if ($res->email_2 != '')
                    $customer_email[] = $res->email_2;

                if ($res->cancel_date != '') {
                    $cancel_date = date('d-m-Y', strtotime($res->cancel_date));
                } else {
                    $cancel_date = '';
                }


                $nestedData['id'] = $t;
                $nestedData['inquiry_no'] = $res->inquiry_no;
                $nestedData['inquiry_date'] = date("d-m-Y h:i a", strtotime($res->inquiry_date . " " . $res->inquiry_time));
                $nestedData['inquiry_person'] = $res->inq_person;
                $nestedData['customer_name'] = $res->name;
                $nestedData['customer_mobile'] = implode('<br />', $customer_mobile);
                $nestedData['customer_email'] = implode('<br />', $customer_email);
                $nestedData['cancel_date'] = $cancel_date;
                $nestedData['cancel_by'] = $res->delete_person;

                /* action start */
                $nestedData['action'] = $action;

                $data[] = $nestedData;
                $t++;
            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function get_inq_delete(Request $request) {
        $data = array("data" => "");
        $zone_session = $this->zone_id;
        $parentTable1 = $this->foreign_table;
        $masterTable1 = 'quatation_master';
        $masterTable2 = 'quatation_master';
        $parentTable2 = $this->foreign_table;
        $select2 = array($masterTable1 . '.*', $parentTable1 . '.*');
        $condition_22 = $masterTable1 . '.inquiry_id';
        $condition_2 = $parentTable1 . '.inquiry_id';
        $orderField2 = $masterTable1 . '.q_master_id';
        $orderType2 = 'DESC';
        if ($this->role_id == '1') {
            $where3 = $parentTable2 . ".first_quatation_id = '0' AND " . $parentTable2 . ".remove_status != '0'";

            $result = Data_model::singleJoin_raw($masterTable2, $parentTable2, $select2, $condition_22, $condition_2, $where3, $orderField2, $orderType2);
        } else {
            $where4 = "find_in_set(" . $masterTable2 . ".zone_id,'$zone_session') AND $parentTable1.first_quatation_id = 0 AND " . $parentTable2 . ".remove_status != '0' ";
            $result = Data_model::singleJoin_raw($masterTable2, $parentTable2, $select2, $condition_22, $condition_2, $where4, $orderField2, $orderType2);
        }

        if ($result) {
            foreach ($result as $key => $val) {
                $action = "<a title='View' class='btn bg-olive btn-flat btn-sm' href='" . $this->controller . "/cancel_inquiry_view/" . $this->utility->encode($val->inquiry_id) . "'><i class='glyphicon glyphicon-eye-open icon-white'></i> View</a>&nbsp;";


                $users = DB::table('users')
                        ->where('id', "=", $val->added_by)
                        ->get();
                $users_inq = DB::table('users')
                        ->where('id', "=", $val->remove_status)
                        ->get();

                $customer = DB::table('customer_master')
                        ->where('customer_id', "=", $val->customer_id)
                        ->get();

                $customer_mobile = array();
                if ($customer[0]->mobile != '')
                    $customer_mobile[] = $customer[0]->mobile;
                if ($customer[0]->mobile_2 != '')
                    $customer_mobile[] = $customer[0]->mobile_2;
                if ($customer[0]->mobile_3 != '')
                    $customer_mobile[] = $customer[0]->mobile_3;

                $customer_email = array();
                if ($customer[0]->email != '')
                    $customer_email[] = $customer[0]->email;
                if ($customer[0]->email_2 != '')
                    $customer_email[] = $customer[0]->email_2;

                $data["data"][] = array(
                    "inquiry_no" => $val->inquiry_no,
                    "inquiry_date" => date("d-m-Y h:i a", strtotime($val->inquiry_date . " " . $val->inquiry_time)),
                    "inquiry_person" => $users[0]->username,
                    "customer_name" => $customer[0]->prefix . ' ' . $customer[0]->name,
                    "customer_mobile" => implode('<br />', $customer_mobile),
                    "customer_email" => implode('<br />', $customer_email),
                    "delete_by" => $users_inq[0]->username,
                    "actions" => $action
                );
            }
        }
        echo json_encode($data);
    }

    /* 21-05-2018 , Sneha Doshi , simple quatation and gst quatation print - 4 functions */

    public function simple_quatation($id) {

        $data['utility'] = $this->utility;
        $quatation_id = $this->utility->decode($id);



        $data['result'] = Data_model::db_query("SELECT `" . $this->table . "`.* ,`product_master`.`product_name`,`customer_master`.`mobile` ,`customer_master`.`mobile_2`,`customer_master`.`mobile_3`,`customer_master`.`prefix`,`customer_master`.`email`,`customer_master`.`email_2` ,`customer_master`.`name` as `customer_name` ,`customer_master`.`company`,`customer_master`.`address`,`customer_master`.`country_id`,`role_master`.`role_name` , `users`.`username` ,`employee`.`name` as `employee_name` ,`users`.`mobile` as `user_mobile` FROM " . $this->table . "
		LEFT JOIN `" . $this->foreign_table . "` ON `" . $this->table . "`.`" . $this->foreign_id . "` = `" . $this->foreign_table . "`.`" . $this->foreign_id . "`
		LEFT JOIN `product_master` ON `product_master`.`product_id` = `" . $this->foreign_table . "`.`product_id`
		LEFT JOIN `customer_master` ON `customer_master`.`customer_id` = `" . $this->foreign_table . "`.`customer_id`
		LEFT JOIN `users` ON `users`.`id` = `" . $this->table . "`.`added_by`
		LEFT JOIN `role_master` ON `role_master`.`role_id` = `users`.`role_id`
		LEFT JOIN `employee` ON `employee`.`emp_id` = `users`.`emp_id`
		where `" . $this->table . "`.`" . $this->primary_id . "` = '" . $quatation_id . "'");

        $get_cur = Data_model::retrive('country_master', '*', array('country_id' => $data['result'][0]->country_id), 'country_name', 'DESC');

        if (!empty($get_cur)) {
            $cur_type = $get_cur[0]->cur_type;
        } else {
            $cur_type = 'inr';
        }
        $data['cur_type'] = $cur_type;


        $data['quatation_product'] = Data_model::retrive('quatation_product', '*', array(), 'name', 'ASC');

        $data['company'] = Data_model::retrive('company', '*', array(), 'name', 'ASC');
        $data['controller_name'] = $this->controller;

        $data['letter_head'] = Data_model::retrive('letterhead_master', '*', array(), 'letterhead_name', 'ASC');

        $pdf = PDF::loadView($this->view . '/simple_print', $data);
        return $pdf->stream('Quotation ' . date('d-m-Y H:i:s'));
    }

    public function revice_simple_quatation($id) {

        $revise_quatation_id = $this->utility->decode($id);


        $data['result'] = Data_model::db_query("SELECT `revise_quatation`.* ,`product_master`.`product_name`,`customer_master`.`mobile` ,`customer_master`.`mobile_2` ,`customer_master`.`mobile_3`,`customer_master`.`email` ,`customer_master`.`email_2`,`customer_master`.`prefix`,`customer_master`.`name` as `customer_name`,`customer_master`.`company`,`customer_master`.`address` ,`customer_master`.`country_id`,`role_master`.`role_name` ,`employee`.`name` as `employee_name` ,`users`.`username` , `users`.`mobile` as `user_mobile` FROM revise_quatation
		LEFT JOIN `" . $this->foreign_table . "` ON `revise_quatation`.`" . $this->foreign_id . "` = `" . $this->foreign_table . "`.`" . $this->foreign_id . "`
		LEFT JOIN `product_master` ON `product_master`.`product_id` = `" . $this->foreign_table . "`.`product_id`
		LEFT JOIN `customer_master` ON `customer_master`.`customer_id` = `" . $this->foreign_table . "`.`customer_id`
		LEFT JOIN `users` ON `users`.`id` = `revise_quatation`.`added_by`
		LEFT JOIN `role_master` ON `role_master`.`role_id` = `users`.`role_id`
		LEFT JOIN `employee` ON `employee`.`emp_id` = `users`.`emp_id`
		where `revise_quatation`.`revise_id` = '$revise_quatation_id'");

        $get_cur = Data_model::retrive('country_master', '*', array('country_id' => $data['result'][0]->country_id), 'country_name', 'DESC');

        if (!empty($get_cur)) {
            $cur_type = $get_cur[0]->cur_type;
        } else {
            $cur_type = 'inr';
        }
        $data['cur_type'] = $cur_type;


        $data['letter_head'] = Data_model::retrive('letterhead_master', '*', array(), 'letterhead_name', 'ASC');
        $data['quatation_product'] = Data_model::retrive('quatation_product', '*', array(), 'name', 'ASC');
        $data['company'] = Data_model::retrive('company', '*', array(), 'name', 'ASC');
        $data['controller_name'] = $this->controller;
        $data['msgName'] = $this->msgName;


        $pdf = PDF::loadView($this->view . '/simple_revise_print', $data);
        return $pdf->stream('Quotation ' . date('d-m-Y H:i:s'));
    }

    public function gst_quatation($id) {
        $data['utility'] = $this->utility;
        $quatation_id = $this->utility->decode($id);


        $data['result'] = Data_model::db_query("SELECT `" . $this->table . "`.* ,`product_master`.`product_name`,`customer_master`.`mobile` ,`customer_master`.`mobile_2`,`customer_master`.`mobile_3`,`customer_master`.`prefix`,`customer_master`.`email`,`customer_master`.`email_2` ,`customer_master`.`name` as `customer_name` ,`customer_master`.`company`,`customer_master`.`address`,`customer_master`.`country_id`,`role_master`.`role_name` , `users`.`username` ,`employee`.`name` as `employee_name` ,`users`.`mobile` as `user_mobile` FROM " . $this->table . "
		LEFT JOIN `" . $this->foreign_table . "` ON `" . $this->table . "`.`" . $this->foreign_id . "` = `" . $this->foreign_table . "`.`" . $this->foreign_id . "`
		LEFT JOIN `product_master` ON `product_master`.`product_id` = `" . $this->foreign_table . "`.`product_id`
		LEFT JOIN `customer_master` ON `customer_master`.`customer_id` = `" . $this->foreign_table . "`.`customer_id`
		LEFT JOIN `users` ON `users`.`id` = `" . $this->table . "`.`added_by`
		LEFT JOIN `role_master` ON `role_master`.`role_id` = `users`.`role_id`
		LEFT JOIN `employee` ON `employee`.`emp_id` = `users`.`emp_id`
		where `" . $this->table . "`.`" . $this->primary_id . "` = '" . $quatation_id . "'");

        $get_cur = Data_model::retrive('country_master', '*', array('country_id' => $data['result'][0]->country_id), 'country_name', 'DESC');

        if (!empty($get_cur)) {
            $cur_type = $get_cur[0]->cur_type;
        } else {
            $cur_type = 'inr';
        }
        $data['cur_type'] = $cur_type;

        $data['specification'] = Data_model::retrive('specification_master', '*', array(), 'sequence', 'ASC');
        $data['quatation_product'] = Data_model::retrive('quatation_product', '*', array(), 'name', 'ASC');
        $data['terms_condition'] = Data_model::retrive('terms_condition', '*', array(), 'term_id', 'ASC');
        $data['company'] = Data_model::retrive('company', '*', array(), 'name', 'ASC');
        $data['controller_name'] = $this->controller;

        $data['letter_head'] = Data_model::retrive('letterhead_master', '*', array(), 'letterhead_name', 'ASC');

        $pdf = PDF::loadView($this->view . '/gst_print', $data);

        return $pdf->stream('Quotation ' . date('d-m-Y H:i:s'));
    }

    public function revice_gst_quatation($id) {
        $data['utility'] = $this->utility;
        $data['msg'] = $this->msgName;
        $revise_quatation_id = $this->utility->decode($id);

        $data['result'] = Data_model::db_query("SELECT `revise_quatation`.* ,`product_master`.`product_name`,`customer_master`.`mobile` ,`customer_master`.`mobile_2` ,`customer_master`.`mobile_3`,`customer_master`.`email` ,`customer_master`.`email_2`,`customer_master`.`prefix`,`customer_master`.`name` as `customer_name`,`customer_master`.`company`,`customer_master`.`address` ,`customer_master`.`country_id`,`role_master`.`role_name` ,`employee`.`name` as `employee_name` ,`users`.`username` , `users`.`mobile` as `user_mobile` FROM revise_quatation
		LEFT JOIN `" . $this->foreign_table . "` ON `revise_quatation`.`" . $this->foreign_id . "` = `" . $this->foreign_table . "`.`" . $this->foreign_id . "`
		LEFT JOIN `product_master` ON `product_master`.`product_id` = `" . $this->foreign_table . "`.`product_id`
		LEFT JOIN `customer_master` ON `customer_master`.`customer_id` = `" . $this->foreign_table . "`.`customer_id`
		LEFT JOIN `users` ON `users`.`id` = `revise_quatation`.`added_by`
		LEFT JOIN `role_master` ON `role_master`.`role_id` = `users`.`role_id`
		LEFT JOIN `employee` ON `employee`.`emp_id` = `users`.`emp_id`
		where `revise_quatation`.`revise_id` = '$revise_quatation_id'");

        $get_cur = Data_model::retrive('country_master', '*', array('country_id' => $data['result'][0]->country_id), 'country_name', 'DESC');

        if (!empty($get_cur)) {
            $cur_type = $get_cur[0]->cur_type;
        } else {
            $cur_type = 'inr';
        }
        $data['cur_type'] = $cur_type;

        $data['specification'] = Data_model::retrive('specification_master', '*', array(), 'sequence', 'ASC');
        $data['letter_head'] = Data_model::retrive('letterhead_master', '*', array(), 'letterhead_name', 'ASC');
        $data['quatation_product'] = Data_model::retrive('quatation_product', '*', array(), 'name', 'ASC');
        $data['terms_condition'] = Data_model::retrive('terms_condition', '*', array(), 'term_id', 'ASC');
        $data['company'] = Data_model::retrive('company', '*', array(), 'name', 'ASC');
        $data['controller_name'] = $this->controller;
        $data['msgName'] = $this->msgName;

        $pdf = PDF::loadView($this->view . '/gst_revise_print', $data);
        return $pdf->stream('Quotation ' . date('d-m-Y H:i:s'));
    }

    public function send_sms(Request $request) {
        $customer_mno = $request->input('customer_mno');
        $inquiry_id = $request->input('inquiry_id');

        /* get quotation no */
        $getQuot = Data_model::db_query("select * from `quatation` where inquiry_id = '" . $inquiry_id . "' ");
        $quotNo = $getQuot[0]->quatation_no;

        /* get address */
        $getmsg = Data_model::db_query("select * from `sms_format` where type = 'quatation' ");
        $massage = $quotNo . " " . $getmsg[0]->format;

        for ($r = 0; $r < count($customer_mno); $r++) {
            if ($customer_mno[$r] != '') {
                $postdata = http_build_query(
                        array('username' => 'rajwater',
                            'password' => 'rajwater@321',
                            'senderid' => 'RWTGPL',
                            'route' => '1',
                            'unicode' => '2',
                            'number' => $customer_mno[$r],
                            'message' => $massage
                        )
                );

                $opts = array('http' =>
                    array(
                        'method' => 'POST',
                        'header' => 'Content-type: application/x-www-form-urlencoded',
                        'content' => $postdata
                    )
                );

                $context = stream_context_create($opts);
                $result = trim(file_get_contents('http://buzz.azmarq.com/http-api.php', false, $context));
            }
        }

        $response[0] = 'success';

        echo json_encode($response);
        exit;
    }

    public function deleteQuotation($id) {
        $id = $this->utility->decode($id);
        $where = array('revise_id' => $id);
        if (Data_model::remove('revise_quatation', $where)) {
            $msg = array('success' => 'Quotation Delete Sucessfully');
        } else {
            $msg = array('error' => 'Some Problem ... Please Try Again');
        }
        return redirect($this->controller)->with($msg);
    }

}
