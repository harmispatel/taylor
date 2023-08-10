@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Payment History') }}</h5>
        </div>
        @if(count($paymentHistory) > 0)
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('No')}}</th>
                            <th data-breakpoints="md">{{ translate('Seller')}}</th>
                            <th data-breakpoints="md">{{ translate('Service Name')}}</th>
                            <th data-breakpoints="md">{{ translate('Service Cost')}}</th>
                            <th data-breakpoints="md">{{ translate('Date')}}</th>
                            <th data-breakpoints="md">{{ translate('Payment Status')}}</th>

                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($paymentHistory as $key => $value)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>{{isset($value->seller->name) ? $value->seller->name : '' }}</td>
                                <td>{{isset($value->service->service_name) ? $value->service->service_name : '' }}</td>
                                <td>{{isset($value->service_cost) ? number_format($value->service_cost) : '' }}</td>
                                <td>{{isset($value->repairOrderPayment->created_at) ? date('d-m-Y',strtotime($value->repairOrderPayment->created_at)) : '' }}</td>
                                <td>
                                    @if($value->payment_status == 1)
                                        <span class="badge badge-inline badge-warning">{{ translate('pending')}}</span></td>
                                    @elseif($value->payment_status == 2)
                                        <span class="badge badge-inline badge-danger">{{ translate('un-paid')}}</span></td>
                                    @elseif($value->payment_status == 3)
                                        <span class="badge badge-inline badge-success">{{ translate('paid')}}</span></td>
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
        {!! $paymentHistory->links() !!}
    </div>
@endsection
@section('script')
<script type="text/javascript">
</script>
@endsection
