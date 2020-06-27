@extends('template.template')

@section('content')
<?php
$send_mail_url = URL::action($controller_name . '@send_mail');
$send_revise_mail_url = URL::action($controller_name . '@send_revise_mail');
$get_customer_email = URL::action($controller_name . '@get_customer_email');
$get_customer_mobile = URL::action($controller_name . '@get_customer_mobile');
$send_reminder_mail_url = URL::action($controller_name . '@send_reminder_mail');
$send_address_url = URL::action($controller_name . '@send_address');
$remark_quotation_url = URL::action($controller_name . '@remark_quotation');
$get_remark_url = URL::action($controller_name . '@get_remark');
$get_inq_pending_url = URL::action($controller_name . '@get_inq_pending');
$get_inq_quotation_url = URL::action($controller_name . '@get_inq_quotation');
$get_inq_revise_url = URL::action($controller_name . '@get_inq_revise');
$get_inq_cancel_url = URL::action($controller_name . '@get_inq_cancel');
$get_inq_delete_url = URL::action($controller_name . '@get_inq_delete');
$send_sms_url = URL::action($controller_name . '@send_sms');
?>
<style>
    .dataTables_processing{
        color:blue;
        text-align:center;
        font-size:20px;
    }
</style>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1> <?php echo $msgName; ?> Details</h1>
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('Dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo $msgName; ?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            @if(session()->has('success'))
            <span class="7"><div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong>
                        {{ session()->get('success') }}
                    </strong></div></span>
            @endif

            @if(session()->has('error'))
            <span class="7"><div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong>
                        {{ session()->get('error') }}
                    </strong></div></span>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#new" data-toggle="tab" >Pending Inquiry</a></li>
                    <li class=""><a id="quotation_tab_click" href="#quatation" data-toggle="tab" >Quotation</a></li>
                    <li class=""><a id="revise_quotation_tab_click" href="#revise_quatation" data-toggle="tab" >Revise Quotation</a></li>
                    <li class=""><a id="canceled_inquiry_tab_click" href="#delete_inquiry" data-toggle="tab" > Canceled Inquiry</a></li>
                    <li class=""><a id="delete_inquiry_tab_click" href="#remove_inquiry" data-toggle="tab" > Delete Inquiry</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane  active" id="new">
                        <div class="box box-warning">
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="inquiry_pending" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Sr</th>
                                                <th>Inquiry No.</th>
                                                <th>Inquiry Id</th>
                                                <th>Inquiry Color</th>
                                                <th>Inquiry Date</th>
                                                <th>Inquiry Person</th>
                                                <th>Customer Name</th>
                                                <th>Customer Mobile</th>
                                                <th>Customer Email</th>
                                                <th width="35%">Manage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="quatation">
                        <div class="box box-warning">
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="inquiry_quotation" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Sr</th>
                                                <th>Inquiry No.</th>
                                                <th>Inquiry Id</th>
                                                <th>Inquiry Date</th>
                                                <th>Quotation No.</th>
                                                <th>Quotation Date.</th>
                                                <th>Customer Name</th>
                                                <th>Quotation By</th>
                                                <th>No Of Send</th>
                                                <th>No Of Revise</th>
                                                <th>Manage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane " id="revise_quatation">
                        <div class="box box-warning">
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="revise_inquiry_quotation" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Sr</th>
                                                <th>Quatation No</th>
                                                <th>Inquiry Id</th>
                                                <th>Revise Id</th>
                                                <th>Rivise Quatation No</th>
                                                <th>Rivise Quatation Date</th>
                                                <th>Manage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane " id="delete_inquiry">
                        <div class="box box-warning">
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="inquiry_cancel" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Sr</th>
                                                <th>Inquiry No.</th>
                                                <th>Inquiry Date</th>
                                                <th>Inquiry Person</th>
                                                <th>Customer Name</th>
                                                <th>Customer Mobile</th>
                                                <th>Customer Email</th>
                                                <th>Cancel Date</th>
                                                <th>Canceled By</th>
                                                <th>Manage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane " id="remove_inquiry">
                        <div class="box box-warning">
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="inquiry_delete" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Sr</th>
                                                <th>Inquiry No.</th>
                                                <th>Inquiry Date</th>
                                                <th>Inquiry Person</th>
                                                <th>Customer Name</th>
                                                <th>Customer Mobile</th>
                                                <th>Customer Email</th>
                                                <th>Delete By</th>
                                                <th>Manage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="send_revise_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        Send Revise Quatation
                    </h4>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="tab-pane ">
                        <form class="form-horizontal" id="send_revise_form_data">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <input type="hidden" name="revise_id" id="revise_id" />
                                <input type="hidden" name="inquiry_id" id="inquiry_id" />
                                <div class="form-group col-sm-12">
                                    <label class=" col-sm-3 control-label">Title<span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <select multiple name="title[]" id="title" class="select2" style="width:100%;">
                                            <?php
                                            foreach ($catalog as $k => $v) {
                                                ?>
                                                <option value="<?php echo $v->id; ?>"><?php echo $v->catalog_title; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label class=" col-sm-3 control-label" >Email ID<span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <select name="send_email_id" id="send_revice_email_id" class="select2 send_email_id" style="width:100%;">
                                            <option value="">Select</option>

                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-default" id="product">Send</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="send_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        Send Quatation
                    </h4>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="tab-pane ">
                        <form class="form-horizontal" id="send_form_data">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <input type="hidden" name="inquiry_id" id="inquiry_id" />
                                <div class="form-group col-sm-12">
                                    <label class=" col-sm-3 control-label"  >Title<span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <select multiple name="title[]" id="title" class="select2" style="width:100%;">
                                            <?php
                                            foreach ($catalog as $k => $v) {
                                                ?>
                                                <option value="<?php echo $v->id; ?>"><?php echo $v->catalog_title; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label class=" col-sm-3 control-label" >Email ID<span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <select name="send_email_id" id="send_email_id" class="select2 send_email_id" style="width:100%;">
                                            <option value="">Select</option>

                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-default" id="product">Send</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="mail_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        Send Reminder
                    </h4>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="tab-pane ">
                        <form class="form-horizontal" id="send_reminder_form">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <input type="hidden" name="inquiry_id" id="inquiry_id" />

                                <div class="form-group col-sm-12">
                                    <label class=" col-sm-3 control-label" >Email ID<span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <select name="reminder_email" id="reminder_email" class="select2" style="width:100%;">
                                            <option value="">Select</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label class=" col-sm-3 control-label"  >Title</label>
                                    <div class="col-sm-9">
                                        <select multiple name="title[]" id="title" class="select2" style="width:100%;">
<?php
foreach ($catalog as $k => $v) {
    ?>
                                                <option value="<?php echo $v->id; ?>"><?php echo $v->catalog_title; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-default" id="product">Send</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="send_address_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        Send Address
                    </h4>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="tab-pane ">
                        {!! Form::open(array('method' => 'post' , 'files' => true ,'id'=>"send_address_form",'name'=>"send_address_form",'class'=>"send_address_form"))!!}
                        {{ csrf_field() }}

                        <div class="box-body">
                            <div class="form-group col-sm-12">
                                <label class=" col-sm-3 control-label" >Mobile No.<span class="required">*</span></label>
                                <div class="col-sm-9">
                                    <select multiple name="customer_mno[]" id="customer_mno" class="select2" style="width:100%;">
                                        <option value="">Select</option>

                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class=" col-sm-3 control-label" >Office<span class="required">*</span></label>
                                <div class="col-sm-9">
                                    @foreach($address_master as $k=>$v)
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" id="follow_up_address" name="follow_up_address[]" value="{{ $v->id }}">
                                            {{ $v->office_name }}
                                        </label>
                                    </div>
                                    @endforeach
                                    <label id="follow_up_address[]-error" class="error" for="follow_up_address[]"></label>
                                </div>
                            </div>
                            <label id="follow_up_address-error" class="error" for="follow_up_address"></label>
                            <div class="form-group col-sm-12">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button type="submit" class="btn btn-default" id="product">Send</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="remark_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        Remark
                    </h4>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="tab-pane ">
                        <form class="form-horizontal" id="remark_quotation_form">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <input type="hidden" name="inquiry_id" id="inquiry_id" />

                                <div class="form-group col-sm-12">
                                    <label class=" col-sm-3 control-label" >Remark<span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <textarea name="remark_quotation" id="remark_quotation" placeholder="Enter Remarks" class="form-control " ></textarea>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12">
                                    <label class=" col-sm-3 control-label" >Date<span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" disabled value='<?php echo date("d-m-Y h:i:s"); ?>'>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-default" id="product">Save</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12">
                                    <div class="col-sm-12" id="remark_data">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="send_sms_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        Send SMS
                    </h4>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="tab-pane ">
                        {!! Form::open(array('method' => 'post' , 'files' => true ,'id'=>"send_sms_form",'name'=>"send_sms_form",'class'=>"send_sms_form"))!!}
                        {{ csrf_field() }}
                        <input type="hidden" name="inquiry_id" id="inquiry_id" />
                        <div class="box-body">
                            <div class="form-group col-sm-12">
                                <label class=" col-sm-3 control-label" >Mobile No.<span class="required">*</span></label>
                                <div class="col-sm-9">
                                    <select multiple name="customer_mno[]" id="customer_mno_sms" class="select2" style="width:100%;">

                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-12">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button type="submit" class="btn btn-default" id="product">Send</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function () {


        $('.datatable').DataTable({
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': true,
            "pageLength": 25,
        })


        /* var pending_inq = $("#inquiry_pending").DataTable({
         processing:true,
         "pageLength":  100,
         "pagingType": "full_numbers",
         "ordering": false,
         "sDom": '<"H"lfrp>t<"F"ip>',
         serverside:true,
         columns:[
         {data:null},
         {data:"inquiry_no"},
         {data:"inquiry_id", "visible":false},
         {data:"inquiry_color", "visible":false},
         {data:"inquiry_date"},
         {data:"inquiry_person"},
         {data:"customer_name"},
         {data:"customer_mobile"},
         {data:"customer_email"},
         {data:"actions","orderable":false}
         ],
         autoWidth:false,
         "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
         $(nRow).css('color',aData.inquiry_color);
         },
         ajax:"{{ $get_inq_pending_url }}"
         }).on( 'order.dt search.dt', function () {
         $("#inquiry_pending").DataTable().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
         cell.innerHTML = i+1;
         } );
         
         
         }).draw(); */

        $.fn.dataTable.ext.errMode = 'none';
        var pending_inq = $('#inquiry_pending').DataTable({
            "processing": true,
            "pageLength": 25,
            "serverSide": true,
            "pagingType": "full_numbers",
            "sDom": '<"H"lfrp>t<"F"ip>',
            "ajax": {
                "url": "{{ $get_inq_pending_url }}",
                "dataType": "json",
                "type": "POST",
                "data": {_token: "{{csrf_token()}}"}
            },
            "columns": [
                {"data": "id"},
                {"data": "inquiry_no"},
                {"data": "inquiry_id", "visible": false},
                {"data": "inquiry_color", "visible": false},
                {"data": "inquiry_date"},
                {"data": "inquiry_person"},
                {"data": "name"},
                {"data": "mobile"},
                {"data": "email"},
                {"data": "action"},
            ],
            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).css('color', aData.inquiry_color);
            },
            "columnDefs": [{
                    "targets": [0, 9],
                    "orderable": false
                }],
        });

        var quotation_inq = '';
        $('body').on('click', '#quotation_tab_click', function () {
            if (!$.fn.dataTable.isDataTable('#inquiry_quotation'))
            {
                quotation_inq = $('#inquiry_quotation').DataTable({
                    "processing": true,
                    "pageLength": 25,
                    "serverSide": true,
                    "pagingType": "full_numbers",
                    "sDom": '<"H"lfrp>t<"F"ip>',
                    "ajax": {
                        "url": "{{ $get_inq_quotation_url }}",
                        "dataType": "json",
                        "type": "POST",
                        "data": {_token: "{{csrf_token()}}"}
                    },
                    "columns": [
                        {"data": "id"},
                        {"data": "inquiry_no"},
                        {"data": "inquiry_id", "visible": false},
                        {"data": "inquiry_date"},
                        {"data": "quotation_no"},
                        {"data": "quotation_date"},
                        {"data": "customer_name"},
                        {"data": "quotation_by"},
                        {"data": "no_of_send"},
                        {"data": "no_of_revise"},
                        {"data": "action"},
                    ],
                    "columnDefs": [{
                            "targets": [0, 10],
                            "orderable": false
                        }],
                });
            }
        });

        var revise_quot = '';
        $('body').on('click', '#revise_quotation_tab_click', function () {
            if (!$.fn.dataTable.isDataTable('#revise_inquiry_quotation'))
            {
                /*revise_quot = $("#revise_inquiry_quotation").DataTable({
                 processing:true,
                 "pageLength":  100,
                 "pagingType": "full_numbers",
                 "ordering": false,
                 "sDom": '<"H"lfrp>t<"F"ip>',
                 serverside:true,
                 columns:[
                 {data:null},
                 {data:"quotation_no"},
                 {data:"revise_id", "visible":false},
                 {data:"inquiry_id", "visible":false},
                 {data:"revise_quotation_no"},
                 {data:"revise_quot_date"},
                 {data:"actions","orderable":false}
                 ],
                 autoWidth:false,
                 ajax:"{{ $get_inq_revise_url }}"
                 }).on( 'order.dt search.dt', function () {
                 $("#revise_inquiry_quotation").DataTable().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                 cell.innerHTML = i+1;
                 } );
                 }).draw(); */

                revise_quot = $('#revise_inquiry_quotation').DataTable({
                    "processing": true,
                    "pageLength": 25,
                    "serverSide": true,
                    "pagingType": "full_numbers",
                    "sDom": '<"H"lfrp>t<"F"ip>',
                    "ajax": {
                        "url": "{{ $get_inq_revise_url }}",
                        "dataType": "json",
                        "type": "POST",
                        "data": {_token: "{{csrf_token()}}"}
                    },
                    "columns": [
                        {"data": "id"},
                        {"data": "quotation_no"},
                        {"data": "revise_id", "visible": false},
                        {"data": "inquiry_id", "visible": false},
                        {"data": "revise_quotation_no"},
                        {"data": "revise_quot_date"},
                        {"data": "action"},
                    ],
                    "columnDefs": [{
                            "targets": [0, 6],
                            "orderable": false
                        }],
                });
            }
        });

        $('body').on('click', '#canceled_inquiry_tab_click', function () {
            if (!$.fn.dataTable.isDataTable('#inquiry_cancel'))
            {
                $('#inquiry_cancel').DataTable({
                    "processing": true,
                    "pageLength": 25,
                    "serverSide": true,
                    "pagingType": "full_numbers",
                    "sDom": '<"H"lfrp>t<"F"ip>',
                    "ajax": {
                        "url": "{{ $get_inq_cancel_url }}",
                        "dataType": "json",
                        "type": "POST",
                        "data": {_token: "{{csrf_token()}}"}
                    },
                    "columns": [
                        {"data": "id"},
                        {"data": "inquiry_no"},
                        {"data": "inquiry_date"},
                        {"data": "inquiry_person"},
                        {"data": "customer_name"},
                        {"data": "customer_mobile"},
                        {"data": "customer_email"},
                        {"data": "cancel_date"},
                        {"data": "cancel_by"},
                        {"data": "action"},
                    ],
                    "columnDefs": [{
                            "targets": [0, 9],
                            "orderable": false
                        }],
                });
            }
        });

        $('body').on('click', '#delete_inquiry_tab_click', function () {
            if (!$.fn.dataTable.isDataTable('#inquiry_delete'))
            {
                $("#inquiry_delete").DataTable({
                    processing: true,
                    "pageLength": 25,
                    "pagingType": "full_numbers",
                    "ordering": false,
                    "sDom": '<"H"lfrp>t<"F"ip>',
                    serverside: true,
                    columns: [
                        {data: null},
                        {data: "inquiry_no"},
                        {data: "inquiry_date"},
                        {data: "inquiry_person"},
                        {data: "customer_name"},
                        {data: "customer_mobile"},
                        {data: "customer_email"},
                        {data: "delete_by"},
                        {data: "actions", "orderable": false}
                    ],
                    autoWidth: false,
                    ajax: "{{ $get_inq_delete_url }}"
                }).on('order.dt search.dt', function () {
                    $("#inquiry_delete").DataTable().column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();
            }
        });

        $('#send_form_data').validate({
            rules:
                    {
                        send_email_id: {required: true, },
                        'title[]': {required: true, },
                    },
            messages:
                    {
                        send_email_id: {required: "Please Select Email "},
                        'title[]': {required: "Please Select Catelog Title"},
                    },
            errorPlacement: function (error, element) {
                error.appendTo(element.parent("div"));
            },
        });
        $('#send_revise_form_data').validate({
            rules:
                    {
                        send_email_id: {required: true, },
                        'title[]': {required: true, },
                    },
            messages:
                    {
                        send_email_id: {required: "Please Select Email "},
                        'title[]': {required: "Please Select Catelog Title"},
                    },
            errorPlacement: function (error, element) {
                error.appendTo(element.parent("div"));
            },
        });

        $('#remark_quotation_form').validate({
            rules:
                    {
                        remark_quotation: {required: true, },
                    },
            messages:
                    {
                        remark_quotation: {required: "Please Enter Remark. "},
                    },
            errorPlacement: function (error, element) {
                error.appendTo(element.parent("div"));
            },
        });

        $('body').on('click', "#quotation_remark", function (e) {
            var th = $(this);
            var inq_id = pending_inq.row($(this).parents('tr')).data()["inquiry_id"];
            $('#remark_quotation_form').find('#inquiry_id').empty().val(inq_id);

            $.ajax({
                type: 'POST',
                url: '{{$get_remark_url}}',
                data: {"_token": "{{ csrf_token() }}", inq_id: inq_id},
                success: function (response)
                {
                    $('#remark_quotation_form').find('#remark_data').empty().html(response);
                },

            });


        });

        $('body').on('click', '#send_sms_quot', function (e) {
            var inq_id = quotation_inq.row($(this).parents('tr')).data()["inquiry_id"];
            $('#send_sms_form').find('#inquiry_id').empty().val(inq_id);
        });

        $('body').on('click', '#send_sms_res', function (e) {
            var inq_id = revise_quot.row($(this).parents('tr')).data()["inquiry_id"];
            $('#send_sms_form').find('#inquiry_id').empty().val(inq_id);
        });

        $('#remark_quotation_form').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            var form = this;

            if ($("#remark_quotation_form").valid())
            {
                $(':input[type="submit"]').prop('disabled', true);

                $('.blockUI').show();
                $.ajax({
                    type: 'post',
                    url: '{{$remark_quotation_url}}',
                    data: formData,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    success: function (response)
                    {
                        if (response[0] == 'success')
                        {
                            alert('Remark Add Successfully.');
                            $(':input[type="submit"]').prop('disabled', false);
                            $('#remark_quotation').val('');
                            $('#remark_model').modal('hide');
                            $('.blockUI').hide();
                        } else
                        {
                            alert('Remark Not Successfully Add.');
                            $(':input[type="submit"]').prop('disabled', false);
                            $('.blockUI').hide();
                        }
                    },
                    error: function ()
                    {
                        alert('Remark Not Successfully Add.');
                        $(':input[type="submit"]').prop('disabled', false);
                        $('.blockUI').hide();
                    }
                });
            } else
            {
                return false;
            }
        });



        $('body').on('click', "#send_reminder_mail", function (e) {
            var th = $(this);
            var inq_id = pending_inq.row($(this).parents('tr')).data()["inquiry_id"];
            $('#send_form_data').find('#inquiry_id').empty().val(inq_id);
            $('#send_reminder_form').find('#inquiry_id').empty().val(inq_id);
            $.ajax({
                type: 'post',
                url: '{{$get_customer_email}}',
                data: {"_token": "{{ csrf_token() }}", inq_id: inq_id},
                success: function (response)
                {
                    $("#send_email_id").empty().append().html(response).trigger('change.select2');
                    $("#reminder_email").empty().append().html(response).trigger('change.select2');
                },

            });
        });
        $('body').on('click', "#send_quotation", function (e) {
            var th = $(this);
            var inq_id = quotation_inq.row($(this).parents('tr')).data()["inquiry_id"];
            $('#send_form_data').find('#inquiry_id').empty().val(inq_id);
            $('#send_reminder_form').find('#inquiry_id').empty().val(inq_id);
            $.ajax({
                type: 'post',
                url: '{{$get_customer_email}}',
                data: {"_token": "{{ csrf_token() }}", inq_id: inq_id},
                success: function (response)
                {
                    $("#send_email_id").empty().append().html(response).trigger('change.select2');
                    $("#reminder_email").empty().append().html(response).trigger('change.select2');
                },

            });
        });
        $('body').on('click', "#send_revise_quotation", function (e) {
            var th = $(this);
            var revise_id = revise_quot.row($(this).parents('tr')).data()["revise_id"];
            var inq_id = revise_quot.row($(this).parents('tr')).data()["inquiry_id"];
            $('#send_revise_form_data').find('#inquiry_id').empty().val(inq_id);
            $('#send_revise_form_data').find('#revise_id').empty().val(revise_id);
            $.ajax({
                type: 'post',
                url: '{{$get_customer_email}}',
                data: {"_token": "{{ csrf_token() }}", inq_id: inq_id},
                success: function (response)
                {
                    $("#send_revice_email_id").empty().append().html(response).trigger('change.select2');
                    $("#reminder_email").empty().append().html(response).trigger('change.select2');
                },

            });
        });

        $('body').on('click', "#send_sms_res", function (e) {
            var th = $(this);
            var inq_id = revise_quot.row($(this).parents('tr')).data()["inquiry_id"];

            $.ajax({
                type: 'post',
                url: '{{$get_customer_mobile}}',
                data: {"_token": "{{ csrf_token() }}", inq_id: inq_id},
                success: function (response)
                {
                    $("#customer_mno_sms").empty().append().html(response).trigger('change.select2');
                },

            });
        });

        $('body').on('click', "#send_sms_quot", function (e) {
            var th = $(this);
            var inq_id = quotation_inq.row($(this).parents('tr')).data()["inquiry_id"];

            $.ajax({
                type: 'post',
                url: '{{$get_customer_mobile}}',
                data: {"_token": "{{ csrf_token() }}", inq_id: inq_id},
                success: function (response)
                {
                    $("#customer_mno_sms").empty().append().html(response).trigger('change.select2');
                },

            });
        });

        $('body').on('click', "#send_address", function (e) {
            var th = $(this);
            var inq_id = pending_inq.row($(this).parents('tr')).data()["inquiry_id"];

            $.ajax({
                type: 'post',
                url: '{{$get_customer_mobile}}',
                data: {"_token": "{{ csrf_token() }}", inq_id: inq_id},
                success: function (response)
                {
                    $("#customer_mno").empty().append().html(response).trigger('change.select2');
                },

            });
        });
        $('body').on('click', "#send_address_quot", function (e) {
            var th = $(this);
            var inq_id = quotation_inq.row($(this).parents('tr')).data()["inquiry_id"];

            $.ajax({
                type: 'post',
                url: '{{$get_customer_mobile}}',
                data: {"_token": "{{ csrf_token() }}", inq_id: inq_id},
                success: function (response)
                {
                    $("#customer_mno").empty().append().html(response).trigger('change.select2');
                },

            });
        });


        $('#send_form_data').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            var form = this;

            if ($("#send_form_data").valid())
            {
                $(':input[type="submit"]').prop('disabled', true);

                $('.blockUI').show();
                $.ajax({
                    type: 'post',
                    url: '{{$send_mail_url}}',
                    data: formData,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    success: function (response)
                    {
                        if (response[0] == 'success')
                        {
                            alert('Mail Sent Successfully');
                            $('#title').select2().val('').trigger('change');
                            $(':input[type="submit"]').prop('disabled', false);
                            $('#send_model').modal('hide');
                            $('.blockUI').hide();
                        } else
                        {
                            alert(response[0]);
                            $('#title').select2().val('').trigger('change');
                            $(':input[type="submit"]').prop('disabled', false);
                            $('#send_model').modal('hide');
                            $('.blockUI').hide();
                        }
                    },
                    error: function ()
                    {
                        alert(response[0]);
                        $('#title').select2().val('').trigger('change');
                        $(':input[type="submit"]').prop('disabled', false);
                        $('#send_model').modal('hide');
                        $('.blockUI').hide();
                    }
                });
            } else
            {
                return false;
            }
        });
        $('#send_revise_form_data').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            var form = this;

            if ($("#send_revise_form_data").valid())
            {
                $(':input[type="submit"]').prop('disabled', true);

                $('.blockUI').show();
                $.ajax({
                    type: 'post',
                    url: '{{$send_revise_mail_url}}',
                    data: formData,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    success: function (response)
                    {
                        if (response[0] == 'success')
                        {
                            alert('Mail Sent Successfully');
                            $('#title').select2().val('').trigger('change');
                            $(':input[type="submit"]').prop('disabled', false);
                            $('#send_model').modal('hide');
                            $('.blockUI').hide();
                        } else
                        {
                            alert(response[0]);
                            $('#title').select2().val('').trigger('change');
                            $(':input[type="submit"]').prop('disabled', false);
                            $('#send_model').modal('hide');
                            $('.blockUI').hide();
                        }
                    },
                    error: function ()
                    {
                        alert(response[0]);
                        $('#title').select2().val('').trigger('change');
                        $(':input[type="submit"]').prop('disabled', false);
                        $('#send_model').modal('hide');
                        $('.blockUI').hide();
                    }
                });
            } else
            {
                return false;
            }
        });

        $('#send_reminder_form').validate({
            rules:
                    {
                        reminder_email: {required: true, },
                    },
            messages:
                    {
                        reminder_email: {required: "Please Select Email"},
                    },
            errorPlacement: function (error, element) {
                error.appendTo(element.parent("div"));
            },
        });

        $('#send_reminder_form').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            var form = this;

            if ($("#send_reminder_form").valid())
            {
                $(':input[type="submit"]').prop('disabled', true);

                $('.blockUI').show();
                $.ajax({
                    type: 'post',
                    url: '{{$send_reminder_mail_url}}',
                    data: formData,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    success: function (response)
                    {
                        if (response[0] == 'success')
                        {
                            alert('Mail Sent Successfully');
                            $('#title').select2().val('').trigger('change');
                            $(':input[type="submit"]').prop('disabled', false);
                            $('#send_model').modal('hide');
                            $('.blockUI').hide();
                        } else
                        {
                            alert(response[0]);
                            $('#title').select2().val('').trigger('change');
                            $(':input[type="submit"]').prop('disabled', false);
                            $('#send_model').modal('hide');
                            $('.blockUI').hide();
                        }
                    },
                    error: function ()
                    {
                        alert(response[0]);
                        $('#title').select2().val('').trigger('change');
                        $(':input[type="submit"]').prop('disabled', false);
                        $('#send_model').modal('hide');
                        $('.blockUI').hide();
                    }
                });
            } else
            {
                return false;
            }
        });
        $.validator.setDefaults({ignore: ":hidden:not(.select2)"})
        $('#send_address_form').validate({
            rules: {
                'follow_up_address[]': {required: true, },
                'customer_mno[]': {required: true, },
            },
            messages: {
                'follow_up_address[]': {required: "Please Select Office Address", },
                'customer_mno[]': {required: "Please Select Mobile Number", },
            },
        });
        $('#send_address_form').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            var form = this;

            if ($("#send_address_form").valid())
            {
                $(':input[type="submit"]').prop('disabled', true);

                $('.blockUI').show();
                $.ajax({
                    type: 'post',
                    url: '{{$send_address_url}}',
                    data: formData,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    success: function (response)
                    {
                        if (response[0] == 'success')
                        {
                            alert('Address Send Successfully.');
                            $(':input[type="submit"]').prop('disabled', false);
                            $('#send_address_model').modal('hide');
                            $('.blockUI').hide();
                        } else
                        {
                            alert('Address Not Successfully Send.');
                            $(':input[type="submit"]').prop('disabled', false);
                            $('.blockUI').hide();
                        }
                    },
                    error: function ()
                    {
                        alert('Address Not Successfully Send.');
                        $(':input[type="submit"]').prop('disabled', false);
                        $('.blockUI').hide();
                    }
                });
            } else
            {
                return false;
            }
        });

        $('#send_sms_form').validate({
            rules: {
                'customer_mno[]': {required: true, },
            },
            messages: {
                'customer_mno[]': {required: "Please Select Mobile Number", },
            },
        });
        $('#send_sms_form').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            var form = this;

            if ($("#send_sms_form").valid())
            {
                $(':input[type="submit"]').prop('disabled', true);

                $('.blockUI').show();
                $.ajax({
                    type: 'post',
                    url: '{{$send_sms_url}}',
                    data: formData,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    success: function (response)
                    {
                        if (response[0] == 'success')
                        {
                            alert('SMS Send Successfully.');
                            $(':input[type="submit"]').prop('disabled', false);
                            $('#send_sms_model').modal('hide');
                            $('.blockUI').hide();
                        } else
                        {
                            alert('SMS Not Successfully Send.');
                            $(':input[type="submit"]').prop('disabled', false);
                            $('.blockUI').hide();
                        }
                    },
                    error: function ()
                    {
                        alert('SMS Not Successfully Send.');
                        $(':input[type="submit"]').prop('disabled', false);
                        $('.blockUI').hide();
                    }
                });
            } else
            {
                return false;
            }
        });

        $(document).on('click', '.confirm-delete', function (e) {
            e.preventDefault(); // Prevent the href from redirecting directly
            var linkURL = $(this).attr("href");
            warnBeforeDelete(linkURL);
        });

        function warnBeforeDelete(linkURL) {
            swal({
                title: "Are You Sure To Active This Inquiry?",
                text: "",
                type: "warning",
                showCancelButton: true
            }, function () {
                // Redirect the user
                window.location.href = linkURL;
            });
        }

        $(document).on('click', '.quotation-delete', function (e) {
            e.preventDefault();
            var linkURL = $(this).attr("href");
            swal({
                title: "Are You Sure To Delete This Quotation?",
                text: "",
                type: "warning",
                showCancelButton: true
            }, function () {
                window.location.href = linkURL;
            });
        });

        $(document).ajaxStart(function () {
            $(".blockUI").show();
        });

        $(document).ajaxStop(function () {
            $(".blockUI").hide();
        });

    });


</script>
@endsection
