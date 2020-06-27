@extends('template.template')

@section('content')
<?php 
	$get_user = URL::action($controller_name.'@get_user');
	$get_data = URL::action($controller_name.'@get_data');
?>
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
                     	 <div class="form-group col-sm-12">
							<div class="form-group col-sm-4">
								{!! Form::label('Role') !!} <span class="required">*</span>
							</div>
							<div class="form-group col-sm-8">
								 <select name="role_id" id="role_id" class="select2" style="width:100%">
										<option  value="">Select</option>
										@foreach($role as $k=> $v)
												<option value="{{ $v->role_id}}" > {{ $v->role_name}}</option>
										@endforeach
								</select>
							</div>
						</div>
						
						<div class="form-group col-sm-12">
							<div class="form-group col-sm-4">
								{!! Form::label('User') !!} <span class="required">*</span>
							</div>
							<div class="form-group col-sm-8">
								 <select name="user_id" id="user_id" class="select2" style="width:100%">
										<option  value="">Select</option>
										
								</select>
							</div>
						</div>
                    </div>
					
					<div class="box-body">
						<div id="role_data">
                        </div>
					</div>	
					
                  </div>
             </div>
        </div>
    </section>
	<script type="text/javascript">

jQuery(document).ready(function($){
	
	$('body').on('change','#role_id', function(){
		var role_id = $(this).val();
		if(role_id == '')
		{
			return false;
		}
		else
		{
			$('#user_id').select2().val('').trigger('change');
			$('.blockUI').show();
			$.ajax({
				type: "POST",
				url: "<?php echo $get_user;?>",
				data: { "_token": "{{ csrf_token() }}",role_id:role_id},
				success:function(res)
				{
					$('#user_id').html(res);
					$('.blockUI').hide();
				}
			});
		}
	});	
	
	
	$('body').on('change','#user_id', function(){
		var user_id = $(this).val();
		var role_id = $('#role_id').val();
		if(user_id == '' || role_id == '')
		{
			return false;
		}
		else
		{
			$('#user_id').select2().val('').trigger('change');
			$('.blockUI').show();
			$.ajax({
				type: "POST",
				url: "<?php echo $get_data; ?>",
				data: { "_token": "{{ csrf_token() }}",user_id:user_id,role_id:role_id},
				success:function(res)
				{
					 $("#role_data").empty().append().html(res);
					$('.blockUI').hide();
				}
			});
		}
	});	
	
});
</script>
@endsection
