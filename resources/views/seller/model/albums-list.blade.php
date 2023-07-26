@extends('seller.layouts.app')

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
                                <td class="text-right">
                                    @if($value->is_public==1)
                                        <form action="{{ route('seller.album_post_list',Crypt::encrypt($value->id)) }}" id="album-post-list" method="GET" enctype="multipart/form-data">
                                            <a  data-toggle="modal"  onclick="openAccessCodeModel({{$value->id}})" class="btn btn-soft-info btn-icon btn-circle btn-sm" title="{{translate('View Post')}}"><i class="las la-eye"></i></a>
                                        </form>
                                    @else
                                        <a href="{{route('seller.album_post_list',Crypt::encrypt($value->id))}}" class="btn btn-soft-info btn-icon btn-circle btn-sm" title="{{translate('View Post')}}"><i class="las la-eye"></i></a>
                                    @endif
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
    <!--openAccessCodeModel Model -->
    <div class="modal fade" id="openAccessCodeModel" >
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 400px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Access Code') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- <form action="{{ route('seller.verify-accesscode') }}" method="POST" enctype="multipart/form-data"> -->
                    @csrf
                    <input type="hidden" name="albumId" id="albumId">
                    <div class="modal-body">
                        <span id="error_message" ></span>
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <label class="m-0">{{ translate('Code')}}</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" name="access_code" id="access_code" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group text-right m-0">
                            <button type="button" onclick="verifyAccessCode()" class="btn btn-sm btn-primary mt-3">{{translate('Verify')}}</button>
                        </div>
                    </div>
                <!-- </form> -->
            </div>
        </div>
    </div>

@endsection
@section('script')
<script type="text/javascript">
    function openAccessCodeModel(albumId){
        $('#albumId').val(albumId);
        $('#openAccessCodeModel').modal('show');
    }
    function verifyAccessCode(){
        var access_code=$('#access_code').val();
        var albumId=$('#albumId').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: "{{route('seller.verify-accesscode')}}",
            data: {"access_code":access_code,"albumId":albumId},
            dataType: 'json',
            success: function (data) {
                if(data.success==1){
                    $('#album-post-list').submit();
                }
                else{
                    $("#error_message").html('<p class="text-danger text-center">Access Code Invalid !</p>');
                    $("#error_message").show();
                    setTimeout(function() { $("#error_message").hide(); }, 5000);
                }
            },
            error: function (data) {
            }
        });
    }
</script>
@endsection
