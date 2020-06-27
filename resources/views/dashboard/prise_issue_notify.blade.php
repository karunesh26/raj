
@if(! empty($prise_issue_notify))
<div class="col-lg-12">
    <div class="box box-warning">
        <div class="col-xs-12">
            <h3>Price / Technical Issue</h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th width="5%">Sr. No.</th>
                        <th width="8%">Issue Date</th>
                        <th width="10%">Quotation Number</th>
                        <th width="15%">Client Name</th>
                        <th width="15%">Inquiry For</th>
                        <th width="20%">Remark</th>
                        <th width="10%">Follow-Up By</th>
                        <th width="10%">Allot To</th>
                        <th width="15%">Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prise_issue_notify as $key=>$value)
                    <tr>
                        <td>{{ $key+1}}</td>
                        <td>{{ date('d-m-Y',strtotime($value->added_date)) }}</td>
                        <td>{{ $value->quotation_no}}</td>
                        <td>{{ $value->prefix.' '.$value->name}}</td>
                        <td>{{ $value->product_name }}</td>
                        <td>{{ $value->remark}}</td>
                        <td>{{ $value->follow_up_by}}</td>
                        <td>{{ $value->allot_to}}</td>
                        <td>
                            <a href="{{ 'Follow_up/'.$utility->encode($value->inquiry_id) }}" target="_blank" class="btn bg-maroon btn-sm" ><i class="glyphicon glyphicon-eye-open icon-white"></i> View</a>

                            <a href="{{ 'Dashboard/clear/'.$utility->encode($value->id).'/'.$utility->encode('prise') }}"  class="btn bg-purple btn-sm" ><i class="fa fa-window-close" aria-hidden="true"></i> Clear</a>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>
@endif