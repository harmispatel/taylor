@extends('backend.layouts.app')
@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Repairer Withdraw Request')}}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th data-breakpoints="lg">#</th>
                        <th data-breakpoints="lg">{{translate('Date')}}</th>
                        <th>{{translate('Repairer')}}</th>
                        <th data-breakpoints="lg">{{translate('Total Amount to Pay')}}</th>
                        <th>{{translate('Requested Amount')}}</th>
                        <th data-breakpoints="lg" width="40%">{{ translate('Message') }}</th>
                        <th data-breakpoints="lg">{{ translate('Status') }}</th>
                        <th data-breakpoints="lg" width="15%" class="text-right">{{translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($repairer_withdraw_requests as $key => $repairer_withdraw_request)

                        <tr>
                            <td>{{ ($key+1) + ($repairer_withdraw_requests->currentPage() - 1)*$repairer_withdraw_requests->perPage() }}</td>
                            <td>{{ $repairer_withdraw_request->created_at }}</td>
                            <td>{{isset($repairer_withdraw_request->repairer->name) ? $repairer_withdraw_request->repairer->name :'' }}</td>
                            <td>{{ single_price($repairer_withdraw_request->amount) }}</td>
                            <td>{{ single_price($repairer_withdraw_request->amount) }}</td>
                            <td>
                                {{ $repairer_withdraw_request->message }}
                            </td>
                            <td>
                                @if ($repairer_withdraw_request->status == 2)
                                <span class="badge badge-inline badge-success">{{translate('Paid')}}</span>
                                @else
                                <span class="badge badge-inline badge-info">{{translate('Pending')}}</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a onclick="show_seller_payment_modal('{{$repairer_withdraw_request->user_id}}','{{ $repairer_withdraw_request->id }}');" class="btn btn-soft-warning btn-icon btn-circle btn-sm" href="javascript:void(0);" title="{{ translate('Pay Now') }}">
                                    <i class="las la-money-bill"></i>
                                </a>
                                <a onclick="show_message_modal('{{ $repairer_withdraw_request->id }}');" class="btn btn-soft-success btn-icon btn-circle btn-sm" href="javascript:void(0);" title="{{ translate('Message View') }}">
                                    <i class="las la-eye"></i>
                                </a>
                                <a href="{{route('sellers.payment_history', encrypt($repairer_withdraw_request->user_id))}}" class="btn btn-soft-primary btn-icon btn-circle btn-sm"  title="{{ translate('Payment History') }}">
                                    <i class="las la-history"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $repairer_withdraw_requests->links() }}
            </div>
        </div>
    </div>

@endsection

@section('modal')
<!-- payment Modal -->
<div class="modal fade" id="payment_modal">
  <div class="modal-dialog">
    <div class="modal-content" id="payment-modal-content">

    </div>
  </div>
</div>


<!-- Message View Modal -->
<div class="modal fade" id="message_modal">
  <div class="modal-dialog">
    <div class="modal-content" id="message-modal-content">

    </div>
  </div>
</div>


@endsection



@section('script')
  <script type="text/javascript">
      function show_seller_payment_modal(user_id, repairer_withdraw_request_id){
          $.post('{{ route('repairer.withdraw_request.payment_modal') }}',{_token:'{{ @csrf_token() }}', user_id:user_id, repairer_withdraw_request_id:repairer_withdraw_request_id}, function(data){
              $('#payment-modal-content').html(data);
              $('#payment_modal').modal('show', {backdrop: 'static'});
            //  $('.demo-select2-placeholder').select2();
          });
      }

      function show_message_modal(id){
          $.post('{{ route('withdraw_request.message_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
              $('#message-modal-content').html(data);
              $('#message_modal').modal('show', {backdrop: 'static'});
          });
      }
  </script>

@endsection
