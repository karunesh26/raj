@extends('template.template')

@section('content')
<?php
error_reporting(0);
if($action == 'insert')
{
	$url = $controller_name.'@insert';
	$duplicate_url = URL::action($controller_name.'@duplicate');
	$btn = "Save";
	
	
}
else
{
	$url = $controller_name.'@update';
	$duplicate_url = URL::action($controller_name.'@duplicate_update');
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
            
              {!! Form::open(array('action' => $url, 'method' => 'post' , 'files' => true ,'id'=>"frm",'name'=>"frm",'class'=>"form",enctype=>"multipart/form-data"))!!}
				 
         		  {{ csrf_field() }}
             	 <div class="box-body">
                 
                 	
                   {!! Form::hidden('id',$result[0]->$primary_id, array('id'=>"id")) !!}
                 
                 	
                    
                    <div class="form-group col-sm-12">
                      	<div class="form-group col-sm-4">
                       {!! Form::label(' Sequence') !!} <span class="required">*</span>
                         {!! Form::text('sequence',$result[0]->sequence, array('class' => 'form-control ' ,'id'=>"sequence",'placeholder'=>'Enter Sequence','required' => 'required')) !!}
        
                        </div>

                     	<div class="form-group col-sm-4">
                       {!! Form::label('Name') !!} <span class="required">*</span>
                         {!! Form::text('name',$result[0]->$field, array('class' => 'form-control ' ,'id'=>"name",'placeholder'=>'Enter Name','required' => 'required')) !!}
        
                        </div>
                        <div class="form-group col-sm-4">
                          {!! Form::label('Image') !!}
                         {!! Form::file('image', array('class' => 'form-control ' ,'id'=>"image",accept=>"image/*")) !!}
                          {!! Form::hidden('old_image',$result[0]->image, array('class' => 'form-control ' ,'id'=>"old_image")) !!}
                          <?php
                          if($result[0]->image != '')
                          {
                        ?>
                          <img src="{{ asset('external/specification_image/'.$result[0]->image) }}" height="100" width="100">
                        <?php
                          }
                          else
                          {
                        ?>
                            <img src="{{ asset('external/no_uploaded.png') }}" height="100" width="100">
                        <?php
                          }
                           ?>
                        </div>
                        
                      
                    </div>
                    
                     <div class="form-group col-sm-12">
                        <table width="100%"  class="table table-bordered" >
                        <tr>
                          <th width="50%">{!! Form::label('Specification') !!} </th>
                          <th width="30%">{!! Form::label('Description') !!}</th>
                          <th width="20%"></th>
                        </tr>
                         <?php
                         if($action == 'insert')
                         {
                           ?>
                             <tr class="pending-user">
								               <td>
                               	{!! Form::text('spe_name[]','',array('class' => 'form-control name' ,'id'=>"name")) !!}
                              </td>
                               <td>
                                {!! Form::text('spe_value[]','',array('class' => 'form-control value' ,'id'=>"value")) !!}
                               </td>
                                <td>
                                  <span class="user-actions">
                                  <button  tabindex="1" type="button" class="btn btn-xs btn-success" onclick="">+</button>
                                  <button tabindex="1" type="button" class="btn btn-xs btn-danger" >-</button>
                                  </span>
									           </td>
                             </tr>
                           <?php
                         }
                         else
                         {
                           $spe_name_arr = explode("+++++",$result[0]->spe_name);
                           $spe_value_arr = explode("+++++",$result[0]->spe_value);
                           for($i=0; $i<count($spe_name_arr);$i++)
								          	{
                           ?>
                            <tr class="pending-user">
								               <td>
                               	{!! Form::text('spe_name[]',$spe_name_arr[$i],array('class' => 'form-control name' ,'id'=>"name")) !!}
                              </td>
                               <td>
                                {!! Form::text('spe_value[]',$spe_value_arr[$i],array('class' => 'form-control value' ,'id'=>"value")) !!}
                               </td>
                                <td>
                                  <span class="user-actions">
                                  <button  tabindex="1" type="button" class="btn btn-xs btn-success" onclick="">+</button>
                                  <button tabindex="1" type="button" class="btn btn-xs btn-danger" >-</button>
                                  </span>
									           </td>
                             </tr>
                           <?php
                            }
                         }
                         ?>
                        </table>
                     </div>

                    <div class="form-group col-sm-12">
                    	<div class="form-group col-sm-4">
                       {!! Form::label('Application') !!} 
                      </div>
                      <div class="form-group col-sm-8">
                      {!! Form::textarea('application',$result[0]->application, array('class' => 'form-control ' ,'id'=>"application",'placeholder'=>'Enter Application')) !!}
                      </div>
                    </div>

                     
                      
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
	
	
	
	var id= $("#id").val();
	$('#frm').validate({
		rules: {
      sequence: {required: true,},
			name: {required: true,
					remote: {	
									url: '<?php echo $duplicate_url;?>', 
									
									type: "post",
									data:
									{	
										 "_token": "{{ csrf_token() }}",
										id:id,		
										name: function()
										{
											return $('#frm :input[name="name"]').val();
										},
									},
									
							},
				},
		
		},
		messages:{
      sequence: {required:"Please Enter Sequence"},
			name: {required:"Please Enter <?php echo $msgName;?>",remote:"<?php echo $msgName;?> Already Exist!"},
		
		},
		submitHandler: function(form) {
				$(':input[type="submit"]').prop('disabled', true);
				form.submit();
		},
	});
		
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
		
		return false;
	});
	
	$('body').on('click',".btn-danger",function()
	{
		var count= $('.pending-user').length;
		var value=count-1;
		if(value>=1)
		{
			$(this).closest('.pending-user').fadeOut('fast', function(){$(this).closest('.pending-user').remove();	
			});
		}
		
	});
	
		
});
</script>
<script  type="text/html" id="form_tpl">
	<tr class="pending-user">
	  <td>
    {!! Form::text('spe_name[]','',array('class' => 'form-control name' ,'id'=>"name")) !!}
    </td>
    <td>
    {!! Form::text('spe_value[]','',array('class' => 'form-control value' ,'id'=>"value")) !!}
    </td>
	  <td>
		<span class="user-actions">
			<button  tabindex="1" type="button" class="btn btn-xs btn-success" onclick="">+</button>
			<button tabindex="1" type="button" class="btn btn-xs btn-danger" >-</button>
		</span>
	 </td>
	</tr>
</script>				
@endsection