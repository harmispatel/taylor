@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Ticket List') }}</h5>
        </div>
        <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('No')}}</th>
                            <th>{{ translate('Name')}}</th>
                            <th data-breakpoints="lg">{{ translate('email')}}</th>
                            <th data-breakpoints="lg">{{ translate('Address')}}</th>
                            <th data-breakpoints="md">{{ translate('Ticket Id')}}</th>
                            <th data-breakpoints="md">{{ translate('Validity')}}</th>
                            <th data-breakpoints="md">{{ translate('Status')}}</th>
                            <th data-breakpoints="md">{{ translate('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ticketList as $key => $value)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$value->name}}</td>
                                <td>{{$value->email}}</td>
                                <td>{{$value->address}}</td>
                                <td>{{$value->ticket_number}}</td>
                                <td>{{date('d-m-Y',strtotime($value->ticket_validity)) }}</td>
                                <td>
                                    @if(date('Y-m-d') < $value->ticket_validity)
                                        <span class="badge badge-inline badge-success">{{ translate('Active')}}</span></td>
                                    @else
                                        <span class="badge badge-inline badge-danger">{{ translate('Expired')}}</span></td>
                                    @endif
                                <td>
                                    <a href="{{route('view.ticket',Crypt::encrypt($value->id))}}" class="btn btn-soft-info btn-icon btn-circle btn-sm" title="{{translate('View Details')}}"><i class="las la-eye"></i></a>
                                    <button onclick="openTicketModel({{$value->id}})" class="btn btn-soft-info btn-icon btn-circle btn-sm" title="Edit"><i class="las la-edit"></i></button>
                                  <a class="btn btn-soft-info btn-icon btn-circle btn-sm" href="{{route('download.ticket',encrypt($value->id))}}" title="{{ translate('Download') }}">
                                      <i class="las la-download"></i>
                                  </a>
                                  <button data-toggle="modal" data-target="#find-nearby-delivery-store" data-ticket-id="{{$value->id}}"  class="btn btn-soft-info btn-icon btn-circle btn-sm f-d-btn-nearby" title="Use Ticket"><i class="las la-ticket-alt aiz-side-nav-icon"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            {{ $ticketList->links() }}
        </div>
    </div>
    <!-- update ticket data model start -->
    <div class="modal fade" id="update-ticket-modal"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('User Details') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{route('updateTicketDetails')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="ticketId" id="ticketId">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Name')}}</label>
                                </div>
                                <div class="col-md-10">
                                <input type="name" name="name" placeholder="{{ translate('Name')}}" id="name" class="form-control mb-3" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Email')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="email" placeholder="{{ translate('Email')}}" name="email" id="email" class="form-control mb-3" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Address')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <textarea class="form-control mb-3"  rows="2" name="address"  id="address" required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Address1')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <textarea class="form-control mb-3" placeholder="{{ translate('Your Address')}}" rows="2" name="address1"  id="address1"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Address2')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <textarea class="form-control mb-3" placeholder="{{ translate('Your Address')}}" rows="2" name="address2" id="address2"></textarea>
                                </div>
                            </div>

                            @if (get_setting('google_map') == 0)
                                <div class="row">
                                    <ul id="geoData">
                                        <li style="display: none;">Full Address: <span id="location"></span></li>
                                        <li style="display: none;">Postal Code: <span id="postal_code"></span></li>
                                        <li style="display: none;">Country: <span id="country"></span></li>
                                        <li style="display: none;">Latitude: <span id="lat"></span></li>
                                        <li style="display: none;">Longitude: <span id="lon"></span></li>
                                    </ul>
                                </div>
                                <div class="row">
                                    <div class="col-md-10" id="">
                                        <input type="hidden" class="form-control mb-3" id="longitude" name="longitude" readonly="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10" id="">
                                        <input type="hidden" class="form-control mb-3" id="latitude" name="latitude" readonly="">
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Phone')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control mb-3" placeholder="{{ translate('+880')}}" name="phone" id="phone" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Document')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="file" class="form-control mb-3"  name="document">
                                    <span id="document"></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button type="button" class="btn btn-sm btn-primary mt-4 GetLocation" >Pin To Current Location</button>

                                </div>

                            <div id="map">
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-sm btn-primary mt-4">{{translate('Save')}}</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- update ticket data model end -->
    <!-- near by delivery stor model start -->
    <div class="modal fade" id="find-nearby-delivery-store" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">{{ translate('Find Nearby Delivery Store')}}</h6>
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
                                    <label>{{ translate('Nearby Delivery Store')}}</label>
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
                                                <ul class="delivery-store-dropdown">

                                                </ul>
                                            </div>
                                        </div> -->
                                        <select class="form-control"  name="repairer_id" id="delivery-store-dropdown" required>
                                        </select>

                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="order_id" id="order_id">
                            <input type="hidden" name="ticket_id" id="ticket_id">
                            <div class="mb-5">
                                <button type="submit" class="btn btn-primary btn-block fw-600">Repairer account details</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- near by delivery stor model end -->
@endsection

@section('script')
<script>
    function openTicketModel(ticketId){
        if(ticketId != 0){
            $('#ticketId').val(ticketId);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{route('getTicketDetails')}}",
                data: {"ticketId":ticketId},
                success:function(data) {
                    if(data.success==1){

                        $('.modal-body #name').val(data.ticketdetails.name);
                        $('.modal-body #email').val(data.ticketdetails.email);
                        $('.modal-body #phone').val(data.ticketdetails.phone);
                        $('.modal-body textarea#address').text(data.ticketdetails.address);
                        $('.modal-body #address1').val(data.ticketdetails.address1);
                        $('.modal-body #address2').val(data.ticketdetails.address2);
                        $('.modal-body #latitude').val(data.ticketdetails.latitude);
                        $('.modal-body #longitude').val(data.ticketdetails.longitude);
                        $('.modal-body #document').text(data.ticketdetails.document);
                    }
                },
                error: function(data) {

                }
            });
        }
        $('#update-ticket-modal').modal('show');
    }
    $(document).on('click', '.f-d-btn-nearby', function() {
        var ticketID = $(this).data('ticket-id');
        $('#ticket_id').val(ticketID);
        var url = "{{ route('requests.nearby_delivery_store', ": id ") }}";
        url = url.replace(':id', ticketID);
        $.ajax({
            type: 'GET',
            url: url,
            data: {
                ticketID: ticketID,
            },
            success: function(data) {
                $('#repairer-dropdown').html('');
                var html=''
                $.each(data.deliveryStore, function(key, value) {
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

</script>
@if(get_setting('google_map') == 0)
    @include('frontend.partials.google_map')
@endif
@endsection
