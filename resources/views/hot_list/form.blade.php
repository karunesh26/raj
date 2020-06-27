@extends('template.template')
@section('content')
<?php
error_reporting(0);
$back_link = URL::to($controller_name);
$get_hot_list_data = URL::action($controller_name.'@hot_list_data');
$get_inquiry_data = URL::action($controller_name.'@get_inquiry_data');
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
										<input type="text" placeholder="Enter Week Number" maxlength="2" name="hot_list_week" class="form-control numberonly" id="hot_list_week"  />
									</div>

									<div class="col-xs-6" style="padding-left:0">
										<input type="text" readonly placeholder="Year" name="hotlistYear" id="hotlistYear" value="{{ date('Y') }}" class="form-control" />
									</div>
								</div>
								<div><br /></div>
								<div class="row">
									<div class="col-xs-6">
										<input type="text" class="form-control" name="hot_list_week_first" id="hot_list_week_first" readonly="readonly" />
									</div>
									<div class="col-xs-6" style="padding-left:0">
										<input type="text" class="form-control" name="hot_list_week_second" id="hot_list_week_second" readonly="readonly" />
									</div>
								</div>
								<div><br /></div>
								<div class="row">
									<div class="col-md-2 col-md-offset-4">
										<input type="button" name="get_hot_list" id="get_hot_list" value="Hot List" class="btn btn-primary">
									</div>
								</div>
							</div>
						</div>
						<hr />
						<div class="row">
						  <div class="col-xs-12 search_data">
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
							 </div>
						 </div>

					<!-- /.box-body -->
				  </div>
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
Date.prototype.getWeek = function () {
    var target  = new Date(this.valueOf());
    var dayNr   = (this.getDay() + 6) % 7;
    target.setDate(target.getDate() - dayNr + 3);
    var firstThursday = target.valueOf();
    target.setMonth(3, 1);
    if (target.getDay() != 4) {
        target.setMonth(3, 1 + ((4 - target.getDay()) + 7) % 7);
    }
	return 1 + Math.ceil((firstThursday - target) / 604800000);

}

/*var d = new Date();
$('#hot_list_week').val(d.getWeek());*/

function ISO8601_week_no(dt)
  {
     var tdt = new Date(dt.valueOf());
     var dayn = (dt.getDay() + 6) % 7;
     tdt.setDate(tdt.getDate() - dayn + 3);
     var firstThursday = tdt.valueOf();
     tdt.setMonth(0, 1);
     if (tdt.getDay() !== 4)
       {
      tdt.setMonth(0, 1 + ((4 - tdt.getDay()) + 7) % 7);
        }
     return 1 + Math.ceil((firstThursday - tdt) / 604800000);
        }

dt = new Date();
console.log(ISO8601_week_no(dt));


/* Date.prototype.getWeek = function() {
	var date = new Date(this.getTime());
	date.setHours(0, 0, 0, 0);
	date.setDate(date.getDate() + 3 - (date.getDay() + 6) % 7);
	console.log(date.getDate() + 3 - (date.getDay() + 6) % 7);
	var week1 = new Date(date.getFullYear(), 3, 1);
	return 1 + Math.round(((date.getTime() - week1.getTime()) / 86400000 - 3 + (week1.getDay() + 6) % 7) / 7);
} */

function getDateRangeOfWeek(weekNo,y)
{
    var d1, numOfdaysPastSinceLastMonday, rangeIsFrom, rangeIsTo;
    d1 = new Date(''+y+'');
    numOfdaysPastSinceLastMonday = d1.getDay() - 1;
    //d1.setDate(d1.getDate() - numOfdaysPastSinceLastMonday);
    d1.setDate(d1.getDate() + (7 * (weekNo - d1.getWeek())));
		d1.setDate(d1.getDate() -1);
    rangeIsFrom = d1.getDate() + "-" + (d1.getMonth() + 1) + "-" + d1.getFullYear();
    d1.setDate(d1.getDate() + 6);
    rangeIsTo = d1.getDate() + "-" + (d1.getMonth() + 1) + "-" + d1.getFullYear() ;
    return rangeIsFrom + "," + rangeIsTo;
};

function get_financial_year()
{
    var today = new Date();
    var cur_month = today.getMonth();
    var year = "";
    if (cur_month => 3)
	{
        year = today.getFullYear().toString();
    }
	else
	{
        year = (today.getFullYear() - 1).toString();
		}

    return year;
}
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

	$("#hotlistYear").datepicker({
		format: "yyyy",
		viewMode: "years",
		minViewMode: "years"
	});

	$('body').on('change', '#hotlistYear, #hot_list_week', function(e){
		var hotlistYear = $('body').find('#hotlistYear').val();
		var hot_list_week = $('body').find('#hot_list_week').val();

		if (hotlistYear != '' && hot_list_week != '') {
			var date = getDateRangeOfWeek(hot_list_week,hotlistYear);
			var date1 = date.split(",");
			$('#hot_list_week_first').val(date1[0]);
			$('#hot_list_week_second').val(date1[1]);
		} else {
			$('#hot_list_week_first').val('');
			$('#hot_list_week_second').val('');
		}
	});

	$('body').on('keyup',"#hot_list_week",function(e){
		var week_no = $(this).val();
		var hotlistYear = $('body').find('#hotlistYear').val();

		if(week_no == '' || hotlistYear == '')
		{
			$('#hot_list_week_first').val('');
			$('#hot_list_week_second').val('');
		}
		else
		{
			var date = getDateRangeOfWeek(week_no,hotlistYear);
			var date1 = date.split(",");
			$('#hot_list_week_first').val(date1[0]);
			$('#hot_list_week_second').val(date1[1]);
		}
	});

	$('body').on('click',"#get_hot_list",function(e){

		var week_number = $('#hot_list_week').val();
		var hotlistYear = $('#hotlistYear').val();

		if(week_number == '' || hotlistYear == '')
		{
			alert("Please Enter Week Number Or Year");
			return false;
		}

		$.ajax({
			type:"POST",
			url:"{{ $get_hot_list_data }}",
			data:{week_number:week_number,hotlistYear:hotlistYear,_token:'{{ csrf_token() }}'},
			success:function (res)
			{
				$('.search_data').empty().html(res);
				$("select").select2({placeholder: "Select"}).one('select2-focus', select2Focus).on("select2-blur", function (){
				  $(this).one('select2-focus', select2Focus)
				});
				$('.blockUI').hide();
			}
		});
	});
	$('#hot_list_week').trigger('keyup');
	//$('#get_hot_list').click();
	$('#datatable_with_scroll').DataTable( {
        scrollY:        '70vh',
        scrollCollapse: true,
        paging:         false
    } );

});
</script>
@endsection