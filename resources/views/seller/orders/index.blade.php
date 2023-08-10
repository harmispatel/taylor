@extends('seller.layouts.app')

@section('panel_content')

<div class="card">
    <form id="sort_orders" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col text-center text-md-left">
                <h5 class="mb-md-0 h6">{{ translate('Orders') }}</h5>
            </div>
            <div class="col-md-3 ml-auto">
                <select class="form-control aiz-selectpicker" data-placeholder="{{ translate('Filter by Payment Status')}}" name="payment_status" onchange="sort_orders()">
                    <option value="">{{ translate('Filter by Payment Status')}}</option>
                    <option value="paid" @isset($payment_status) @if($payment_status=='paid' ) selected @endif @endisset>{{ translate('Paid')}}</option>
                    <option value="unpaid" @isset($payment_status) @if($payment_status=='unpaid' ) selected @endif @endisset>{{ translate('Un-Paid')}}</option>
                </select>
            </div>

            <div class="col-md-3 ml-auto">
                <select class="form-control aiz-selectpicker" data-placeholder="{{ translate('Filter by Payment Status')}}" name="delivery_status" onchange="sort_orders()">
                    <option value="">{{ translate('Filter by Deliver Status')}}</option>
                    <option value="pending" @isset($delivery_status) @if($delivery_status=='pending' ) selected @endif @endisset>{{ translate('Pending')}}</option>
                    <option value="confirmed" @isset($delivery_status) @if($delivery_status=='confirmed' ) selected @endif @endisset>{{ translate('Confirmed')}}</option>
                    <option value="on_delivery" @isset($delivery_status) @if($delivery_status=='on_delivery' ) selected @endif @endisset>{{ translate('On delivery')}}</option>
                    <option value="delivered" @isset($delivery_status) @if($delivery_status=='delivered' ) selected @endif @endisset>{{ translate('Delivered')}}</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="from-group mb-0">
                    <input type="text" class="form-control" id="search" name="search" @isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type Order code & hit Enter') }}">
                </div>
            </div>
        </div>
    </form>

    @if (count($orders) > 0)
    <div class="card-body p-3">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ translate('Order Code')}}</th>
                    <th data-breakpoints="lg">{{ translate('Num. of Products')}}</th>
                    <th data-breakpoints="lg">{{ translate('Customer')}}</th>
                    <th data-breakpoints="md">{{ translate('Amount')}}</th>
                    <th data-breakpoints="lg">{{ translate('Delivery Status')}}</th>
                    <th>{{ translate('Payment Status')}}</th>
                    <th class="text-right">{{ translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $key => $order_id)
                @php
                $order = \App\Models\Order::find($order_id->id);
                @endphp
                @if($order != null)
                <tr>
                    <td>
                        {{ $key+1 }}
                    </td>
                    <td>
                        <a href="#{{ $order->code }}" onclick="show_order_details({{ $order->id }})">{{ $order->code }}</a>
                    </td>
                    <td>
                        {{ count($order->orderDetails->where('seller_id', Auth::user()->id)) }}
                    </td>
                    <td>
                        @if ($order->user_id != null)
                        {{ optional($order->user)->name }}
                        @else
                        {{ translate('Guest') }} ({{ $order->guest_id }})
                        @endif
                    </td>
                    <td>
                        {{ single_price($order->grand_total) }}
                    </td>
                    <td>
                        @php
                        $status = $order->delivery_status;
                        $addrdressId=isset($order->user->customer_addresses->id) ? $order->user->customer_addresses->id : '';
                        @endphp
                        {{ translate(ucfirst(str_replace('_', ' ', $status))) }}

                        <button type="button" data-toggle="modal" data-target="#find-nearby-repairer" data-order-id="{{$order->id}}" data-customer-id="{{$order->user_id}}" data-address-id="{{$addrdressId}}" class="f-r-btn-nearby btn btn-soft-primary mr-2 find-measurer-btn fw-600">
                            <i class="las la-arrow-right"></i>
                            <span class="d-none d-md-inline-block">{{ translate('Find nearby repairer for customer')}}</span>
                        </button>
                    </td>
                    <td>
                        @if ($order->payment_status == 'paid')
                        <span class="badge badge-inline badge-success">{{ translate('Paid')}}</span>
                        @else
                        <span class="badge badge-inline badge-danger">{{ translate('Unpaid')}}</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <a href="{{ route('seller.orders.show', encrypt($order->id)) }}" class="btn btn-soft-info btn-icon btn-circle btn-sm" title="{{ translate('Order Details') }}">
                            <i class="las la-eye"></i>
                        </a>
                        <a href="{{ route('seller.invoice.download', $order->id) }}" class="btn btn-soft-warning btn-icon btn-circle btn-sm" title="{{ translate('Download Invoice') }}">
                            <i class="las la-download"></i>
                        </a>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $orders->links() }}
        </div>
    </div>
    @endif
</div>
<div class="modal fade" id="find-nearby-repairer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-zoom" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-600">{{ translate('Find Nearby Repairer')}}</h6>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">

                <div class="p-3">
                    <form class="form-default find-measurer-form" role="form" action="{{ route('seller.repairer.serviceDetails') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Nearby Repairer')}}</label>
                            </div>
                            <div class="col-md-10">
                                <div class="mb-3">
                                    <!-- <div class="btn-group w-100 position-relative repair_dropdown">
                                        <button type="button" class="btn dropdown-toggle w-100 justify-content-between" data-toggle="dropdown" aria-expanded="false">
                                            <div class="d-flex align-items-center">
                                                <div class="repair_img">
                                                    <img src="{{asset('public/assets/img/avatar-place.png')}}">
                                                </div>
                                                <div class="repair_info">
                                                    <h3>Select Repairer</h3>
                                                </div>
                                            </div>
                                        </button>
                                        <div class="dropdown-menu w-100">
                                            <ul class="repairer-dropdown">

                                            </ul>
                                        </div>
                                    </div> -->
                                    <select class="form-control"  name="repairer_id" id="repairer-dropdown" required>

                                    </select>

                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="order_id" id="order_id">
                        <input type="hidden" name="customer_id" id="customer_id">
                        <div class="mb-5">
                            <button type="submit" class="btn btn-primary btn-block fw-600">Repairer account details</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

<script type="text/javascript">
    function sort_orders(el) {
        $('#sort_orders').submit();
    }
    $(document).on('click', '.f-r-btn-nearby', function() {
        var customerID = $(this).data('customer-id');
        var addressID = $(this).data('address-id');
        var orderId = $(this).data('order-id');
        $('#order_id').val(orderId);
        $('#customer_id').val(customerID);
        var url = "{{ route('seller.requests.nearby_repairer', ": id ") }}";
        url = url.replace(':id', customerID);
        $.ajax({
            type: 'GET',
            url: url,
            data: {
                user_id: customerID,
                address_id: addressID
            },
            success: function(data) {
                $('#repairer-dropdown').html('');
                var html=''
                $.each(data.repairer, function(key, value) {
                    $("#repairer-dropdown").append('<option value="' + value
                        .user_id + '">' + value.name + '    ( ' + value.distance.toFixed(2) + ' Km )</option>');
                   // console.log(value.file_name);
                //    var imgPath="{{asset('public/assets/img/avatar-place.png')}}";
                //    if(value.file_name != ''){
                //     var imgPath="{{asset('public/"+ value.file_name +"')}}";
                //    }
                //     html='<li class="dropdown-item">'+
                //                 '<div class="d-flex align-items-center dropdown-item">'+
                //                     '<div class="repair_img">'+
                //                          //'<img src="{{asset('public/assets/img/avatar-place.png')}}">'+
                //                         '<img src="'+imgPath +'">'+
                //                     '</div>'+
                //                     '<div class="repair_info">'+
                //                         '<h3>' + value.name + '</h3>'+
                //                         '<p>'+ value.distance.toFixed(2) + ' Km</p>'+
                //                     '</div>'+
                //                 '</div>'+
                //             '</li>';
                //     $('.repairer-dropdown').append(html);
                });


            }
        });

    });

    // repairer dropdown
    // $(document).ready(function() {
    //     if ($('.arabics').hasClass("active")) {
    //         $('.arabics')
    //             .parents(".dropdown")
    //             .find(".btn")
    //             .html("<span class='flag-icon flag-icon-sa me-1'></span>" + $('.arabics').text());
    //     }

    // });
    // if ($(".dropdown").length) {

    //     $(document).on("click", ".dropdown-menu .dropdown-item", function(e) {
    //         e.preventDefault();
    //         if (!$(this).hasClass("active")) {
    //             $('.english').hide();
    //             $('.arabic').show();
    //             $(".dropdown-menu .dropdown-item").removeClass("active");
    //             $(this).addClass("active");
    //             $(this)
    //                 .parents(".dropdown")
    //                 .find(".btn")
    //                 .html("<span class='flag-icon flag-icon-sa me-1'></span>" + $(this).text());
    //         }
    //         if ($('.eng').hasClass("active")) {
    //             $(".dropdown-menu .dropdown-item").removeClass("active");
    //             $(this).addClass("active");
    //             $('.english').show();
    //             $('.arabic').hide();
    //             $('.arabics')
    //                 .parents(".dropdown")
    //                 .find(".btn")
    //                 .html("<span class='flag-icon flag-icon-us me-1'></span>" + $('.eng').text());
    //         }

    //     });
    // }
</script>
@endsection
