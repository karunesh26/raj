@extends('template.template')
@section('content')
<?php
//error_reporting(0);
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1> Sales Employee Details</h1>
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('Dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Sales Employee Details</li>
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
                        <tr>
                            <th>Previous Pending Follow-Up</th>
                            <th>Allotment</th>
                            <th>Follow-Up</th>
                            <th>Total Follow-Up</th>
                            <th>Pending Follow-Up</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr align="center" id="value_row">
                            <td><img src="{{ URL::asset('external/select2/select2-spinner.gif')}}" /></td>
                            <td><img src="{{ URL::asset('external/select2/select2-spinner.gif')}}" /></td>
                            <td><img src="{{ URL::asset('external/select2/select2-spinner.gif')}}" /></td>
                            <td><img src="{{ URL::asset('external/select2/select2-spinner.gif')}}" /></td>
                            <td><img src="{{ URL::asset('external/select2/select2-spinner.gif')}}" /></td>
                        </tr>
                        <?php
                        /*<tr align="center">
                            <td>{{ $previous_pending_followup }}</td>
                            <td>{{ $allotment_total }}</td>
                            <td>{{ $total_follow_up }}</td>
                            <td>{{ $allotment_total+$total_follow_up }}</td>
                            <td>{{ $pending_follow_up }}</td>
                        </tr>*/
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
<?php
    $Sales_Employee_Work_Detail = URL::action('Dashboard@Sales_Employee_Work_Detail');
?>
<script>
    $(document).ready(function () {
        //setInterval(sendRequest, 3000);
        previous_pending_followup();
        allotment_total();
        pending_follow_up();
    });
    var zone_id = '{{$emp_work_detail->zone_id}}';
    function previous_pending_followup() {
        $.ajax({
            type: "POST",
            url: "<?php echo $Sales_Employee_Work_Detail; ?>",
            data: {"_token": "{{ csrf_token() }}",'user_id': '{{ $user_id }}','zone_id': zone_id,'type': 'previous_pending_followup'},
            success: function (res)
            {
                jQuery('#value_row').find('td:eq(0)').text(res.success);
                //console.log(res);//value_row
            }
        });
    }
    function allotment_total() {
        $.ajax({
            type: "POST",
            url: "<?php echo $Sales_Employee_Work_Detail; ?>",
            data: {"_token": "{{ csrf_token() }}",'user_id': '{{ $user_id }}','zone_id': zone_id,'type': 'allotment_total'},
            success: function (res)
            {
                total_follow_up();
                jQuery('#value_row').find('td:eq(1)').text(res.success);
                //console.log(res);//value_row
            }
        });
    }
    function total_follow_up() {
        $.ajax({
            type: "POST",
            url: "<?php echo $Sales_Employee_Work_Detail; ?>",
            data: {"_token": "{{ csrf_token() }}",'user_id': '{{ $user_id }}','zone_id': zone_id,'type': 'total_follow_up'},
            success: function (res)
            {
                jQuery('#value_row').find('td:eq(2)').text(res.success);
                jQuery('#value_row').find('td:eq(3)').text(Number(res.success)+Number(jQuery('#value_row').find('td:eq(1)').text()));
                //console.log(res);//value_row
            }
        });
    }
    function pending_follow_up() {
        $.ajax({
            type: "POST",
            url: "<?php echo $Sales_Employee_Work_Detail; ?>",
            data: {"_token": "{{ csrf_token() }}",'user_id': '{{ $user_id }}','zone_id': zone_id,'type': 'pending_follow_up'},
            success: function (res)
            {
                jQuery('#value_row').find('td:eq(4)').text(res.success);
                //console.log(res);//value_row
            }
        });
    }
</script>
@endsection