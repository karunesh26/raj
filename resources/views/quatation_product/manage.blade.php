 @extends('template.template')

@section('content')
<?php
function moneyFormatIndia($num) {
   
    $explrestunits = "" ;
    if(strlen($num)>3)
     {
        $lastthree = substr($num, strlen($num)-3, strlen($num));
        $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
        $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit = str_split($restunits, 2);
        for($i=0; $i<sizeof($expunit); $i++) {
            // creates each of the 2's group and adds a comma to the end
            if($i==0) {
                $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
            } else {
                $explrestunits .= $expunit[$i].",";
            }
        }
        $thecash = $explrestunits.$lastthree;
    } else {
        $thecash = $num;
    }

      return $thecash.".00";
    
    
}
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
         
        @if($role_id == 1)
            <div class="row">
                <div class="col-xs-12">
                    <a style="float:right"class="btn bg-orange btn-flat" href="<?php echo $controller_name;?>/add"> <i class="glyphicon glyphicon-plus icon-white"></i>  New</a>
                
                </div>
            </div>
        @else
            @if($add_permission == 1)
            <div class="row">
                <div class="col-xs-12">
                    <a style="float:right"class="btn bg-orange btn-flat" href="<?php echo $controller_name;?>/add"> <i class="glyphicon glyphicon-plus icon-white"></i>  New</a>
                
                </div>
            </div>
            @endif
        @endif 

          
                    
        <div class="row">
            <div class="col-xs-12">
              
                  <div class="box box-warning">
                  
                    <div class="box-body">
                     	
                      <table id="datatable" class="table table-bordered table-striped">
                         <thead>
                        <tr>
							<th>Sr</th>
							<th width="25%"><?php echo $msgName;?></th>
							<th>HSN/SAC</th>
							<th width="15%">Power</th>
							<th>Remark</th>
							<th>Rate</th>
                            <th width="10%">Manage</th>
                        </tr>
                      </thead>
                         
                       <tbody>
                     
                            @foreach ($result as $key=>$value)
                            <tr>
                                <td>{{ $key+1}}</td>
                                <td>{{ $value->$field }}</td>
                                <td>{{ $value->hsn_code }}</td>
                                <td>{{ $value->power_value." HP" }}</td>
                                <td><?php echo $value->remark;?></td>
                                <td>
                                <?php
                                $number =  $value->rate;;
                                $number_arr = explode(".",$number);
                              
                                $amount = $number_arr[0];
                              
                                $amount = moneyFormatIndia( $amount );
                                echo $amount;
                                ?>
                                </td>
                               
                                <td>
                                    @if($role_id == 1)
                                        <a title="Edit" class="btn bg-purple btn-flat btn-sm" href="<?php echo $controller_name;?>/edit/<?php echo $utility->encode($value->$primary_id); ?>"><i class="glyphicon glyphicon-edit icon-white"></i></a>
									
									    <a class="btn bg-maroon btn-flat btn-sm" onclick="return confirm('Are You Sure To Delete?')" href="<?php echo $controller_name;?>/delete/<?php echo $utility->encode($value->$primary_id); ?>"><i class="glyphicon glyphicon-trash icon-white"></i></a>
                                    @else
                                        @if($edit_permission == 1)
                                            <a title="Edit" class="btn bg-purple btn-flat btn-sm" href="<?php echo $controller_name;?>/edit/<?php echo $utility->encode($value->$primary_id); ?>"><i class="glyphicon glyphicon-edit icon-white"></i></a>
                                        @endif

                                        @if($delete_permission == 1)
                                            <a class="btn bg-maroon btn-flat btn-sm" onclick="return confirm('Are You Sure To Delete?')" href="<?php echo $controller_name;?>/delete/<?php echo $utility->encode($value->$primary_id); ?>"><i class="glyphicon glyphicon-trash icon-white"></i></a>
                                        @endif
                                    @endif
									
                                </td>
                            </tr>
                          @endforeach
                          </tbody>
                       
							
                      </table>
                     </div>
                  </div>
             </div>
        </div>
    </section>
@endsection
