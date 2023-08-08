@extends('seller.layouts.app')

@section('panel_content')

    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">{{ translate('Repairer Service Details') }}</h1>
        </div>

        <div class="card-body">
            <div class="row gutters-5">
                <div class="col text-md-left text-center">
                </div>
            <!--Assign Delivery Boy-->
            </div>
            <form class="form-default find-measurer-form" role="form" action="{{ route('seller.repairStore.bookService') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="customer_id" value="{{$customerId}}">
                <input type="hidden" name="repairer_id" value="{{$repairerId}}">
                <input type="hidden" name="seller_id" value="{{auth()->user()->id}}">
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{translate('Product Name')}}</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" disabled name="product_id" value="{{$order->product->name}}">
                        <input type="hidden" class="form-control"  name="product_id" value="{{$order->product->id}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{translate('measurement ID')}}</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="measurement_id"
                            placeholder="{{ translate('measurement ID') }}"  required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{translate('Services')}}</label>
                    <div class="col-md-4">
                        <select class="form-control" name="service_id" onchange="getServiceCost(this.value)" id="service_id" required>
                            @if(count($service) > 0)
                            <option  value="">{{translate('Select Service')}}</option>
                                @foreach($service as $value)
                                    <option data-service-id="{{$value->id}}" data-order-id="{{$value->id}}"  value="{{$value->id}}">{{$value->service_name}}</option>
                                @endforeach
                            @endif

                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{translate('Service Cost')}}</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="service_cost" id="service_cost" value="0" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{translate('Date From')}}</label>
                    <div class="col-md-4">
                        <input type="date" class="form-control" name="date_from"
                            required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{translate('Date To')}}</label>
                    <div class="col-md-4">
                        <input type="date" class="form-control" name="date_to"
                            required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{translate('Picture of customer')}}</label>
                    <div class="col-md-4">
                        <input type="file" class="form-control" name="image" required>
                    </div>
                </div>
                <div class="form-group row">
                    <button type="submit" class="btn btn-primary">{{translate('Book Service')}}</button>
                </div>
            </form>
            <a title="Start Conversation" href="{{ route('seller.model_conversations_create',['model_id' => encrypt(@$repairerId) ])}}" class="btn btn-primary">{{ translate('Start Conversation') }}</a>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function getServiceCost(serviceId) {
            $.post('{{ route('seller.repairStore.gerServiceCost') }}', {
                _token: '{{ @csrf_token() }}',
                serviceId: serviceId,
            }, function(data) {
                $('#service_cost').val(data.serviceCost);
            });
        }
    </script>
@endsection
