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
								<div class="col-sm-2 col-md-offset-3">
									{!! Form::label('Inquiry Source :') !!} <span class="required">*</span>
								</div>
								<div class="col-sm-4">
									<select multiple name="source[]" id="source" class="select2" style="width:100%">
										<option value="all">All</option>
										@foreach($source as $key=>$val)
											<option value="{{ $val->source_id }}">{{ $val->source_name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group col-md-12">
								<div class="col-sm-2 col-md-offset-3">
									{!! Form::label('State :') !!}
								</div>
								<div class="col-sm-4">
									<select multiple name="state[]" id="state" class="select2" style="width:100%">
										<option value="all">All</option>
										@foreach($state as $key=>$val)
											<option value="{{ $val->state_id }}">{{ $val->state_name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group col-md-12">
								<div class="col-sm-2 col-md-offset-3">
									{!! Form::label('From Date :') !!}
								</div>
								<div class="col-sm-4">
									<input type="text" name="from_date" autocomplete="off" id="from_date" placeholder="Inquiry From Date" class="form-control pull-right datepicker" >
								</div>
							</div>
							<div class="form-group col-md-12">
								<div class="col-sm-2 col-md-offset-3">
									{!! Form::label('To Date :') !!}
								</div>
								<div class="col-sm-4">
									<input type="text" placeholder="Inquiry To Date" autocomplete="off" name="to_date" id="to_date" class="form-control pull-right datepicker" >
								</div>
							</div>

							<div class="col-md-2 col-md-offset-6">
								<a id="search_btn" class="btn btn-success" >Search</a>
							</div>
						</div>

						<br><div class="box-footer"></div>

						<div id="search_data">
							<table id="datatable" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>Sr No.</th>
										<th>Date</th>
										<th>Total Inquiry</th>
										<th>Pending Inquiry</th>
										<th>Active Inquiry</th>
										<th>Cancel Inquiry</th>
										<th>Delete Inquiry</th>
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
	  orientation: "bottom auto"
    });

	$('body').on('click','#search_btn', function(){
		var source = $('#source').val();
		var from_date = $('#from_date').val();
		var to_date = $('#to_date').val();
		var state = $('#state').val();
		if(source == '')
		{
			alert('Please Select Source');
			return false;
		}
		if(from_date != '')
		{
			if(to_date == '')
			{
				alert('Please Select To Date');
				return false;
			}
		}
		if(to_date != '')
		{
			if(from_date == '')
			{
				alert('Please Select From Date');
				return false;
			}
		}

		$('.blockUI').show();
		$.ajax({
			type: "POST",
			url: "<?php echo $get_cearch_data_url;?>",
			data: { "_token": "{{ csrf_token() }}",source:source,from_date:from_date,to_date:to_date,state:state},
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
