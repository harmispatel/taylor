@extends('frontend.layouts.user_panel')

@section('panel_content')
    @php
        $getAdminCommission=0;
        $getAdminCommission=App\Models\User::where('user_type','admin')->first()->commission;
    @endphp
    <input type="hidden" name="admin_commission_percentage" id="admin_commission_percentage" value="{{$getAdminCommission}}">
    <input type="hidden" name="final_amount" id="final_amount" value="{{$finalAmount}}">
    <div class="aiz-titlebar mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Money Withdraw') }}</h1>
        </div>
      </div>
    </div>

    <div class="row gutters-10">
        <div class="col-md-4 mb-3 ml-auto" >
            <div class="bg-grad-1 text-white rounded-lg overflow-hidden">
              <span class="size-30px rounded-circle mx-auto bg-soft-primary d-flex align-items-center justify-content-center mt-3">
                  <i class="las la-dollar-sign la-2x text-white"></i>
              </span>
              <div class="px-3 pt-3 pb-3">
                  <div class="h4 fw-700 text-center">{{single_price($finalAmount)}}</div>
                  <div class="opacity-50 text-center">{{ translate('Pending Balance') }}</div>
              </div>
            </div>
        </div>
        <div class="col-md-4 mb-3 mr-auto" >
          <div class="p-3 rounded mb-3 c-pointer text-center bg-white shadow-sm hov-shadow-lg has-transition" onclick="show_request_modal()">
              <span class="size-60px rounded-circle mx-auto bg-secondary d-flex align-items-center justify-content-center mb-3">
                  <i class="las la-plus la-3x text-white"></i>
              </span>
              <div class="fs-18 text-primary">{{ translate('Send Withdraw Request') }}</div>
          </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Withdraw Request history')}}</h5>
        </div>
          <div class="card-body">
              <table class="table aiz-table mb-0">
                  <thead>
                      <tr>
                          <th>#</th>
                          <th>{{ translate('Date') }}</th>
                          <th>{{ translate('Amount')}}</th>
                          <th data-breakpoints="lg">{{ translate('Status')}}</th>
                          <th data-breakpoints="lg" width="60%">{{ translate('Message')}}</th>
                      </tr>
                  </thead>
                  <tbody>
                      @foreach ($repairer_withdraw_requests as $key => $repairer_withdraw_request)
                          <tr>
                              <td>{{ $key+1 }}</td>
                              <td>{{ date('d-m-Y', strtotime($repairer_withdraw_request->created_at)) }}</td>
                              <td>{{ single_price($repairer_withdraw_request->amount) }}</td>
                              <td>
                                  @if ($repairer_withdraw_request->status == 2)
                                      <span class=" badge badge-inline badge-success" >{{ translate('Paid')}}</span>
                                  @else
                                      <span class=" badge badge-inline badge-info" >{{ translate('Pending')}}</span>
                                  @endif
                              </td>
                              <td>
                                  {{ $repairer_withdraw_request->message }}
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
    <div class="modal fade" id="request_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Send A Withdraw Request') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>

                    <form class="" action="{{ route('repairer.money_withdraw_request.store') }}" method="post">
                        @csrf
                        <div class="modal-body gry-bg px-3 pt-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>{{ translate('Amount')}} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <input type="number" lang="en" class="form-control mb-3" name="amount" id="amount" min="50" max="{{$finalAmount}}"  placeholder="{{ translate('Amount') }}" required>
                                </div>
                            </div>
                            <div class="row pay_to_admin" style="display:none;">
                                <div class="col-md-3">
                                    <label>{{ translate('Pay To Admin')}}{{'('.$getAdminCommission.'%)'}}</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="number" disabled lang="en" class="form-control mb-3"  id="pay_to_admin">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>{{ translate('Message')}}</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="message" rows="8" class="form-control mb-3"></textarea>
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-sm btn-primary">{{translate('Send')}}</button>
                            </div>
                        </div>
                    </form>

                    <!-- <div class="modal-body gry-bg px-3 pt-3">
                        <div class="p-5 heading-3">
                            {{ translate('You do not have enough balance to send withdraw request') }}
                        </div>
                    </div> -->

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('#request_modal').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
        })
        function show_request_modal(){
            $('#request_modal').modal('show');
        }
        function show_message_modal(id){
            $.post('{{ route('withdraw_request.message_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#message_modal .modal-content').html(data);
                $('#message_modal').modal('show', {backdrop: 'static'});
            });
        }
        $("input").keyup(function(){
           var  amount=$(this).val();
           var  adminCommissionPercentage=$('#admin_commission_percentage').val();
           var adminCommission = amount *  adminCommissionPercentage / 100 ;
           adminCommission=Math.round(adminCommission);
           $('#pay_to_admin').val(adminCommission);
           $('.pay_to_admin').show();
            //$("#amount").css("background-color", "green");
        });

    </script>
@endsection
