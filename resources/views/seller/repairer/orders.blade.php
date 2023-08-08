@extends('seller.layouts.app')

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
                            <th data-breakpoints="lg">{{ translate('Product Name')}}</th>
                            <th data-breakpoints="md">{{ translate('Service Name')}}</th>
                            <th data-breakpoints="md">{{ translate('Service Cost')}}</th>
                            <th data-breakpoints="lg">{{ translate('Wearing Item')}}</th>
                            <th data-breakpoints="lg">{{ translate('Date From')}}</th>
                            <th data-breakpoints="lg">{{ translate('Date To')}}</th>
                            <th data-breakpoints="md">{{ translate('Status')}}</th>
                            <th data-breakpoints="md">{{ translate('Payment Status')}}</th>
                            <th data-breakpoints="lg">{{ translate('Action')}}</th>

                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($orders as $key => $value)
                            <tr>
                               @php
                                    $amount=number_format($value->service_cost);
                               @endphp
                                <td>{{$key + 1}}</td>
                                <td>{{isset($value->product->name) ? $value->product->name : '' }}</td>
                                <td>{{isset($value->service->service_name) ? $value->service->service_name : '' }}</td>
                                <td>{{isset($value->service_cost) ? number_format($value->service_cost) : '' }}</td>
                                <td><img src="{{ url('/public/uploads/repairer_order') . '/' . $value->image }}" width="50px"class="shadow-1-strong rounded mb-4"
                                        alt="Boat on Calm Water" /></td>
                                <td>{{isset($value->date_from) ? date('d-m-Y',strtotime($value->date_from)) : '' }}</td>
                                <td>{{isset($value->date_to) ? date('d-m-Y',strtotime($value->date_to)) : '' }}</td>
                                <td>
                                    @if($value->status == 1)
                                        <span class="badge badge-inline badge-warning">{{ translate('Pending')}}</span>
                                    @elseif($value->status == 2)
                                        <span class="badge badge-inline badge-info">{{ translate('Order Confirmed')}}</span>
                                    @elseif($value->status == 3)
                                        <span class="badge badge-inline badge-danger">{{ translate('Rejected')}}</span>
                                    @elseif($value->status == 4)
                                        <span class="badge badge-inline badge-primary">{{ translate('Reparation Started')}}</span>
                                    @elseif($value->status == 5)
                                        <span class="badge badge-inline badge-success">{{ translate('Reparation completed')}}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($value->payment_status == 1)
                                        <span class="badge badge-inline badge-warning">{{ translate('pending')}}</span></td>
                                    @elseif($value->payment_status == 2)
                                        <span class="badge badge-inline badge-danger">{{ translate('un-paid')}}</span></td>
                                    @elseif($value->payment_status == 3)
                                        <span class="badge badge-inline badge-success">{{ translate('paid')}}</span></td>
                                    @endif
                                <td>
                                @if($value->payment_status == 2)
                                    <button onclick="selectPaymentMethod({{$value->id}},{{$amount}})" class="btn btn-primary make_payment">Make Payment</button>
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
    <!-- Online payment Modal -->
    <div class="modal fade" id="payment_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Make Payment') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="" id="package_payment_form" action="{{ route('payment.makeRepairOrderPayment') }}" method="post">
                    @csrf
                    <input type="hidden" name="amount" id="amount">
                    <input type="hidden" name="repairOrder_id" id="repairId">
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Payment Method')}}</label>
                            </div>
                            <div class="col-md-10">
                                <div class="mb-3">
                                    <select class="form-control selectpicker" data-live-search="true" name="payment_option">
                                        @if(get_setting('paypal_payment') == 1)
                                            <option value="paypal">{{ translate('Paypal')}}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button type="button" class="btn btn-sm btn-secondary transition-3d-hover mr-1" data-dismiss="modal">{{translate('cancel')}}</button>
                            <button type="submit" class="btn btn-sm btn-primary transition-3d-hover mr-1">{{translate('Confirm')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script type="text/javascript">
function selectPaymentMethod(repairId,amount){
    $('#repairId').val(repairId);
    $('#amount').val(amount);
    $('#payment_model').modal('show');
}
</script>
@endsection
