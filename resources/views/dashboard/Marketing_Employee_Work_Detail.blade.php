@extends('template.template')
@section('content')
<?php
//error_reporting(0);
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1> Employee Details</h1>
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('Dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Employee Details</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            @if(session()->has('success'))
            <span class="7">
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
                    <strong>{{ session()->get('success') }}</strong>
                </div>
            </span>
            @endif
            @if(session()->has('error'))
            <span class="7">
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
                    <strong>{{ session()->get('error') }}</strong>
                </div>
            </span>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="box box-warning">
            <div class="box-header">
                <b>Employee Name: {{ $emp_work_detail->name }}</b>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr align="center">
                            <th>Previous Pending Inquiry</th>
                            <th>Inquiry Entry</th>
                            <th>Inquiry Allot</th>
                            <th>Calling</th>
                            <th>Quotation Generate</th>
                            <th>Revise Quotation</th>
                            <th>Cancel Inquiry</th>
                            <th>Total Pending Inquiry</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr align="center" id="value_row">
                            <td><img src="{{ URL::asset('external/select2/select2-spinner.gif')}}" /></td>
                            <td><img src="{{ URL::asset('external/select2/select2-spinner.gif')}}" /></td>
                            <td><img src="{{ URL::asset('external/select2/select2-spinner.gif')}}" /></td>
                            <td><img src="{{ URL::asset('external/select2/select2-spinner.gif')}}" /></td>
                            <td><img src="{{ URL::asset('external/select2/select2-spinner.gif')}}" /></td>
                            <td><img src="{{ URL::asset('external/select2/select2-spinner.gif')}}" /></td>
                            <td><img src="{{ URL::asset('external/select2/select2-spinner.gif')}}" /></td>
                            <td><img src="{{ URL::asset('external/select2/select2-spinner.gif')}}" /></td>
                        </tr>
                    </tbody>
                    <?php
                    /*<tbody>
                        <tr>
                            <td>{{$previous_pending_inq}}</td>
                            <td>{{$inquiry_total_per_day}}</td>
                            <td>{{$inquiry_allot}}</td>
                            <td>{{$call_inquiry}}</td>
                            <td>{{$generate_quot}}</td>
                            <td>{{ $total_revise_quotation }}</td>
                            <td>{{ $cancel_inq }}</td>
                            <td>{{ $total_pending_inq }}</td>
                        </tr>
                    </tbody>*/
                            ?>
                </table>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
<?php
$marketing_employee_details = URL::action('Dashboard@marketing_employee_details');
?>
<script>
    $(document).ready(function () {
        //setInterval(sendRequest, 3000);
        previous_pending_inq();
        inquiry_total_per_day();
        inquiry_allot();
        call_inquiry();
        generate_quot();
        total_revise_quotation();
        cancel_inq();
        total_pending_inq();
    });
    var zone_id = '{{$emp_work_detail->zone_id}}';
    function previous_pending_inq() {
        $.ajax({
            type: "POST",
            url: "<?php echo $marketing_employee_details; ?>",
            data: {"_token": "{{ csrf_token() }}",'user_id': '{{ $user_id }}','zone_id': zone_id,'type': 'previous_pending_inq'},
            success: function (res)
            {
                jQuery('#value_row').find('td:eq(0)').text(res.success);
                //console.log(res);//value_row
            }
        });
    }
    function inquiry_total_per_day() {
        $.ajax({
            type: "POST",
            url: "<?php echo $marketing_employee_details; ?>",
            data: {"_token": "{{ csrf_token() }}",'user_id': '{{ $user_id }}','zone_id': zone_id,'type': 'inquiry_total_per_day'},
            success: function (res)
            {
                jQuery('#value_row').find('td:eq(1)').text(res.success);
                //console.log(res);//value_row
            }
        });
    }
    function inquiry_allot() {
        $.ajax({
            type: "POST",
            url: "<?php echo $marketing_employee_details; ?>",
            data: {"_token": "{{ csrf_token() }}",'user_id': '{{ $user_id }}','zone_id': zone_id,'type': 'inquiry_allot'},
            success: function (res)
            {
                jQuery('#value_row').find('td:eq(2)').text(res.success);
                //console.log(res);//value_row
            }
        });
    }
    function call_inquiry() {
        $.ajax({
            type: "POST",
            url: "<?php echo $marketing_employee_details; ?>",
            data: {"_token": "{{ csrf_token() }}",'user_id': '{{ $user_id }}','zone_id': zone_id,'type': 'call_inquiry'},
            success: function (res)
            {
                jQuery('#value_row').find('td:eq(3)').text(res.success);
                //console.log(res);//value_row
            }
        });
    }
    function generate_quot() {
        $.ajax({
            type: "POST",
            url: "<?php echo $marketing_employee_details; ?>",
            data: {"_token": "{{ csrf_token() }}",'user_id': '{{ $user_id }}','zone_id': zone_id,'type': 'generate_quot'},
            success: function (res)
            {
                jQuery('#value_row').find('td:eq(4)').text(res.success);
                //console.log(res);//value_row
            }
        });
    }
    function total_revise_quotation() {
        $.ajax({
            type: "POST",
            url: "<?php echo $marketing_employee_details; ?>",
            data: {"_token": "{{ csrf_token() }}",'user_id': '{{ $user_id }}','zone_id': zone_id,'type': 'total_revise_quotation'},
            success: function (res)
            {
                jQuery('#value_row').find('td:eq(5)').text(res.success);
                //console.log(res);//value_row
            }
        });
    }
    function cancel_inq() {
        $.ajax({
            type: "POST",
            url: "<?php echo $marketing_employee_details; ?>",
            data: {"_token": "{{ csrf_token() }}",'user_id': '{{ $user_id }}','zone_id': zone_id,'type': 'cancel_inq'},
            success: function (res)
            {
                jQuery('#value_row').find('td:eq(6)').text(res.success);
                //console.log(res);//value_row
            }
        });
    }
    function total_pending_inq() {
        $.ajax({
            type: "POST",
            url: "<?php echo $marketing_employee_details; ?>",
            data: {"_token": "{{ csrf_token() }}",'user_id': '{{ $user_id }}','zone_id': zone_id,'type': 'total_pending_inq'},
            success: function (res)
            {
                jQuery('#value_row').find('td:eq(7)').text(res.success);
                //console.log(res);//value_row
            }
        });
    }
</script>
@endsection
