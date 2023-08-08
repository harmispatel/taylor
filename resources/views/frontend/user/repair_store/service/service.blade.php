@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="text-right mb-3">
        <button data-toggle="modal" onclick="openAlbumsModel(0)" class="btn btn-primary">Add</button>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Service List') }}</h5>
        </div>
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('No')}}</th>
                            <th data-breakpoints="md">{{ translate('Name')}}</th>
                            <th data-breakpoints="md">{{ translate('Price')}}</th>
                            <th class="text-right">{{ translate('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1; @endphp
                        @foreach ($service as $key => $value)
                            <tr class="">
                                <td>{{$i}}</td>
                                <td>{{$value->service_name}}</td>
                                <td>{{$value->service_price}}</td>
                                <td class="text-right">
                                <button onclick="openAlbumsModel({{$value->id}})" class="btn btn-soft-info btn-icon btn-circle btn-sm" title="Edit"><i class="las la-edit"></i></button>
                                <a href="#" data-href="{{route('delete.service',Crypt::encrypt($value->id))}}"  class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" title="Delete"><i class="las la-trash"></i></a>
                                </td>

                            </tr>
                            @php $i++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        {!! $service->links() !!}
    </div>
    <!--Service Model -->
    <div class="modal fade" id="service-modal" >
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Service Detail') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('store.service') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="serviceId">
                    <div class="modal-body">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Name')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="service_name" id="service_name" class="form-control mb-3" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Price')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="number" name="service_price" id="service_price" class="form-control mb-3" required>
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-sm btn-primary mt-4">{{translate('Save')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('modal')
    @include('modals.delete_modal')
@endsection
@section('script')
<script>
    function openAlbumsModel(serviceId){

        if(serviceId != 0){
            $('#serviceId').val(serviceId);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "getServiceDetails",
                data: {"id":serviceId},
                success:function(data) {
                    if(data.success==1){
                        $('.modal-body #service_name').val(data.service.service_name);
                        $('.modal-body #service_price').val(data.service.service_price);
                    }
                },
                error: function(data) {

                }
            });
        }
        $('#service-modal').modal('show');
    }

</script>
@endsection
