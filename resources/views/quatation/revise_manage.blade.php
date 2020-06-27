@extends('template.template')
@section('content')


 <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> <?php echo $msgName;?> Details</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('Dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ URL::to($controller_name) }}">Quatation</a></li>
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
                     	
                      <table id="datatable" class="table table-bordered table-striped">
                         <thead>
                        <tr>
							<th>Sr</th>
							<th>Quatation No</th>
							<th>Rivise Quatation No</th>
							<th>Rivise Quatation Date</th>
                            <th>Manage</th>
                        </tr>
                      </thead>
                         
                       <tbody>
                            @foreach ($result as $key=>$value)
                            <tr>
                                <td>{{ $key+1}}</td>
                                <td>{{ $value->quatation_no}}</td>
                                <td>{{ $value->revise_quatation_no}}</td>
                                <td>{{ date('d-m-Y',strtotime($value->revise_date))}}</td>
                                <td>
									<a target="_blank" class="btn bg-olive btn-flat btn-sm" href="{{ URL::to( $controller_name.'/revise_quatation_view/'.$utility->encode($value->revise_id)) }}"> <i class="glyphicon glyphicon-eye-open icon-white"></i> View</a>
									
									<a target="_blank" class="btn btn-danger btn-flat btn-sm" href="{{ URL::to( $controller_name.'/revise_quatation_print/'.$utility->encode($value->revise_id).'/'.$utility->encode('print').'/'.$utility->encode('yes')) }}"> <i class="glyphicon glyphicon-print icon-white"></i> Latterhead Print</a>
									<a target="_blank" class="btn btn-danger btn-flat btn-sm" href="{{ URL::to( $controller_name.'/revise_quatation_print/'.$utility->encode($value->revise_id).'/'.$utility->encode('print').'/'.$utility->encode('no')) }}"> <i class="glyphicon glyphicon-print icon-white"></i> W/O Latterhead Print</a>
									<a class="btn btn-info btn-flat btn-sm" href="{{ URL::to( $controller_name.'/revise_quatation_print/'.$utility->encode($value->revise_id).'/'.$utility->encode('download').'/'.$utility->encode('yes')) }}"> <i class="glyphicon glyphicon-print icon-white"></i> Latterhead Download</a>
									<a class="btn btn-info btn-flat btn-sm" href="{{ URL::to( $controller_name.'/revise_quatation_print/'.$utility->encode($value->revise_id).'/'.$utility->encode('download').'/'.$utility->encode('no')) }}"> <i class="glyphicon glyphicon-print icon-white"></i> W/O Latterhead Download</a>
									
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
<script>
$(document).ready(function () {
	 
	 $('.datatable').DataTable({
		  'paging'      : true,
		  'lengthChange': true,
		  'searching'   : true,
		  'ordering'    : true,
		  'info'        : true,
		  'autoWidth'   : true
		})
});
</script>
@endsection
  