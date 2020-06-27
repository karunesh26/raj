@extends('template.template')

@section('content')
<?php
error_reporting(0);
if($action == 'insert')
{
	$url = $controller_name.'@insert';
	$btn = "Save";
}
else
{
	$url = $controller_name.'@update';
	$btn= "Update";
}

$back_link = URL::to($controller_name);
?>

 <!-- Content Header (Page header) -->
    <section class="content-header">
    
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('Dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li ><?php echo $msgName;?></li>
        <li class="active"><?php echo $btn;?></li>
      </ol>
    </section>
    
    <section class="content">
    
	
		 
    	<div class="row">
        	<a class="btn bg-navy btn-flat margin" href="<?php echo $back_link;?>">Back</a>
        </div>
          
      <div class="row">
       <div class="box box-primary">
      	
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $btn." ".$msgName;?></h3>
            </div>
            
              {!! Form::open(array('action' => $url, 'method' => 'post' , 'files' => true ,'id'=>"frm",'name'=>"frm",'class'=>"form"))!!}
				 
         		  {{ csrf_field() }}
             	 <div class="box-body">
                 
                 	
                   {!! Form::hidden('id',$result[0]->$primary_id, array('id'=>"id")) !!}
						
					<table width="70%" id="table_id" class="table table-bordered" >
                       <thead>
                         <tr>
                            <th width="40%">Title</th>
                            <th width="40%">File</th>
                           <?php if($action == 'insert'){ ?> <th width="10%">Actions</th> <?php } ?>
                         </tr>
                       </thead>
                       <tbody  id="addrow" >
                       <?php if($action == 'insert')
							 {
						?>
                       	<tr class="pending-user">
							<td> 
								<input type="text" id="title" name="title[]"  class="form-control col-md-7 col-xs-12 title"  >
								<label class=""></label>
							</td>
                            <td> 
								<input type="file" id="0" name="file[]"  class="form-control col-md-7 col-xs-12 file"  >
								<label class=""></label>
							</td>
                              
                            <input type="hidden" id="last_row" name="last_row" />
                              
                              <td>
                                 <span class="user-actions">
                                    <button  tabindex="1" type="button" class="btn btn-success" onclick="">+</button>
                                    <button tabindex="1" type="button" class="btn btn-danger" >-</button>
                                  </span>
                             </td>
                           </tr>
                       <?php
							}
							else
							{
						?>
							<td> 
								<input type="text" id="title" value="{{ $result[0]->catalog_title }}"  name="title"  class="form-control col-md-7 col-xs-12 title"  >
								<label class=""></label>
							</td>
                            <td> 
								<input type="hidden" value="{{ $result[0]->catalog_file }}" name="old_file" id="old_file" />
								<input type="file" id="0" name="file"  class="form-control col-md-7 col-xs-12 file"  >
								<label class=""></label>
								<br />
								<a target="_blank" class="btn btn-info" href="{{ URL::asset('external/catalog/'.$result[0]->catalog_file) }}">View File</a>
							</td>	
						<?php
							}
							 ?>   
						   <tr>
                           		<td><label id=""></label></td>
                                <td><label id=""></label></td>
                                <?php if($action == 'insert'){ ?> <td></td> <?php } ?>
                           </tr>
                          </tbody>	
                  	 </table> 	
					
                  </div>
                
                </div>
                <div class="box-footer">
                	<div class="col-sm-6 col-sm-offset-5">
						<?php
						if($action == 'insert')
						{
						?>
						{{ Form::reset('Reset', ['class' => 'btn btn-danger']) }}
						<?php
						}
						else
						{
							?>
							<a class="btn btn-danger" type="reset" href="<?php echo $utility->encode($result[0]->$primary_id);?>">Reset</a>
						   <?php
						}
						?>
						{!! Form::submit($btn, ['class' => 'btn bg-olive']) !!}
                    </div>
                </div>
               {!!Form::close()!!}
             
		</div>
       </div>
     </section>
 

<script type="text/javascript">

jQuery(document).ready(function($){

	<?php 
		if($action == 'insert')
		{
	?>
		_.templateSettings.variable = "element";
		var tpl = _.template($("#form_tpl").html());
		var counter = 1;
		
		$("body").on("click",".btn-success", function (e) {
		  e.preventDefault();
			 var tplData = {
				i: counter
			};
			 $(this).closest("tr").after(tpl(tplData));
				counter += 1;
				 $(".select2_single").select2({
					 placeholder: "Select",
					 allowClear: true
				  });
				var row_index = $(this).closest("tr").index() + 1;
			return false;
		});
		
		$('body').on('click',".btn-danger",function()
		{ 
			var count= $('.pending-user').length; 
			var value=count-1;
			if(value>=1)
			{
			$(this).closest('.pending-user').fadeOut('fast', function(){$(this).closest('.pending-user').remove();
				return false;
			 });
			}
		});
		
		$('#frm').on('submit', function(e){
			e.preventDefault();
			var form = this;
			if($("#frm").valid() )
			{
				var t_a = [];
				var f_a = [];
				$('.title').each(function () 
				{
					
					if($(this).val() == '' )
					{
						t_a.push(0);
						$(this).closest("td").find("label").addClass('title_err');
					}
					else
					{
						t_a.push(1);
						$(this).closest("td").find("label").removeClass('title_err');
						$(this).closest("td").find("label").html('');
					}
					
				});
				
				$('.file').each(function () 
				{
					if($(this).val() == '' )
					{
						f_a.push(0);
						$(this).next().addClass('file_err');
					}
					else
					{
						f_a.push(1);
						$(this).next().html('');
						$(this).next().removeClass('file_err');
					}
					
				});
				var t = t_a.indexOf(0);
				var f = f_a.indexOf(0);
				if(t != '-1')
				{
					var str = 'Please Enter Title';
					var result = str.fontcolor("red");
					$('.title_err').html(result);
					return false;
				}
				else
				{
					$('.title_err').html('');
					if(f != '-1')
					{
						var str = 'Please Select File';
						var result = str.fontcolor("red");
						$('.file_err').html(result);
						return false;
					}
					else
					{
						$(':input[type="submit"]').prop('disabled', true);
						form.submit();
					}
				}
			}
			else
			{
				return false;
			}
			
		});
	<?php
		}
		else
		{
	?>
			$('#frm').validate({
				rules: {
					title:{required: true,},
				},
				messages:{
					title: {required:"Please Enter <?php echo $msgName;?>",},
				},
				submitHandler: function(form) {
					$(':input[type="submit"]').prop('disabled', true);
					form.submit();
				},
			});
	<?php
		}
	?>
		
			
});
</script>
<script  type="text/html" id="form_tpl">
<tr class="pending-user">
	<td> 
		<input type="text" id="title" name="title[]"  class="form-control col-md-7 col-xs-12 title"  >
		<label class=""></label>
	</td>
	<td> 
		<input type="file" id="file" name="file[]"  class="form-control col-md-7 col-xs-12 file" >
		<label class=""></label>
	</td>
	  
	<input type="hidden" id="last_row" name="last_row" />
	  
	  <td>
		 <span class="user-actions">
			<button  tabindex="1" type="button" class="btn btn-success" onclick="">+</button>
			<button tabindex="1" type="button" class="btn btn-danger" >-</button>
		  </span>
	 </td>
   </tr>
</script>			
@endsection