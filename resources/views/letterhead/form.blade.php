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
                            <th width="40%">File</th>
                           <?php if($action == 'insert'){ ?> <th width="10%">Actions</th> <?php } ?>
                         </tr>
                       </thead>
                       <tbody  id="addrow" >
                            <td> 
								<input type="hidden" value="{{ $result[0]->letterhead_name }}" name="old_file" id="old_file" />
								<input type="file" id="0" name="file"  class="form-control col-md-7 col-xs-12 file"  >
								<label class=""></label>
								<br />
								<a target="_blank" class="btn btn-info" href="{{ URL::asset('external/quatation_formate/'.$result[0]->letterhead_name) }}">View File</a>
							</td>	
						 
						   <tr>
                                <td><label id=""></label></td>
                           </tr>
                          </tbody>	
                  	 </table> 	
					
                  </div>
                
                </div>
                <div class="box-footer">
                	<div class="col-sm-6 col-sm-offset-5">
						
						<a class="btn btn-danger" type="reset" href="<?php echo $utility->encode($result[0]->$primary_id);?>">Reset</a>
						   
						{!! Form::submit($btn, ['class' => 'btn bg-olive']) !!}
                    </div>
                </div>
               {!!Form::close()!!}
             
		</div>
       </div>
     </section>
 

<script type="text/javascript">

jQuery(document).ready(function($){

	$('#frm').validate({
		
		submitHandler: function(form) {
			$(':input[type="submit"]').prop('disabled', true);
			form.submit();
		},
	});
			
});
</script>
		
@endsection