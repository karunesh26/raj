@if(isset($pending_enq))
    @if(count($pending_enq))
    <table id="datatable" class="table table-bordered color_table">
        <thead>
            <tr>
                <th colspan=3>Allotement</th>
            </tr>
            <tr>
                <th>Sr</th>
                <th>Quotation No.</th>
                <th>Customer</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pending_enq as $key=>$value)
            <tr id="follow_up_btn" <?php echo ($value->follow_up_status != 0) ? 'style="background:#00FA9A" class="active_tr"' : ''; ?>>
                <td align="center">
                    {{ $key+1}}<br />
                    <input type="hidden" name="follow_up_inquiry_id" id="follow_up_inquiry_id" value="{{$utility->encode($value->inquiry_id) }}" />
                </td>
                <td>{{ $value->quatation_no}}</td>
                <td>{{ $value->name}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
@endif
@if(isset($visit_done))
    @if(count($visit_done))
    <table id="datatable" class="table table-bordered color_table">
        <thead>
            <tr>
                <th colspan=3>Allotement</th>
            </tr>
            <tr>
                <th>Sr</th>
                <th>Quotation No.</th>
                <th>Customer</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($visit_done as $key=>$value)
            <tr id="follow_up_btn" <?php echo ($value->follow_up_status != 0) ? 'style="background:#00FA9A" class="active_tr"' : ''; ?>>
                <td align="center">
                    {{ $key+1}}<br />
                    <input type="hidden" name="follow_up_inquiry_id" id="follow_up_inquiry_id" value="{{$utility->encode($value->inquiry_id) }}" />
                </td>
                <td>{{ $value->quatation_no}}</td>
                <td>{{ $value->name}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
@endif
@if(isset($allotement_data))
    @if(count($allotement_data))
    <table id="datatable" class="table table-bordered color_table">
        <thead>
            <tr>
                <th colspan=3>Allotement</th>
            </tr>
            <tr>
                <th>Sr</th>
                <th>Quotation No.</th>
                <th>Customer</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($allotement_data as $key=>$value)

            <tr id="follow_up_btn" <?php echo ($value->follow_up_status != 0) ? 'style="background:#00FA9A" class="active_tr"' : ''; ?>>
                <td align="center">
                    {{ $key+1}}<br />
                    <input type="hidden" name="follow_up_inquiry_id" id="follow_up_inquiry_id" value="{{$utility->encode($value->inquiry_id) }}" />
                </td>
                <td>{{ $value->quatation_no}}</td>
                <td>{{ $value->name}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
@endif
@if(isset($follow_up_data))
    @if(count($follow_up_data))
    <table id="datatable" class="table table-bordered color_table">
        <thead>
            <tr>
                <th colspan=3>Follow-Up</th>
            </tr>
            <tr>
                <th>Sr</th>
                <th>Quotation No.</th>
                <th>Customer</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($follow_up_data as $key=>$value)
            <tr id="follow_up_btn" <?php echo ($value->follow_up_status != 0) ? 'style="background:#00FA9A" class="active_tr"' : ''; ?>>
                <td align="center">
                    {{ $key+1}}<br />
                    <input type="hidden" name="follow_up_inquiry_id" id="follow_up_inquiry_id" value="{{$utility->encode($value->inquiry_id)}}" />
                </td>
                <td>{{ $value->quatation_no}}</td>
                <td>{{ $value->name}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
@endif
@if(isset($inquiry))
    @if(count($inquiry))
    <table id="datatable" class="table table-bordered color_table">
        <thead>
            <tr>
                <th colspan=3>All</th>
            </tr>
            <tr>
                <th>Sr</th>
                <th>Quotation No.</th>
                <th>Customer</th>
            </tr>
        </thead>
        <tbody>
            @php $t=1; @endphp
            @foreach($inquiry as $key=>$value)
            <tr id="follow_up_btn" <?php echo ($value->follow_up_status != 0) ? 'style="background:#00FA9A" class="active_tr"' : ''; ?>>
                <td align="center">
                    {{ $t }}<br />
                    <input type="hidden" name="follow_up_inquiry_id" id="follow_up_inquiry_id" value="{{$utility->encode($value->inquiry_id)}}" />
                </td>
                <td>{{ $value->quatation_no}}</td>
                <td>{{ $value->name}}</td>
            </tr>
            @php $t++; @endphp
            @endforeach
            @foreach($inq_follow_up as $key=>$value)
            <tr id="follow_up_btn" <?php echo ($value->follow_up_status != 0) ? 'style="background:#00FA9A" class="active_tr"' : ''; ?>>
                <td align="center">
                    {{ $t }}<br />
                    <input type="hidden" name="follow_up_inquiry_id" id="follow_up_inquiry_id" value="{{$utility->encode($value->inquiry_id)}}" />
                </td>
                <td>{{ $value->quatation_no}}</td>
                <td>{{ $value->name}}</td>
            </tr> 
            @php $t++; @endphp
            @endforeach
        </tbody>
    </table>
    @endif
@endif
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#datatable').DataTable({
            scrollY: '70vh',
            scrollCollapse: true,
            paging: false
        });
    });
</script>
