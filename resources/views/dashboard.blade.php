@extends('template.template')
@section('content')
<?php
//error_reporting(0);
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1> Dashboard</h1>
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('Dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
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
    @if($role_id != 1)
    <div class="col-lg-12">
        <div class="box box-warning">
            <div class="col-xs-12">
                <h3>Work Detail</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped">
                    @if($role_id == 2 || $role_id == 3 || $role_id == 5)
                    <thead>
                        <tr>
                            <th colspan="4">Work Detail :-- {{ $emp_detail[0]->name }} </th>
                            <th colspan="4">Date :- {{ date('d-m-Y') }}</th>
                        </tr>
                        <tr>
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
                        <tr align="center">
                            <td>{{ $previous_pending_inq }}</td>
                            <td>{{ $inquiry_total_per_day }}</td>
                            <td>{{ $inquiry_allot }}</td>
                            <td>{{ $call_inquiry }}</td>
                            <td>{{ $generate_quot }}</td>
                            <td>{{ $total_revise_quotation }}</td>
                            <td>{{ $cancel_inq }}</td>
                            <td>{{ $total_pending_inq }}</td>
                        </tr>
                    </tbody>
                    @elseif($role_id == '4' || $role_id == '6' || $role_id == '11')
                    <thead>
                        <tr>
                            <th colspan="3">Work Detail :-- {{ $emp_detail[0]->name }} </th>
                            <th colspan="2">Date :- {{ date('d-m-Y') }}</th>
                        </tr>
                        <tr>
                            <th>Previous Pending Follow-Up</th>
                            <th>Allotment</th>
                            <th>Follow-Up</th>
                            <th>Total Follow-Up</th>
                            <th>Pending Follow-Up</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr align="center">
                            <td>{{ $previous_pending_followup }}</td>
                            <td>{{ $allotment_total }}</td>
                            <td>{{ $total_follow_up }}</td>
                            <td>{{ $allotment_total+$total_follow_up }}</td>
                            <td>{{ $pending_follow_up }}</td>
                        </tr>
                    </tbody>
                    @endif
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-md-6">
            <!--Marketing Employee Work Detail-->
            <div class="box box-warning">
                <div class="box-header">
                    <b>Marketing Employee Work Detail ON : {{ date('d-m-Y') }}</b>
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <td width="10">#</td>
                                <th>Employee Name</th>
                                <th width="10">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($emp_work_detail AS $key => $value)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>{{$value->name}}</td>
                                <td>
                                    <a href="{{ route('dashboard_employee',['type' => 'marketing','id' => Base64_encode($value->id)]) }}" target="_blank" title="More Detail" class="btn bg-olive btn-flat btn-sm"><i class="glyphicon glyphicon-eye-open icon-white"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <!--Marketing Employee Work Detail-->
            <div class="box box-warning">
                <div class="box-header">
                    <b>Sales Employee Work Detail ON : {{ date('d-m-Y') }}</b>
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <td width="10">#</td>
                                <th>Employee Name</th>
                                <th width="10">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales_emp_detail AS $key => $value)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>{{$value->name}}</td>
                                <td>
                                    <a href="{{ route('dashboard_employee',['type' => 'sales','id' => Base64_encode($value->id)]) }}" target="_blank" title="View" class="btn bg-olive btn-flat btn-sm" href="#"><i class="glyphicon glyphicon-eye-open icon-white"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="box box-warning">
        <div class="box-header">
            <b>Price / Technical Issue</b>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th width="5%">Sr. No.</th>
                        <th width="8%">Issue Date</th>
                        <th width="10%">Quotation Number</th>
                        <th width="15%">Client Name</th>
                        <th width="15%">Inquiry For</th>
                        <th width="20%">Remark</th>
                        <th width="10%">Follow-Up By</th>
                        <th width="10%">Allot To</th>
                        <th width="15%">Manage</th>
                    </tr>
                </thead>
                <tbody id="prise_issue_notify_tbody">
                    <tr>
                        <td colspan="9" style="text-align: center;"><img src="{{ URL::asset('external/select2/select2-spinner.gif')}}" /></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="box box-warning">
        <div class="col-xs-12">
            <h3>Revise</h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th width="5%">Sr. No.</th>
                        <th width="8%">Revise Date</th>
                        <th width="15%">Quotation Number</th>
                        <th width="20%">Client Name</th>
                        <th width="15%">Inquiry For</th>
                        <th width="15%">Remark</th>
                        <th width="10%">Follow-Up By</th>
                        <th width="10%">Allot To</th>
                        <th width="15%">Manage</th>
                    </tr>
                </thead>
                <tbody id="revise_notify_tbody">
                    <td colspan="9" style="text-align: center;"><img src="{{ URL::asset('external/select2/select2-spinner.gif')}}" /></td>
                </tbody>
            </table>
        </div>
    </div>
</section>
<!-- /.content -->
<?php
$prise_issue_notify = URL::action('Dashboard@prise_issue_notify');
$revise_notify_data = URL::action('Dashboard@revise_notify_data');
$zone_id = 0;
$employee_id = 0;
if ($role_id != 1) {
    $zone_id = 0;
    $employee_id = 0;
}
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.datatable').DataTable({
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': true,
            "pageLength": 100,
        })
    });
    $(document).ready(function () {
        //setInterval(sendRequest, 3000);
        prise_issue_notify();
    });
    function prise_issue_notify() {
        $.ajax({
            type: "POST",
            url: "<?php echo $prise_issue_notify; ?>",
            data: {"_token": "{{ csrf_token() }}",'zone_id': '{{$zone_id}}','employee_id': '{{$employee_id}}'},
            success: function (res)
            {
                jQuery('#prise_issue_notify_tbody').html(res.success);
                //revise_notify_data();
                //console.log(res);
            }
        });
    }
    function revise_notify_data() {
        $.ajax({
            type: "POST",
            url: "<?php echo $revise_notify_data; ?>",
            data: {"_token": "{{ csrf_token() }}",'zone_id': '{{$zone_id}}','employee_id': '{{$employee_id}}'},
            success: function (res)
            {
                jQuery('#revise_notify_tbody').html(res.success);
                console.log(res);
            }
        });
    }
</script>
@endsection
