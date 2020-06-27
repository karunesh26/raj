<?php

/*

|--------------------------------------------------------------------------

| Application Routes

|--------------------------------------------------------------------------

|

| Here is where you can register all of the routes for an application.

| It's a breeze. Simply tell Laravel the URIs it should respond to

| and give it the controller to call when that URI is requested.

|

*/

/*Route::get('/', function () {

    return view('welcome');

});

*/

// ___________________________ ADMIN SIDE ROUTES_______________________

Route::get('/', array('uses' => 'Login@index'));

Route::get('login', array('uses' => 'Login@index'));

Route::post('checklogin', array('uses' => 'Login@doLogin'));

Route::get('forgot_password', array('uses' => 'Login@forgot_password'));

Route::post('change_password', array('uses' => 'Login@update_password'));

Route::get('logout',  'Logout@index');



Route::get('Change_password',  'Change_password@index');

Route::post('Change_password/update','Change_password@update');



Route::get('Dashboard','Dashboard@index');

Route::get('Dashboard/clear/{id}/{type}','Dashboard@read_clear');



Route::get('Role','Role@index');

Route::get('Role/add','Role@add');

Route::post('Role/insert','Role@insert');

Route::get('Role/edit/{id}','Role@edit');

Route::post('Role/update','Role@update');

Route::post('Role/duplicate','Role@duplicate');

Route::post('Role/duplicate_update','Role@duplicate_update');

Route::get('Role/delete/{id}','Role@delete');



Route::get('Employee','Employee@index');

Route::get('Employee/add','Employee@add');

Route::post('Employee/insert','Employee@insert');

Route::get('Employee/edit/{id}','Employee@edit');

Route::post('Employee/update','Employee@update');

Route::post('Employee/duplicate','Employee@duplicate');

Route::post('Employee/duplicate_update','Employee@duplicate_update');

Route::get('Employee/delete/{id}','Employee@delete');

Route::post('Employee/change_emp_password','Employee@change_emp_password');



Route::get('Category','Category@index');

Route::get('Category/add','Category@add');

Route::post('Category/insert','Category@insert');

Route::get('Category/edit/{id}','Category@edit');

Route::post('Category/update','Category@update');

Route::post('Category/duplicate','Category@duplicate');

Route::post('Category/duplicate_update','Category@duplicate_update');

Route::get('Category/delete/{id}','Category@delete');



Route::get('Product','Product@index');

Route::get('Product/add','Product@add');

Route::post('Product/insert','Product@insert');

Route::get('Product/edit/{id}','Product@edit');

Route::post('Product/update','Product@update');

Route::post('Product/duplicate','Product@duplicate');

Route::post('Product/duplicate_update','Product@duplicate_update');

Route::get('Product/delete/{id}','Product@delete');



Route::get('Quatation_product','Quatation_product@index');

Route::get('Quatation_product/add','Quatation_product@add');

Route::post('Quatation_product/insert','Quatation_product@insert');

Route::get('Quatation_product/edit/{id}','Quatation_product@edit');

Route::post('Quatation_product/update','Quatation_product@update');

Route::post('Quatation_product/duplicate','Quatation_product@duplicate');

Route::post('Quatation_product/duplicate_update','Quatation_product@duplicate_update');

Route::get('Quatation_product/delete/{id}','Quatation_product@delete');



Route::get('Specification','Specification@index');

Route::get('Specification/add','Specification@add');

Route::post('Specification/insert','Specification@insert');

Route::get('Specification/edit/{id}','Specification@edit');

Route::post('Specification/update','Specification@update');

Route::post('Specification/duplicate','Specification@duplicate');

Route::post('Specification/duplicate_update','Specification@duplicate_update');

Route::get('Specification/delete/{id}','Specification@delete');





Route::get('Zone','Zone@index');

Route::get('Zone/add','Zone@add');

Route::post('Zone/insert','Zone@insert');

Route::get('Zone/edit/{id}','Zone@edit');

Route::post('Zone/update','Zone@update');

Route::post('Zone/duplicate','Zone@duplicate');

Route::post('Zone/duplicate_update','Zone@duplicate_update');

Route::get('Zone/delete/{id}','Zone@delete');



Route::get('Client_category','Client_category@index');

Route::get('Client_category/add','Client_category@add');

Route::post('Client_category/insert','Client_category@insert');

Route::get('Client_category/edit/{id}','Client_category@edit');

Route::post('Client_category/update','Client_category@update');

Route::post('Client_category/duplicate','Client_category@duplicate');

Route::post('Client_category/duplicate_update','Client_category@duplicate_update');

Route::get('Client_category/delete/{id}','Client_category@delete');



Route::get('Source','Source@index');

Route::get('Source/add','Source@add');

Route::post('Source/insert','Source@insert');

Route::get('Source/edit/{id}','Source@edit');

Route::post('Source/update','Source@update');

Route::post('Source/duplicate','Source@duplicate');

Route::post('Source/duplicate_update','Source@duplicate_update');

Route::get('Source/delete/{id}','Source@delete');



Route::get('Country','Country@index');

Route::get('Country/add','Country@add');

Route::post('Country/insert','Country@insert');

Route::get('Country/edit/{id}','Country@edit');

Route::post('Country/update','Country@update');

Route::post('Country/duplicate','Country@duplicate');

Route::post('Country/duplicate_update','Country@duplicate_update');

Route::get('Country/delete/{id}','Country@delete');



Route::get('Address','Address@index');

Route::get('Address/add','Address@add');

Route::post('Address/insert','Address@insert');

Route::get('Address/edit/{id}','Address@edit');

Route::post('Address/update','Address@update');

Route::post('Address/duplicate','Address@duplicate');

Route::post('Address/duplicate_update','Address@duplicate_update');

Route::get('Address/delete/{id}','Address@delete');



Route::get('Email','Email@index');

Route::get('Email/add','Email@add');

Route::post('Email/insert','Email@insert');

Route::get('Email/edit/{id}','Email@edit');

Route::post('Email/update','Email@update');

Route::post('Email/duplicate','Email@duplicate');

Route::post('Email/duplicate_update','Email@duplicate_update');

Route::get('Email/delete/{id}','Email@delete');



Route::get('Catalog','Catalog@index');

Route::get('Catalog/add','Catalog@add');

Route::post('Catalog/insert','Catalog@insert');

Route::get('Catalog/edit/{id}','Catalog@edit');

Route::post('Catalog/update','Catalog@update');

Route::get('Catalog/delete/{id}','Catalog@delete');



Route::get('State','State@index');

Route::get('State/add','State@add');

Route::post('State/insert','State@insert');

Route::get('State/edit/{id}','State@edit');

Route::post('State/update','State@update');

Route::post('State/duplicate','State@duplicate');

Route::post('State/duplicate_update','State@duplicate_update');

Route::get('State/delete/{id}','State@delete');



Route::get('City','City@index');

Route::get('City/add','City@add');

Route::post('City/insert','City@insert');

Route::get('City/edit/{id}','City@edit');

Route::post('City/update','City@update');

Route::post('City/duplicate','City@duplicate');

Route::post('City/duplicate_update','City@duplicate_update');

Route::get('City/delete/{id}','City@delete');

Route::post('City/state_add','City@state_add');

Route::post('City/state_duplicate','City@state_duplicate');

Route::post('City/get_state','City@get_state');

Route::post('City/check_state','City@check_state');



Route::get('Minimum_days','Minimum_days@index');

Route::post('Minimum_days/update','Minimum_days@update');



Route::get('Terms_condition','Terms_condition@index');

Route::post('Terms_condition/update','Terms_condition@update');



Route::get('Assign_zone','Assign_zone@index');

Route::post('Assign_zone/update','Assign_zone@update');



Route::get('Inquiry','Inquiry@index');

Route::get('Inquiry/add','Inquiry@add');

Route::post('Inquiry/insert','Inquiry@insert');

Route::get('Inquiry/edit/{id}','Inquiry@edit');

Route::post('Inquiry/update','Inquiry@update');

Route::get('Inquiry/editSource/{id}','Inquiry@editSource');

Route::post('Inquiry/updateInq','Inquiry@updateInq');

Route::get('Inquiry/delete/{id}','Inquiry@delete');

Route::get('Inquiry/view/{id}','Inquiry@view');

Route::get('Inquiry/edit/{id}','Inquiry@edit');

Route::post('Inquiry/get_customer_data','Inquiry@get_customer_data');

Route::post('Inquiry/get_city','Inquiry@get_city');

Route::post('Inquiry/mobile_check','Inquiry@mobile_check');

Route::post('Inquiry/email_check','Inquiry@email_check');

Route::post('Inquiry/get_country_zone','Inquiry@get_country_zone');

Route::post('Inquiry/get_rate','Inquiry@get_rate');

Route::get('Inquiry/inquiry_active/{id}','Inquiry@inquiry_active');

Route::post('Inquiry/get_inq','Inquiry@get_inq');

Route::post('Inquiry/get_active_inq','Inquiry@get_active_inq');

Route::post('Inquiry/get_cancel_inq','Inquiry@get_cancel_inq');

Route::post('Inquiry/get_delete_inq','Inquiry@get_delete_inq');





//Sample Quatation

Route::get('Sample_quatation','Sample_quatation@index');

Route::get('Sample_quatation/add','Sample_quatation@add');

Route::post('Sample_quatation/insert','Sample_quatation@insert');

Route::get('Sample_quatation/edit/{id}','Sample_quatation@edit');

Route::get('Sample_quatation/view/{id}','Sample_quatation@view');

Route::post('Sample_quatation/update','Sample_quatation@update');

Route::post('Sample_quatation/duplicate','Sample_quatation@duplicate');

Route::get('Sample_quatation/delete/{id}','Sample_quatation@delete');

Route::post('Sample_quatation/get_rate','Sample_quatation@get_rate');

//End Sample Quatation



//Quatation

Route::get('Quatation','Quatation@index');

Route::get('Quatation/generate_quatation/{inq_id}/{m_q_id}','Quatation@generate_quatation');

Route::post('Quatation/add_quatation','Quatation@add_quatation');

Route::post('Quatation/send_mail','Quatation@send_mail');

Route::post('Quatation/send_revise_mail','Quatation@send_revise_mail');

Route::post('Quatation/send_reminder_mail','Quatation@send_reminder_mail');

Route::get('Quatation/view/{id}','Quatation@view');

Route::get('Quatation/print_quatation/{id}/{type}/{with_latterhead}','Quatation@print_quatation');

Route::get('Quatation/cancel_inquiry/{inq_id}/{m_q_id}','Quatation@cancel_inquiry');

Route::post('Quatation/cancel_inquiry_update','Quatation@cancel_inquiry_update');

Route::get('Quatation/cancel_inquiry_view/{inq_id}','Quatation@cancel_inquiry_view');



Route::post('Quatation/get_customer_data','Quatation@get_customer_data');

Route::post('Quatation/get_sample_quatation','Quatation@get_sample_quatation');

Route::post('Quatation/get_cur_type','Quatation@get_cur_type');

Route::post('Quatation/get_city','Quatation@get_city');

Route::post('Quatation/mobile_check','Quatation@mobile_check');

Route::post('Quatation/email_check','Quatation@email_check');

Route::post('Quatation/get_country_zone','Quatation@get_country_zone');

Route::post('Quatation/get_rate','Quatation@get_rate');

Route::post('Quatation/get_inq_data','Quatation@get_inq_data');

Route::post('Quatation/get_customer_email','Quatation@get_customer_email');

Route::post('Quatation/get_customer_mobile','Quatation@get_customer_mobile');

Route::post('Quatation/remark_quotation','Quatation@remark_quotation');

Route::post('Quatation/get_remark','Quatation@get_remark');



Route::get('Quatation/revise_quatation_index/{id}','Quatation@revise_quatation_index');

Route::get('Quatation/revise_quatation/{inquiry_id}/{quatation_id}','Quatation@revise_quatation');

Route::post('Quatation/add_revise_quatation','Quatation@add_revise_quatation');

Route::get('Quatation/revise_quatation_view/{id}','Quatation@revise_quatation_view');

Route::get('Quatation/revise_quatation_print/{id}/{type}/{with_latterhead}','Quatation@revise_quatation_print');

Route::post('Quatation/send_address','Quatation@send_address');

Route::get('Quatation/inquiry_active/{id}','Quatation@inquiry_active');

Route::get('Quatation/revise_quatation_desktop/{inquiry_id}','Quatation@revise_quatation_desktop');

Route::post('Quatation/get_inq_pending','Quatation@get_inq_pending');

Route::post('Quatation/get_inq_quotation','Quatation@get_inq_quotation');

Route::post('Quatation/get_inq_revise','Quatation@get_inq_revise');

Route::post('Quatation/get_inq_cancel','Quatation@get_inq_cancel');

Route::get('Quatation/get_inq_delete','Quatation@get_inq_delete');

Route::post('Quatation/send_sms','Quatation@send_sms');
Route::get('Quatation/delete-quotation/{id}','Quatation@deleteQuotation');




/* 21-05-2018 , Sneha Doshi , simple quatation and gst quatation print - 4 routes*/

Route::get('Quatation/simple_quatation/{id}','Quatation@simple_quatation');

Route::get('Quatation/revice_simple_quatation/{id}','Quatation@revice_simple_quatation');

Route::get('Quatation/gst_quatation/{id}','Quatation@gst_quatation');

Route::get('Quatation/revice_gst_quatation/{id}','Quatation@revice_gst_quatation');

//End Quatation



//Follow Up

/* Route::get('Follow_up','Follow_up@index');  */



Route::get('Follow_up/{id?}',function ($id = '-1') {

	return App::make('\App\Http\Controllers\Follow_up')->index($id);

});



Route::post('Follow_up/get_inquiry_data','Follow_up@get_inquiry_data');

Route::post('Follow_up/customer_update','Follow_up@customer_update');

Route::post('Follow_up/follow_up_add','Follow_up@follow_up_add');

Route::post('Follow_up/document_add','Follow_up@document_add');

Route::post('Follow_up/visitor_add','Follow_up@visitor_add');

Route::post('Follow_up/mobile_check','Follow_up@mobile_check');

Route::post('Follow_up/email_check','Follow_up@email_check');

Route::post('Follow_up/get_allotment','Follow_up@get_allotment');

Route::post('Follow_up/get_follow_up','Follow_up@get_follow_up');

Route::post('Follow_up/get_all','Follow_up@get_all');

Route::post('Follow_up/revise_send','Follow_up@revise_send');

Route::post('Follow_up/prise_issue','Follow_up@prise_issue');

Route::post('Follow_up/hot_list','Follow_up@hot_list');

Route::post('Follow_up/send_address','Follow_up@send_address');

Route::post('Follow_up/order_book','Follow_up@order_book');

Route::post('Follow_up/regret_add','Follow_up@regret_add');

Route::post('Follow_up/get_quatation_date','Follow_up@get_quatation_date');

Route::get('Follow_up/download/{id}','Follow_up@download');

Route::post('Follow_up/send_mail','Follow_up@send_mail');

Route::post('Follow_up/send_reminder_mail','Follow_up@send_reminder_mail');

Route::post('Follow_up/visitor_form_no','Follow_up@visitor_form_no');

Route::post('Follow_up/visitor_form_data','Follow_up@visitor_form_data');

Route::get('Follow_up/visitor_view/{id}','Follow_up@visitor_view');

Route::post('Follow_up/get_state','Follow_up@get_state');

Route::post('Follow_up/get_city','Follow_up@get_city');

Route::post('Follow_up/send_sms','Follow_up@send_sms');

//End Follow Up





//Quatation Status Master

Route::get('Quatation_status_master','Quatation_status_master@index');

Route::get('Quatation_status_master/add','Quatation_status_master@add');

Route::post('Quatation_status_master/insert','Quatation_status_master@insert');

Route::get('Quatation_status_master/edit/{id}','Quatation_status_master@edit');

Route::post('Quatation_status_master/update','Quatation_status_master@update');

Route::post('Quatation_status_master/duplicate','Quatation_status_master@duplicate');

Route::post('Quatation_status_master/duplicate_update','Quatation_status_master@duplicate_update');

Route::get('Quatation_status_master/delete/{id}','Quatation_status_master@delete');

//Follow-up Way Master End





//Follow-up Way Master

Route::get('Followup_way_master','Followup_way_master@index');

Route::get('Followup_way_master/add','Followup_way_master@add');

Route::post('Followup_way_master/insert','Followup_way_master@insert');

Route::get('Followup_way_master/edit/{id}','Followup_way_master@edit');

Route::post('Followup_way_master/update','Followup_way_master@update');

Route::post('Followup_way_master/duplicate','Followup_way_master@duplicate');

Route::post('Followup_way_master/duplicate_update','Followup_way_master@duplicate_update');

Route::get('Followup_way_master/delete/{id}','Followup_way_master@delete');

//Follow-up Way Master End





//Project Division Master

Route::get('Project_division_master','Project_division_master@index');

Route::get('Project_division_master/add','Project_division_master@add');

Route::post('Project_division_master/insert','Project_division_master@insert');

Route::get('Project_division_master/edit/{id}','Project_division_master@edit');

Route::post('Project_division_master/update','Project_division_master@update');

Route::post('Project_division_master/duplicate','Project_division_master@duplicate');

Route::post('Project_division_master/duplicate_update','Project_division_master@duplicate_update');

Route::get('Project_division_master/delete/{id}','Project_division_master@delete');

//Project Division Master End





//Raw Water Master

Route::get('Raw_water_master','Raw_water_master@index');

Route::get('Raw_water_master/add','Raw_water_master@add');

Route::post('Raw_water_master/insert','Raw_water_master@insert');

Route::get('Raw_water_master/edit/{id}','Raw_water_master@edit');

Route::post('Raw_water_master/update','Raw_water_master@update');

Route::post('Raw_water_master/duplicate','Raw_water_master@duplicate');

Route::post('Raw_water_master/duplicate_update','Raw_water_master@duplicate_update');

Route::get('Raw_water_master/delete/{id}','Raw_water_master@delete');

//Raw Water Master End





//Site Status Master

Route::get('Site_status_master','Site_status_master@index');

Route::get('Site_status_master/add','Site_status_master@add');

Route::post('Site_status_master/insert','Site_status_master@insert');

Route::get('Site_status_master/edit/{id}','Site_status_master@edit');

Route::post('Site_status_master/update','Site_status_master@update');

Route::post('Site_status_master/duplicate','Site_status_master@duplicate');

Route::post('Site_status_master/duplicate_update','Site_status_master@duplicate_update');

Route::get('Site_status_master/delete/{id}','Site_status_master@delete');

//Site Status Master End





//Planning Stage Master

Route::get('Planning_stage_master','Planning_stage_master@index');

Route::get('Planning_stage_master/add','Planning_stage_master@add');

Route::post('Planning_stage_master/insert','Planning_stage_master@insert');

Route::get('Planning_stage_master/edit/{id}','Planning_stage_master@edit');

Route::post('Planning_stage_master/update','Planning_stage_master@update');

Route::post('Planning_stage_master/duplicate','Planning_stage_master@duplicate');

Route::post('Planning_stage_master/duplicate_update','Planning_stage_master@duplicate_update');

Route::get('Planning_stage_master/delete/{id}','Planning_stage_master@delete');

//Planning Stage Master End





//Visit Details Master

Route::get('Visit_details_master','Visit_details_master@index');

Route::get('Visit_details_master/add','Visit_details_master@add');

Route::post('Visit_details_master/insert','Visit_details_master@insert');

Route::get('Visit_details_master/edit/{id}','Visit_details_master@edit');

Route::post('Visit_details_master/update','Visit_details_master@update');

Route::post('Visit_details_master/duplicate','Visit_details_master@duplicate');

Route::post('Visit_details_master/duplicate_update','Visit_details_master@duplicate_update');

Route::get('Visit_details_master/delete/{id}','Visit_details_master@delete');

//Visit Details Master End





//Payment Mode Master

Route::get('Payment_mode_master','Payment_mode_master@index');

Route::get('Payment_mode_master/add','Payment_mode_master@add');

Route::post('Payment_mode_master/insert','Payment_mode_master@insert');

Route::get('Payment_mode_master/edit/{id}','Payment_mode_master@edit');

Route::post('Payment_mode_master/update','Payment_mode_master@update');

Route::post('Payment_mode_master/duplicate','Payment_mode_master@duplicate');

Route::post('Payment_mode_master/duplicate_update','Payment_mode_master@duplicate_update');

Route::get('Payment_mode_master/delete/{id}','Payment_mode_master@delete');

//Payment Mode Master End





//Water Report Master

Route::get('Water_report_master','Water_report_master@index');

Route::get('Water_report_master/add','Water_report_master@add');

Route::post('Water_report_master/insert','Water_report_master@insert');

Route::get('Water_report_master/edit/{id}','Water_report_master@edit');

Route::post('Water_report_master/update','Water_report_master@update');

Route::post('Water_report_master/duplicate','Water_report_master@duplicate');

Route::post('Water_report_master/duplicate_update','Water_report_master@duplicate_update');

Route::get('Water_report_master/delete/{id}','Water_report_master@delete');

//Water Report Master End





//Power Supply Master

Route::get('Power_supply_master','Power_supply_master@index');

Route::get('Power_supply_master/add','Power_supply_master@add');

Route::post('Power_supply_master/insert','Power_supply_master@insert');

Route::get('Power_supply_master/edit/{id}','Power_supply_master@edit');

Route::post('Power_supply_master/update','Power_supply_master@update');

Route::post('Power_supply_master/duplicate','Power_supply_master@duplicate');

Route::post('Power_supply_master/duplicate_update','Power_supply_master@duplicate_update');

Route::get('Power_supply_master/delete/{id}','Power_supply_master@delete');

//Power Supply Master End



//document name master

Route::get('Document_name','Document_name@index');

Route::get('Document_name/add','Document_name@add');

Route::post('Document_name/insert','Document_name@insert');

Route::get('Document_name/edit/{id}','Document_name@edit');

Route::post('Document_name/update','Document_name@update');

Route::post('Document_name/duplicate','Document_name@duplicate');

Route::post('Document_name/duplicate_update','Document_name@duplicate_update');

Route::get('Document_name/delete/{id}','Document_name@delete');

//document name master end



//letterhead master

Route::get('Letterhead','Letterhead@index');

Route::get('Letterhead/edit/{id}','Letterhead@edit');

Route::post('Letterhead/update','Letterhead@update');



//letterhead End



//user rights

Route::get('User_rights','User_rights@index');

Route::get('User_rights/add','User_rights@add');

Route::post('User_rights/insert','User_rights@insert');

Route::get('User_rights/edit/{id}','User_rights@edit');

Route::post('User_rights/update','User_rights@update');

Route::post('User_rights/get_user','User_rights@get_user');

Route::post('User_rights/get_data','User_rights@get_data');

Route::post('User_rights/update_data','User_rights@update_data');

Route::get('User_rights/delete/{id}','User_rights@delete');



//user rights end



// Rate master start

Route::get('Rate_master','Rate_master@index');

Route::get('Rate_master/edit/{id}','Rate_master@edit');

Route::post('Rate_master/update','Rate_master@update');

// Rate master end



//search start

Route::get('Search','Search@index');

Route::post('Search/get_search_data','Search@get_search_data');

//search end



// proforma start

Route::get('Proforma_Invoice','Proforma_Invoice@index');

Route::post('Proforma_Invoice/get_search_data','Proforma_Invoice@get_search_data');

Route::get('Proforma_Invoice/print_pdf/{id}/{type}','Proforma_Invoice@print_pdf');

Route::get('Proforma_Invoice/view/{id}','Proforma_Invoice@view');

Route::get('Proforma_Invoice/generate_invoice/{type}/{id}','Proforma_Invoice@generate_invoice');

Route::post('Proforma_Invoice/add_invoice','Proforma_Invoice@add_invoice');

Route::post('Proforma_Invoice/get_rate','Proforma_Invoice@get_rate');

Route::post('Proforma_Invoice/send_sms','Proforma_Invoice@send_sms');

Route::post('Proforma_Invoice/get_customer_mobile','Proforma_Invoice@get_customer_mobile');

Route::post('Proforma_Invoice/get_quotation_invoice','Proforma_Invoice@get_quotation_invoice');
Route::post('Proforma_Invoice/get_rev_quotation_invoice','Proforma_Invoice@get_rev_quotation_invoice');


// proforma end



// job card start

Route::get('Job_card','Job_card@index');

Route::post('Job_card/get_search_data','Job_card@get_search_data');

Route::get('Job_card/print_pdf/{id}/{type}','Job_card@print_pdf');

Route::get('Job_card/view_job_card/{id}','Job_card@view_job_card');

Route::get('Job_card/view/{id}','Job_card@view');

// job card end



//quotation Email start

Route::get('Quotation_email','Quotation_email@index');

Route::get('Quotation_email/add','Quotation_email@add');

Route::post('Quotation_email/insert','Quotation_email@insert');

Route::get('Quotation_email/edit/{id}','Quotation_email@edit');

Route::post('Quotation_email/update','Quotation_email@update');

Route::get('Quotation_email/delete/{id}','Quotation_email@delete');

//quotation email end



//quotation Email start

Route::get('Follow_up_email','Follow_up_email@index');

Route::get('Follow_up_email/add','Follow_up_email@add');

Route::post('Follow_up_email/insert','Follow_up_email@insert');

Route::get('Follow_up_email/edit/{id}','Follow_up_email@edit');

Route::post('Follow_up_email/update','Follow_up_email@update');

Route::get('Follow_up_email/delete/{id}','Follow_up_email@delete');

//quotation email end



// order book start

Route::get('Order_book','Order_book@index');

Route::post('Order_book/get_search_data','Order_book@get_search_data');

Route::get('Order_book/print_pdf/{id}','Order_book@print_pdf');

Route::get('Order_book/view/{id}','Order_book@view');

Route::get('Order_book/delete/{id}','Order_book@delete');

Route::get('Order_book/active/{id}','Order_book@active');
Route::get('Order_book/add_to_follow/{id}','Order_book@addtofollowup');

// order book end



// Big Zone start

Route::get('Big_zone_amount','Big_zone_amount@index');

Route::get('Big_zone_amount/edit/{id}','Big_zone_amount@edit');

Route::post('Big_zone_amount/update','Big_zone_amount@update');

// Big Zone end



// customer detail

Route::get('Customer','Customer@index');

Route::get('Customer/edit/{id}','Customer@edit');

Route::post('Customer/update','Customer@update');

Route::post('Customer/get_city','Customer@get_city');

Route::post('Customer/mobile_check','Customer@mobile_check');

Route::post('Customer/email_check','Customer@email_check');

Route::post('Customer/get_country_zone','Customer@get_country_zone');

Route::get('Customer/get_all_customer','Customer@get_all_customer');

// customer detail



// hot list start

Route::get('Hot_list/{id?}',function ($id = '-1') {

	return App::make('\App\Http\Controllers\Hot_list')->index($id);

});



Route::post('Hot_list/get_hot_list_data','Hot_list@hot_list_data');

Route::post('Hot_list/get_inquiry_data','Hot_list@get_inquiry_data');

Route::post('Hot_list/customer_update','Hot_list@customer_update');

Route::post('Hot_list/follow_up_add','Hot_list@follow_up_add');

Route::post('Hot_list/document_add','Hot_list@document_add');

Route::post('Hot_list/visitor_add','Hot_list@visitor_add');

Route::post('Hot_list/mobile_check','Hot_list@mobile_check');

Route::post('Hot_list/email_check','Hot_list@email_check');

Route::post('Hot_list/get_follow_up','Hot_list@get_follow_up');

Route::post('Hot_list/get_all','Hot_list@get_all');

Route::post('Hot_list/revise_send','Hot_list@revise_send');

Route::post('Hot_list/prise_issue','Hot_list@prise_issue');

Route::post('Hot_list/hot_list','Hot_list@hot_list');

Route::post('Hot_list/send_address','Hot_list@send_address');

Route::post('Hot_list/order_book','Hot_list@order_book');

Route::post('Hot_list/regret_add','Hot_list@regret_add');

Route::post('Hot_list/get_quatation_date','Hot_list@get_quatation_date');

Route::get('Hot_list/download/{id}','Hot_list@download');

Route::post('Hot_list/send_mail','Hot_list@send_mail');

Route::post('Hot_list/send_reminder_mail','Hot_list@send_reminder_mail');

Route::post('Hot_list/visitor_form_no','Hot_list@visitor_form_no');

Route::post('Hot_list/visitor_form_data','Hot_list@visitor_form_data');

Route::get('Hot_list/visitor_view/{id}','Hot_list@visitor_view');

Route::post('Hot_list/get_state','Hot_list@get_state');

Route::post('Hot_list/get_city','Hot_list@get_city');



// hot list end







/* quotation transfer start */

Route::get('Quotation_transfer','Quotation_transfer@index');

Route::get('Quotation_transfer/edit/{id}','Quotation_transfer@edit');

Route::post('Quotation_transfer/update','Quotation_transfer@update');

Route::get('Quotation_transfer/get_quotation_all','Quotation_transfer@get_quotation_all');

/* quotation transfer end */



/*

22-05-2018

Sneha Doshi

Transfer Employee Inquiry

*/

Route::get('Employee_transfer','Employee_transfer@index');

Route::get('Employee_transfer/add','Employee_transfer@add');

Route::post('Employee_transfer/insert','Employee_transfer@insert');

Route::get('Employee_transfer/delete/{id}','Employee_transfer@delete');







/* regret list start */

Route::get('Regret_list/{id?}',function ($id = '-1') {

	return App::make('\App\Http\Controllers\Regret_list')->index($id);

});



Route::post('Regret_list/get_inquiry_data','Regret_list@get_inquiry_data');

Route::post('Regret_list/customer_update','Regret_list@customer_update');

Route::post('Regret_list/follow_up_add','Regret_list@follow_up_add');

Route::post('Regret_list/document_add','Regret_list@document_add');

Route::post('Regret_list/visitor_add','Regret_list@visitor_add');

Route::post('Regret_list/mobile_check','Regret_list@mobile_check');

Route::post('Regret_list/email_check','Regret_list@email_check');

Route::post('Regret_list/get_regret_list','Regret_list@get_regret_list');

Route::post('Regret_list/revise_send','Regret_list@revise_send');

Route::post('Regret_list/prise_issue','Regret_list@prise_issue');

Route::post('Regret_list/hot_list','Regret_list@hot_list');

Route::post('Regret_list/send_address','Regret_list@send_address');

Route::post('Regret_list/order_book','Regret_list@order_book');

Route::post('Regret_list/regret_add','Regret_list@regret_add');

Route::post('Regret_list/get_quatation_date','Regret_list@get_quatation_date');

Route::get('Regret_list/download/{id}','Regret_list@download');

Route::post('Regret_list/send_mail','Regret_list@send_mail');

Route::post('Regret_list/send_reminder_mail','Regret_list@send_reminder_mail');

Route::post('Regret_list/visitor_form_no','Regret_list@visitor_form_no');

Route::post('Regret_list/visitor_form_data','Regret_list@visitor_form_data');

Route::get('Regret_list/visitor_view/{id}','Regret_list@visitor_view');

Route::post('Regret_list/get_state','Regret_list@get_state');

Route::post('Regret_list/get_city','Regret_list@get_city');



/* Regret List End */



/* Power Calculation Start */



Route::get('Power_calculation','Power_calculation@index');

Route::post('Power_calculation/get_search_data','Power_calculation@get_search_data');

Route::get('Power_calculation/view/{id}','Power_calculation@view');

Route::get('Power_calculation/generate_invoice/{id}','Power_calculation@generate_invoice');

Route::post('Power_calculation/add_power_data','Power_calculation@add_power_data');

Route::get('Power_calculation/get_power_data','Power_calculation@get_power_data');

Route::get('Power_calculation/generate_power/{id}/{type}','Power_calculation@generate_power');

Route::get('Power_calculation/generated_power_data','Power_calculation@generated_power_data');

Route::get('Power_calculation/print_pdf/{id}/{type}','Power_calculation@print_pdf');

Route::post('Power_calculation/get_hp_power','Power_calculation@get_hp_power');



/* power Calculation end */







/*  reports routes start  */



/* inquiry report */

Route::get('Inquiry_report','Inquiry_report@index');

Route::post('Inquiry_report/get_search_data','Inquiry_report@get_search_data');



/* hot list report */

Route::get('Hot_list_report','Hot_list_report@index');

Route::post('Hot_list_report/get_search_data','Hot_list_report@get_search_data');



/* order book report */

Route::get('Order_book_report','Order_book_report@index');

Route::post('Order_book_report/get_search_data','Order_book_report@get_search_data');



/* regret report */

Route::get('Regret_report','Regret_report@index');

Route::post('Regret_report/get_search_data','Regret_report@get_search_data');



/* Customer Report */

Route::get('Customer_report','Customer_report@index');

Route::post('Customer_report/get_search_data','Customer_report@get_search_data');



/* visiting Report */

Route::get('Visiting_report','Visiting_report@index');

Route::post('Visiting_report/get_search_data','Visiting_report@get_search_data');



/* work report */

Route::get('Work_report','Work_report@index');

Route::post('Work_report/get_search_data','Work_report@get_search_data');



/* Detail Work Report */

Route::get('Detail_work_report','Detail_work_report@index');

Route::post('Detail_work_report/get_search_data','Detail_work_report@get_search_data');



/*  report routes end  */



/* Party Master route */

Route::get('Party_master','Party_master@index');

Route::get('Party_master/add','Party_master@add');

Route::post('Party_master/insert','Party_master@insert');

Route::get('Party_master/edit/{id}','Party_master@edit');

Route::post('Party_master/update','Party_master@update');

Route::get('Party_master/get_all_party','Party_master@get_all_party');

Route::get('Party_master/delete/{id}','Party_master@delete');



/* Check Print */

Route::get('Check_print','Check_print@index');

Route::get('Check_print/add','Check_print@add');

Route::post('Check_print/insert','Check_print@insert');

Route::get('Check_print/edit/{id}','Check_print@edit');

Route::post('Check_print/update','Check_print@update');

Route::get('Check_print/get_check_detail','Check_print@get_check_detail');

Route::get('Check_print/delete/{id}','Check_print@delete');



/* SMS Format by Sneha Doso */

Route::get('SmsFormat','SmsFormat@index');

Route::post('SmsFormat/update','SmsFormat@update');

Route::post('/get_project_city','Follow_up@get_project_city');

