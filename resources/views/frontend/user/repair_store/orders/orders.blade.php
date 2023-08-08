@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('My orders') }}</h5>
        </div>
        @if(count($orders) > 0)
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('No')}}</th>
                            <th data-breakpoints="md">{{ translate('Seller')}}</th>
                            <th data-breakpoints="lg">{{ translate('Product Name')}}</th>
                            <th data-breakpoints="lg">{{ translate('Payment Status')}}</th>
                            <th data-breakpoints="md">{{ translate('Service Name')}}</th>
                            <th data-breakpoints="md">{{ translate('Service Cost')}}</th>
                            <th data-breakpoints="lg">{{ translate('Wearing Item')}}</th>
                            <th data-breakpoints="lg">{{ translate('Date From')}}</th>
                            <th data-breakpoints="lg">{{ translate('Date To')}}</th>
                            <th data-breakpoints="md">{{ translate('Status')}}</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($orders as $key => $value)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>{{isset($value->seller->name) ? $value->seller->name : '' }}</td>
                                <td>{{isset($value->product->name) ? $value->product->name : '' }}</td>
                                <td>
                                    @if($value->payment_status == 1)
                                        <span class="badge badge-inline badge-warning">{{ translate('pending')}}</span></td>
                                    @elseif($value->payment_status == 2)
                                        <span class="badge badge-inline badge-danger">{{ translate('un-paid')}}</span></td>
                                    @elseif($value->payment_status == 3)
                                        <span class="badge badge-inline badge-success">{{ translate('paid')}}</span></td>
                                    @endif
                                </td>
                                <td>{{isset($value->service->service_name) ? $value->service->service_name : '' }}</td>
                                <td>{{isset($value->service_cost) ? number_format($value->service_cost) : '' }}</td>
                                <td><img src="{{ url('/public/uploads/repairer_order') . '/' . $value->image }}" width="50px"class="shadow-1-strong rounded mb-4"
                                        alt="Boat on Calm Water" /></td>
                                <td>{{isset($value->date_from) ? date('d-m-Y',strtotime($value->date_from)) : '' }}</td>
                                <td>{{isset($value->date_to) ? date('d-m-Y',strtotime($value->date_to)) : '' }}</td>
                                <td>
                                    @if($value->status == 3)
                                        <span class="badge badge-inline badge-danger">{{ translate('Rejected')}}</span>
                                    @elseif($value->status == 5)
                                        <span class="badge badge-inline badge-success">{{ translate('Completed')}}</span>
                                    @else
                                        @if($value->status == 2 || $value->status == 4)
                                            <select class="form-control" name="status" id="status" onchange="updateOrderStatus(this.value,{{$value->id}})">
                                            <option value="2" @if ($value->status == '2') selected @endif>
                                                {{ translate('Pending Preparation') }}</option>
                                            <option value="4" @if ($value->status == '4') selected @endif>
                                                {{ translate('reparation Started') }}</option>
                                            <option value="5" @if ($value->status == '5') selected @endif>
                                                {{ translate('reparation Completed') }}</option>
                                        </select>
                                        @endif
                                        @if($value->status == 1)
                                            <button class="btn m-action-btn btn-xs dropdown-toggle " type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a href="javascript:;"  onclick="actionConfirmCheck(2,{{$value->id}})" class="dropdown-item">Accept</a>
                                                <a onclick="actionConfirmCheck(3,{{$value->id}})" href="javascript:;" class="dropdown-item" >Reject</a>
                                            </div>
                                        @endif
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
        <center>No Data Available</center>
        @endif
        {!! $orders->links() !!}
    </div>
    <form action="{{ route('repairStore.acceptOrder') }}" method="post">
        @csrf
        <input type="hidden" name="id" id="orderId">
        <input type="hidden" name="orderStatus" id="orderStatus">
        <div class="modal fade" id="check-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-zoom modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title fw-600"></h6>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true"></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        <div class="col-md-8">
                        <p id="warning-text"> </p>
                        </div>
                        <label class="aiz-checkbox" style="margin: 55px;">
                            <input type="checkbox" required >
                            <span class="aiz-square-check"></span>

                        </label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block fw-600">{{  translate('Submit') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('script')
<script type="text/javascript">
    function updateOrderStatus(status,id){

        $.post('{{ route('updateOrderStatus') }}', {
            _token: '{{ @csrf_token() }}',
            id: id,status: status
        }, function(data) {
           location.reload();
        });
    }
    function actionConfirmCheck(status,id){
        $('#orderStatus').val(status);
        $('#orderId').val(id);
        if(status==2){
           $('#warning-text').text('Are You Sure? Mandatorily checked in order to accept this order?');
           $('.modal-title').text('Accept Order');
        }
        else{
            $('#warning-text').text('Are You Sure? Mandatorily checked in order to reject this order?');
            $('.modal-title').text('Reject Order');
        }
        $('#check-modal').modal();
    }


</script>
@endsection
