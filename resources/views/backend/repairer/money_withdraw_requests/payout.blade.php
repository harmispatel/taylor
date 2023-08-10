@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Repairer Payments')}}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th data-breakpoints="md">#</th>
                        <th data-breakpoints="md">{{translate('Date')}}</th>
                        <th>{{translate('Repairer')}}</th>
                        <th>{{translate('Requested Amount')}}</th>
                        <td>{{ translate('Admin To Paid') }}</td>
                        <th>{{translate('Total Amount To Paid')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payouts as $key => $payout)
                        <tr>
                            <td>{{ ($key+1)}}</td>
                            <td>{{ $payout->created_at }}</td>
                            <td>{{isset($payout->repairer->name) ? $payout->repairer->name :'' }}</td>
                            <td>{{ single_price($payout->requested_amount) }}</td>
                            <td>{{ single_price($payout->paid_to_admin_amount) }}</td>
                            <td>{{ single_price($payout->paid_to_repairer_amount) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $payouts->links() }}
            </div>
        </div>
    </div>

@endsection

