@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="text-right mb-3">
        <button data-toggle="modal" onclick="openAlbumsModel(0)" class="btn btn-primary">Add</button>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Albums List') }}</h5>
        </div>
        @if(count($albums) > 0)
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('No')}}</th>
                            <th data-breakpoints="md">{{ translate('Name')}}</th>
                            <th data-breakpoints="md">{{ translate('Type')}}</th>
                            <th data-breakpoints="md">{{ translate('Access Code')}}</th>
                            <th class="text-right">{{ translate('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1; @endphp
                        @foreach ($albums as $key => $value)
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$value->name}}</td>
                                <td>{{($value->is_public==1) ? translate('Public') : translate('Private') }}</td>
                                <td>{{$value->access_code}}</td>
                                <td class="text-right">
                                <a href="{{route('view-albums',Crypt::encrypt($value->id))}}" class="btn btn-soft-info btn-icon btn-circle btn-sm" title="{{translate('View Details')}}"><i class="las la-eye"></i></a>
                                <button onclick="openAlbumsModel({{$value->id}})" class="btn btn-soft-info btn-icon btn-circle btn-sm" title="Edit"><i class="las la-edit"></i></button>
                                <a href="#" data-href="{{route('delete-albums',Crypt::encrypt($value->id))}}"  class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" title="Delete"><i class="las la-trash"></i></a>
                                </td>

                            </tr>
                            @php $i++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
        <center>No Data Available</center>
        @endif
        {!! $albums->links() !!}
    </div>
    <!--Album Model -->
    <div class="modal fade" id="albums-modal" >
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Albums Details') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('store-albums') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="albumId" id="albumId">
                    <div class="modal-body">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Name')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="name" id="name" class="form-control mb-3" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Type')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <select name="is_public" class="form-control mb-3" onchange="displayAccessCode(this.value)" id="is_public">
                                        <option value="">{{ translate('--Select Type--')}}</option>
                                        <option value="0">{{ translate('Private')}}</option>
                                        <option value="1">{{ translate('Public')}}</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Hidden Field -->
                            <div class="row access_code" style="display:none;">
                                <div class="col-md-2">
                                    <label>{{ translate('Access Code')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="access_code" id="access_code" class="form-control mb-3">
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
    function openAlbumsModel(albumId){

        if(albumId != 0){
            $('#albumId').val(albumId);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "getAlbumDetails",
                data: {"albumId":albumId},
                success:function(data) {
                    if(data.success==1){
                        if(data.albums.is_public==1){
                            $('.modal-body #access_code').val(data.albums.access_code);
                            $('.access_code').show();
                            $('#access_code').prop('required',true);
                        }
                        $('#is_public option').attr('selected', false);
                        $('#is_public option[value="' + data.albums.is_public + '"]').attr("selected", "selected");
                        $('.modal-body #name').val(data.albums.name);
                    }
                },
                error: function(data) {

                }
            });
        }
        $('#albums-modal').modal('show');
    }
    function storeAlbumsDetails(){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: "modelLike",
            data: {"model_id":id,"is_like":isLike},
            dataType: 'json',
            success: function (data) {
            },
            error: function (data) {
            }
        });
    }
    function displayAccessCode(value){
        if(value==1){
            $('.access_code').show();
            $('#access_code').prop('required',true);
        }else{
            $('.access_code').hide();
            $('#access_code').prop('required',false);
        }
    }
</script>
@endsection
