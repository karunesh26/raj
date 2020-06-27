@extends('template.template')
@section('content')
<?php
error_reporting(0);
$url = $controller_name.'@updateInq';
$btn= "Update";
$back_link=URL::to($controller_name);
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


                      <div class="form-group col-sm-12">

						<input type="hidden" name="inquiry_no" id="inquiry_no" class="form-control inquiry_no" value="<?php echo $result[0]->inquiry_no; ?>" />



                            <div class="form-group col-sm-3">
                             {!! Form::label('Inquiry Date') !!}
                            <?php
							if($action == 'insert')
							{
								?>
                          	 {!! Form::text('inquiry_data',date("d-m-Y"), array('class' => 'form-control ' ,'id'=>"inquiry_date",'required' => 'required','readonly'=>'readonly')) !!}
                             <?php
							}
							else
							{
								?>
                                 {!! Form::text('inquiry_date',date('d-m-Y', strtotime($result[0]->inquiry_date)), array('class' => 'form-control ' ,'id'=>"inquiry_date",'required' => 'required','readonly'=>'readonly')) !!}
                                <?php
							}
							?>
                            </div>
							 <div class="form-group col-sm-3">
							  {!! Form::label('Inquiry Time') !!}
							   <?php
								if($action == 'insert')
								{
									?>
								{!! Form::text('inquiry_time',date("h:i a"), array('class' => 'form-control ' ,'id'=>"inquiry_time",'required' => 'required','readonly'=>'readonly')) !!}
								<?php
								}
								else
								{
									?>
									{!! Form::text('inquiry_date',date('h:i a', strtotime($result[0]->inquiry_time)), array('class' => 'form-control ' ,'id'=>"inquiry_time",'required' => 'required','readonly'=>'readonly')) !!}
									<?php
								}
								?>

							 </div>
                             <div class="form-group col-sm-3">
                                    {!! Form::label('Inquiry Source') !!} <span class="required">*</span>

                                    <select name="source_id" id="source_id" class="select2" style="width:100%">
                                                <option  value="">Select</option>
                                                <?php $source_name=''; ?>
                                                @foreach($source as $k=> $v)
                                                    <?php
                                                    if ($v->source_id == $result[0]->source_id)
                                                    {
                                                        $source_name=strtolower($v->source_name);

                                                    ?>
                                                            <option value="{{ $v->source_id}}" selected="selected" > {{ $v->source_name}}</option>
                                                    <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                            <option value="{{ $v->source_id}}" > {{ $v->source_name}}</option>
                                                    <?php
                                                    }
                                                    ?>
                                                @endforeach
                                    </select>
                                    <label id="source_id-error" class="error" for="source_id"></label>
                                </div>
                                <div class="form-group col-sm-3">
                                    {!! Form::label('Inquiry No') !!}

                                    {!! Form::text('inquiry_no',$result[0]->inquiry_no, array('class' => 'form-control ' ,'id'=>"inquiry_no",'required' => 'required','readonly'=>'readonly')) !!}

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
	$.validator.setDefaults({ ignore: ":hidden:not(.select2)" })
	var id= $("#id").val();
	$('#frm').validate({
		rules: {
			source_id: {required: true,},
		},
		messages:{
			source_id: {required:"Please Select Inquiry Source"},
        },
        errorPlacement: function(error, element) {
			error.appendTo(element.parent("div"));
		},
        submitHandler: function(form)
        {
            $(':input[type="submit"]').prop('disabled', true);
            form.submit();
        }
	});

});
</script>
@endsection