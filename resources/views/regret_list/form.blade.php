@extends('template.template')
@section('content')
<?php
error_reporting(0);
$back_link = URL::to($controller_name);
$get_inquiry_data = URL::action($controller_name.'@get_inquiry_data');
$get_regret_list_data = URL::action($controller_name.'@get_regret_list');
?>
<style>
.btn_width{
	width:100%;
	text-align:center;
}
</style>
<div class="blockUI" style=""></div>
<div class="blockUI blockOverlay" style="z-index: 1000; border: medium none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background-color: rgb(0, 0, 0); opacity: 0.6; cursor: wait; position: fixed;"></div>
<div class="blockUI blockMsg blockPage" style="z-index: 1011; position: fixed; padding: 15px; margin: 0px; width: 30%; top: 40%; left: 35%; text-align: center; color: rgb(255, 255, 255); border: medium none;  cursor: wait; opacity: 0.5;">
	<img alt="loading.." src="{{ URL::asset('external/gif/6.gif')}}">
</div>
 <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> <?php echo $msgName;?> Details</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('Dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><?php echo $msgName; ?></li>
      </ol>
    </section>
    <br />
    <section class="content">
		<div class="row">
				<div class="col-xs-4">
				  <div class="box box-primary">
					<div class="box-body box-profile">
						<div class="row">
							<div class="col-xs-3">
								<a target="_blank" href="{{ URL::to('Search') }}"><img style="width:100%;height:100%;" src="{{ URL::asset('external/search_image/search1.png')}}"></a>
							</div>
							<div class="col-xs-9">
								<div class="row">
									<div class="col-xs-6">
										<input type="text" value="{{date('d-m-Y')}}" class="form-control" id="search_date" readonly="readonly" />
									</div>
									<div class="col-xs-6" style="padding-left:0">
										<input type="text" value="{{date('l')}}" class="form-control" id="day_name" readonly="readonly" />
									</div>
								</div>
								<div><br/></div>
								<div class="row">
									<div class="col-xs-6 col-md-offset-3">
										<center><button class="search_regret_list btn btn-primary btn_width">Regret List</button></center>
									</div>
								</div>
							</div>
						</div>
						<hr />
						<br />
						<div class="row">
						  <div class="col-xs-12 search_data">
						  @if(count($inquiry))
							  <table id="datatable_with_scroll" class="table table-bordered color_table">
								<thead>
									<tr>
										<th>Sr</th>
										<th>Quotation No.</th>
										<th>Customer</th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							  </table>
							@endif
							 </div>
						 </div>
					</div>
					<!-- /.box-body -->
				  </div>
				 </div>

		  <!-- Inquiry Data Change Start -->
            <div class="col-xs-8">
			  <div class="inquiry_data_change"></div>
		   <!-- Inquiry Data Change End -->
           </div>
		</div>
    </section>


<script type="text/javascript">
jQuery(document).ready(function($){
	$('body').on('click',"#follow_up_btn",function(e){
		var inquiry_id = $(this).closest('tr').find('#follow_up_inquiry_id').val();
		$('.color_table tr').css('background','#fff');
		$('.color_table .active_tr').css('background','#00FA9A');
		$(this).css('background','#87CEEB');
		$('.blockUI').show();
		$.ajax({
			type:"POST",
			url:"{{ $get_inquiry_data }}",
			data:{enquiry_id:inquiry_id,_token:'{{ csrf_token() }}'},
			success:function (res)
			{
				$('.inquiry_data_change').empty().html(res);
				$("select").select2({placeholder: "Select"}).one('select2-focus', select2Focus).on("select2-blur", function (){
				  $(this).one('select2-focus', select2Focus)
				});
				$('.blockUI').hide();
			}
		});
	});

	$('#datatable_with_scroll').DataTable( {
        scrollY:        '70vh',
        scrollCollapse: true,
        paging:         false
    } );

	<?php
		if($auto_click == 'yes')
		{
	?>
		$('#follow_up_btn').click();
	<?php
		}
	?>

	$('#search_date').datepicker({
		autoclose: true,
		format: 'dd-mm-yyyy',
		//endDate: '+0d',
	});

	$('#search_date').on('show', function(e){
		if ( e.date )
		{
			 $(this).data('stickyDate', e.date);
		}
		else {
			 $(this).data('stickyDate', null);
		}
	});
	$('body').on('change',"#search_date",function(e){
		var dt = $(this).val();
		$('#day_name').val(Day_name(dt));
	});

	$('body').on('click',".search_regret_list",function(e){
		var search_date = $('#search_date').val();
		$('.blockUI').show();
		$.ajax({
			type:"POST",
			url:"{{ $get_regret_list_data }}",
			data:{search_date:search_date,_token:'{{ csrf_token() }}'},
			success:function (res)
			{
				$('.search_data').html(res);
				$('.blockUI').hide();
			}
		});
	});


	function Day_name(custom_date)
	{
		 var myDate=custom_date;
		 myDate=myDate.split("-");
		 var newDate=myDate[2]+"-"+myDate[1]+"-"+myDate[0];
		 var my_ddate=new Date(newDate).getTime();
		 var currentDate = new Date(newDate);
		 var day_name = currentDate.getDay();
		 var days = new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
		 return days[day_name];
	}
});
</script>
@endsection