@extends('template.template')

@section('content')
<?php
	$get_cearch_data_url = URL::action($controller_name.'@get_search_data');
?>

 <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> <?php echo $msgName;?> Details</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('Dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo $msgName;?></li>
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
                  <div class="box box-warning">
                    <div class="box-body">
						<div class="row">
							<div class="form-group col-md-12">
								<div class="col-sm-2 col-md-offset-2">
									{!! Form::label('Employee :') !!} <span class="required">*</span>
								</div>
								<div class="col-sm-4">
									<select name="employee" id="employee" class="select2" style="width:100%">
										<option value="">Select</option>
										@foreach($employee as $key=>$val)
											<option value="{{ $val->emp_id }}">{{ $val->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group col-md-12">
								<div class="col-sm-2 col-md-offset-2">
									{!! Form::label('Date :') !!}
								</div>
								<div class="col-sm-4">
									<input type="text" placeholder="Select From Date" name="date" readonly id="date" value={{ date('d-m-Y') }} class="form-control datepicker" >
								</div>
							</div>

							<div class="col-md-2 col-md-offset-5">
								<a id="search_btn" class="btn btn-success" style="margin-left:50px;" >Search</a>
							</div>
						</div>

						<br><div class="box-footer"></div>

						<div id="search_data">
							<table id="datatable" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>Sr No.</th>
										<th>Quotation No.</th>
										<th>Client Name</th>
										<th>Mobile No</th>
										<th>State</th>
										<th>Inquiry For</th>
										<th>Inquiry Source</th>
										<th>Quotation Time</th>
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
    </section>
<script>
 $(document).ready(function () {
	$('.datepicker').datepicker({
      autoclose: true,
	  format: 'dd-mm-yyyy',
    });

	$('body').on('click','#search_btn', function(){
		var employee = $('#employee').val();
		var date = $('#date').val();

		if(employee == '')
		{
			alert('Please Select Employee');
			return false;
		}
		if(date == '')
		{
			alert("Please Select Date");
			return false;
		}

		$('.blockUI').show();
		$.ajax({
			type: "POST",
			url: "<?php echo $get_cearch_data_url;?>",
			data: { "_token": "{{ csrf_token() }}",employee:employee,date:date},
			success:function(res)
			{
				$('.blockUI').hide();
				$('#search_data').empty().html(res);
			}
		});
	});


 });
</script>

@endsection
