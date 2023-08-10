    @php
        $getAdminCommission=0;
        $getAdminCommission=App\Models\User::where('user_type','admin')->first()->commission;
        $adminCommission = $repairer_withdraw_requests->amount  *  $getAdminCommission / 100 ;
        $adminCommission=round($adminCommission);
        $totalPayableAmount=$repairer_withdraw_requests->amount - $adminCommission;
    @endphp
<form class="form-horizontal" action="{{ route('commissions.pay_to_repairer') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal-header">
    	<h5 class="modal-title h6">{{translate('Pay to Repairer')}}</h5>
    	<button type="button" class="close" data-dismiss="modal">
    	</button>
    </div>
    <div class="modal-body">
      <table class="table table-striped table-bordered" >
          <tbody>
                <tr>
                    <td>{{ translate('Admin To Pay') }}</td>
                    <td>{{ single_price($adminCommission) }}</td>
                </tr>
                <tr>
                    <td>{{ translate('Requested Amount is ') }}</td>
                    <td>{{ single_price($repairer_withdraw_requests->amount) }}</td>
                </tr>
                <tr>
                    <td>{{ translate('Total Payable Amount') }}</td>
                    <td>{{ single_price($totalPayableAmount) }}</td>
                </tr>
                <tr>
                    <td>{{ translate('Bank Name') }}</td>
                    <td>{{ isset($repairer_withdraw_requests->bankDetails->bank_name) ? $repairer_withdraw_requests->bankDetails->bank_name : '' }}</td>
                </tr>
                <tr>
                    <td>{{ translate('Bank Account Name') }}</td>
                    <td>{{ isset($repairer_withdraw_requests->bankDetails->bank_account_name) ? $repairer_withdraw_requests->bankDetails->bank_account_name : '' }}</td>
                </tr>
                <tr>
                    <td>{{ translate('Bank Account Number') }}</td>
                    <td>{{ isset($repairer_withdraw_requests->bankDetails->bank_account_number) ? $repairer_withdraw_requests->bankDetails->bank_account_number : '' }}</td>
                </tr>
                <tr>
                    <td>{{ translate('IFSC CODE') }}</td>
                    <td>{{ isset($repairer_withdraw_requests->bankDetails->ifsc_code) ? $repairer_withdraw_requests->bankDetails->ifsc_code : '' }}</td>
                </tr>

            </tbody>
        </table>
        <input type="hidden" name="payment_withdraw" value="withdraw_request">
        <input type="hidden" name="withdraw_request_id" value="{{ $repairer_withdraw_requests->id }}">
        <input type="hidden" name="requested_amount" value="{{ $repairer_withdraw_requests->amount }}">
        <input type="hidden" name="paid_to_admin_amount" value="{{ $adminCommission }}">
        <input type="hidden" name="paid_to_repairer_amount" value="{{ $totalPayableAmount }}">
        <input type="hidden" name="paid_by" value="{{ auth()->user()->name }}">
        <input type="hidden" name="repairer_id" value="{{ $repairer_withdraw_requests->user_id }}">
        <div class="form-group row">
            <label class="col-sm-3 col-from-label" for="amount">{{translate('Requested Amount')}}</label>
            <div class="col-sm-9">
                <input type="number" lang="en" min="0" step="0.01"  disabled id="amount" value="{{ $repairer_withdraw_requests->amount }}" class="form-control" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-from-label" for="payment_option">{{translate('Payment Method')}}</label>
            <div class="col-sm-9">
                <select name="payment_option" id="payment_option" class="form-control demo-select2-placeholder" required>
                    <option value="">{{translate('Select Payment Method')}}</option>
                    <option value="cash">{{translate('Cash')}}</option>
                    <option value="bank_payment">{{translate('Bank Payment')}}</option>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">{{translate('Pay')}}</button>
        <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
    </div>
</form>

<script>
$(document).ready(function(){
    $('#payment_option').on('change', function() {
      if ( this.value == 'bank_payment')
      {
        $("#txn_div").show();
      }
      else
      {
        $("#txn_div").hide();
      }
    });
    $("#txn_div").hide();
    AIZ.plugins.bootstrapSelect('refresh');
});
</script>
