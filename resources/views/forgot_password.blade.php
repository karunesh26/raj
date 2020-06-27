<?php

//$url = URL::action('Login@doLogin');

$company_data = DB::table('company')->get();

?>

<!DOCTYPE html>

<html>

<head>

  <meta charset="utf-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">



    <title><?php echo $company_data[0]->name;?></title>

    

 	<!-- Tell the browser to be responsive to screen width -->

  	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

 	 <!-- Bootstrap 3.3.7 -->

    <link href="{{ URL::asset('external/bower_components/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">

    

    <!-- Font Awesome -->

    

    <link href="{{ URL::asset('external/bower_components/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

   

    <!-- Ionicons -->

    <link href="{{ URL::asset('external/bower_components/Ionicons/css/ionicons.min.css') }}" rel="stylesheet">

    

      <!-- Theme style -->



     <link href="{{ URL::asset('external/dist/css/AdminLTE.min.css') }}" rel="stylesheet">

      <!-- iCheck -->

      <link href="{{ URL::asset('external/plugins/iCheck/square/blue.css') }}" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

  <!--[if lt IE 9]>

  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>

  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

  <![endif]-->



  <!-- Google Font -->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  </head>



<body class="hold-transition login-page">

	<div class="login-box">

  	<div class="login-logo"><b><?php echo $company_data[0]->name;?></b>

  	</div>



            

            <div class="row-fluid" >

                   

     

                      @if(session()->has('error'))

                          <span class="7"><div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong> {{ session()->get('error') }}</strong></div></span>

                       

                    @endif

                    

                   @if(session()->has('success'))

                       <span class="7"><div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong>

                        {{ session()->get('success') }}

                   </strong></div></span>

               	 @endif

        

             </div>

            

             

             <!-- /.login-logo -->

  		<div class="login-box-body">

   			 <p class="login-box-msg">Forgot Password</p>

                 <?php	echo Form::open(array('action' => 'Login@update_password', 'method' => 'post' , 'files' => true ,'id'=>"frmLogin",'name'=>"frmLogin"))

				 

				 

?>

						{{ csrf_field() }}

                    	 <div class="form-group has-feedback">

                         

                         	<?php

                            echo Form::text('username','', array('class' => 'form-control ' ,'id'=>"username",'placeholder'=>'Username','required' => 'required' ));

							?>

                         

                             <span class="glyphicon glyphicon-user form-control-feedback"></span>

                         </div>

                          

                         <div class="row">

       							<div class="col-xs-8">
									
                             	</div>

                                <div class="col-xs-4">

                           		<?php /*?><input style="float:right"  type="submit" class="btn btn-default btn-large" value="Login" /><?php */?>

                               <button type="submit" class="btn btn-primary btn-block btn-flat">Change</button>

                                </div>

                          </div>

                         

                    <?php echo Form::close();?>

                 	<div class="social-auth-links text-center">
						
                    	<div>
								
                              <h1><?php /*?><img src="<?php echo base_url();?>assets/images/logo.png"><?php */?></h1>

                              <p>©<?php echo date("Y");?> All Rights Reserved.</p>

                        </div>

                    </div>

                

 		 </div>

 	 <!-- /.login-box-body -->

	</div>

   <!-- /.login-box -->

</body>

</html>

<!-- jQuery 3 -->

    <script src="{{ URL::asset('external/bower_components/jquery/dist/jquery.min.js') }}"></script>

<!-- Bootstrap 3.3.7 -->

    <script src="{{ URL::asset('external/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <!-- iCheck -->

 <script src="{{ URL::asset('external/plugins/iCheck/icheck.min.js') }}"></script>

 <script src="{{ URL::asset('external/js/common.js') }}"></script>


 