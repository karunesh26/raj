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
							<div class="col-lg-2">
								<div class="input-group">

									<input type="text" name="inq_no" placeholder="Inquiry No" id="inq_no" class="form-control">
								</div>
							</div>
							<div class="col-lg-2">
								<div class="input-group">
								
									<input type="text" name="quot_no" placeholder="Quotation No" id="quot_no" class="form-control">
								</div>
							</div>
							<div class="col-lg-2">
								<div class="input-group">
									<select name="inq_for" id="inq_for" class="form-control">
										<option value="">Inquiry For</option>
										@foreach($inq_for as $key=>$val)
											<option value="{{ $val->product_id }}">{{ $val->product_name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-lg-2">
								<div class="input-group">
										<select name="inq_source" id="inq_source" class="form-control">
										<option value="">Inquiry Source</option>
										@foreach($inq_source as $key=>$val)
											<option value="{{ $val->source_id }}">{{ $val->source_name }}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="col-lg-2">
								<div class="input-group">
									<select name="inq_type" id="inq_type" class="form-control">
										<option value="">Inquiry Type</option>
										@foreach($inq_type as $key=>$val)
											<option value="{{ $val->category_id }}">{{ $val->category_name }}</option>
										@endforeach
									</select> 
								</div>
							</div>
							<div class="col-lg-2">
								<div class="input-group">
									<select name="inq_person" id="inq_person" class="form-control">
										<option value="">Inquiry Person</option>
										@foreach($users as $key=>$val)
											<option value="{{ $val->id }}">{{ $val->username }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-lg-2">
								<div class="input-group">
									<select name="quot_person" id="quot_person" class="form-control">
										<option value="">Quotation Person</option>
										@foreach($users as $key=>$val)
											<option value="{{ $val->id }}">{{ $val->username }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-lg-2">
								<div class="input-group">
									<select name="foll_by" id="foll_by" class="form-control">
										<option value="">Follow-Up By</option>
										@foreach($users as $key=>$val)
											<option value="{{ $val->id }}">{{ $val->username }}</option>
										@endforeach
									</select>	
								</div>
							</div>
							<div class="col-lg-2">
								<div class="input-group">
									<input type="text" name="client_name" placeholder="Client Name" id="client_name" class="form-control">
								</div>
							</div>
							<div class="col-lg-2">
								<div class="input-group">
									<input type="text" name="mobile_no" placeholder="Mobile No" id="mobile_no" class="form-control">
								</div>
							</div>
							<div class="col-lg-2">
								<div class="input-group">
									<input type="text" name="email_id" placeholder="Email Id" id="email_id" class="form-control">
								</div>
							</div>
							<div class="col-lg-2">
								<div class="input-group">
									<select name="client_category" id="client_category" class="form-control">
										<option value="">Client Category</option>
										@foreach($client_category as $key=>$val)
											<option value="{{ $val->client_category_id }}">{{ $val->client_category_name }}</option>
										@endforeach	
									</select>
								</div>
							</div>
							<div class="col-lg-2">
								<div class="input-group">
									<select name="country" id="country" class="form-control">
										<option value="">Country</option>
										@foreach($country as $key=>$val)
											<option value="{{ $val->country_id }}">{{ $val->country_name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-lg-2">
								<div class="input-group">
									<select name="state" id="state" class="form-control">
										<option value="">State</option>
										@foreach($state as $key=>$val)
											<option value="{{ $val->state_id }}">{{ $val->state_name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-lg-2">
								<div class="input-group">
									<select name="city" id="city" class="form-control">
										<option value="">City</option>
										@foreach($city as $key=>$val)
											<option value="{{ $val->city_id }}">{{ $val->city_name }}</option>
										@endforeach
									</select>
								</div>
							</div>


							<div class="col-lg-2">
								<div class="input-group date">
								  <div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								  </div>
								  <input type="text" name="from_date" id="from_date" placeholder=" From Date" class="form-control pull-right datepicker" >
								</div>
							</div>
							<div class="col-lg-2">
								<div class="input-group date">
								  <div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								  </div>
								  <input type="text" placeholder=" To Date" name="to_date" id="to_date" class="form-control pull-right datepicker" >
								</div>
							</div>


						</div>
						<br>
						<div class="col-lg-12">
								<div class="col-md-1 col-md-offset-5">
									<a id="search_btn" class="btn btn-success" >Search</a>
								</div>
							</div>
						<br><br><br>
						 <div class="box-footer"></div>

						<div id="search_data">
							<table id="datatable" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>Sr No.</th>
										<th>View</th>
										<th>Quot. Date</th>
										<th>Client Name</th>
										<th>Mobile No.</th>
										<th>Email Id</th>
										<th>Address</th>
										<th>Country</th>
										<th>State</th>
										<th>City</th>
										<th>Inquiry For</th>
										<th>Inquiry Type</th>
										<th>Quot. No.</th>
										<th>Inq. No.</th>
										<th>Inq. Source</th>
										<th>Client Category</th>
										<th>Visit Status</th>
										<th>Inq. By</th>
										<th>Quot. By</th>
										<th>Follow-Up By</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
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
		var inq_no = $('#inq_no').val();
		var quot_no = $('#quot_no').val();
		var inq_for = $('#inq_for').val();
		var inq_source = $('#inq_source').val();
		var inq_person = $('#inq_person').val();
		var quot_person = $('#quot_person').val();
		var foll_by = $('#foll_by').val();
		var client_name = $('#client_name').val();
		var mobile_no = $('#mobile_no').val();
		var email_id = $('#email_id').val();
		var client_category = $('#client_category').val();
		var country = $('#country').val();
		var state = $('#state').val();
		var city = $('#city').val();
		var inq_type = $('#inq_type').val();

		var from_date = $('#from_date').val();
		var to_date = $('#to_date').val();

		if(inq_no == '' && quot_no == '' && inq_for == '' && inq_source == '' && inq_person == '' && quot_person == '' && foll_by == '' &&  client_name == '' && mobile_no == '' && email_id == '' && client_category == '' && country == '' && state == '' && city == '' && inq_type == '')
		{
			alert('Please Enter Any One Search Value');
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

		$('.blockUI').show();
		$.ajax({
			type: "POST",
			url: "<?php echo $get_cearch_data_url;?>",
			data: { "_token": "{{ csrf_token() }}",inq_no:inq_no,quot_no:quot_no,inq_for:inq_for,inq_source:inq_source,inq_person:inq_person,quot_person:quot_person,foll_by:foll_by,client_name:client_name,mobile_no:mobile_no,email_id:email_id,client_category:client_category,country:country,state:state,city:city,from_date:from_date,to_date:to_date,inq_type:inq_type},
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
