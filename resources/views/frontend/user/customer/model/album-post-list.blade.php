@extends('frontend.layouts.user_panel')

@section('panel_content')
<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3 text-primary">{{ translate('Images') }}</h1>
        </div>
        @if(auth()->user()->user_type=='model')
            <div class="col-md-6 text-right post_btn">
                <button  data-toggle="modal"  onclick="openAccessCodeModel({{$album_id}})" class="btn btn-primary">{{ translate('Add Your Post') }}</button>
            </div>
        @endif
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{ translate('Basic Info') }}</h5>
    </div>
    <div class="card-body public_album_post_upload" style="display:none;">
        <form action="{{ route('model_upload_image') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="upload_type" value="public_upload">
            <input type="hidden" name="album_id" value="{{$album_id}}">
            <input type="hidden" name="model_id" value="{{$modelId}}">
            <input type="hidden" name="approval" value="2">
            <div class="form-group row">
                <label class="col-md-2 col-form-label">{{ translate('Photo') }}</label>
                <div class="col-md-10">
                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}
                            </div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" name="photo" class="selected-files">
                    </div>
                    <div class="file-preview box sm">
                    </div>
                </div>
            </div>
            <div class="form-group mb-0 text-right mb-3">
                <button type="submit" class="btn btn-primary">{{ translate('Upload Image') }}</button>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label">{{ translate('Video') }}</label>
                <div class="col-md-10">
                    <div class="input-group" data-toggle="aizuploader" data-type="video">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}
                            </div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" name="video" class="selected-files">
                    </div>
                    <div class="file-preview box sm">
                    </div>
                </div>
            </div>
            <div class="form-group mb-0 text-right mb-3">
                <button type="submit" class="btn btn-primary">{{ translate('Upload Video') }}</button>
            </div>
        </form>
    </div>
</div>
<div class="row">
    @php
    $i=1;
    @endphp
    @if (count($imagesPath) > 0)
    @foreach ($imagesPath as $imagePath)
    @php
    $likeClass=getModelLikePostWise(@$imagePath->id,auth()->user()->id,'albumPost');
    @endphp
    <div class="col-sm-6 col-md-4  col-xxl-3">
        <div class="card product_box">
            <div class="modal_box">
                <div class="bg-image modal_img" data-mdb-ripple-color="light">
                    @if(@$imagePath->type=='video')
                    <video class="w-100" controls>
                        <source src="{{ url('/public') . '/' . $imagePath->file_name }}" type="video/mp4">
                    </video>
                    @else
                    <img src="{{ url('/public').'/'.$imagePath->file_name }}" class="img-fluid w-100" />
                    @endif
                    <a href="#!">
                        <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                    </a>
                </div>
                <!-- <a href="{{route('seller.view_model_details',Crypt::encrypt($imagePath->id))}}" class="btn-success add_btn">View Detail</a> -->
            </div>
            <div class="card-body">
                <!-- <h5 class="card-title">{{@$imagePath->name}}</h5> -->
                @if(auth()->user()->user_type=='customer')
                    <div class="product_box_btn_group">
                        <a title="View Details" href="{{route('user.view_model_details',Crypt::encrypt($imagePath->id))}}" class="btn btn-dark"><i class="fa-solid fa-eye"></i></a>
                        <a title="Hire Modal" href="{{ route('user.model_conversations_create',['model_id' => encrypt(@$imagePath->user_id) ])}}" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i></a>
                        <a title="Modal Id" href="#" class="btn btn-success">#{{@$imagePath->user_id}}</a>
                        <a title="Albums" href="#" class="btn btn-warning"><i class="fa-solid fa-image"></i></a>
                    </div>
                @endif
            </div>
            <div class="product_short_icon">
                <a class="like{{$i}} {{$likeClass}}" onclick="giveLikes({{$i}},{{@$imagePath->user_id}},{{@$imagePath->id}},{{"'albumPost'"}})">
                    <i class="fa-solid fa-thumbs-up"></i>
                </a>
                <button class="btn" data-toggle="modal" data-target="#myModal{{$i}}">
                    <i class="las la-comment"></i>
                </button>
            </div>
            <div class="modal" id="myModal{{$i}}">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Comment</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <form method="POST" action="{{route('user.add.comment')}}">
                            @csrf
                            <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                            <input type="hidden" name="model_id" value="{{isset($imagePath->user_id) ? $imagePath->user_id :''}}">
                            <input type="hidden" name="album_id" value="{{isset($album_id) ? $album_id :''}}">
                            <input type="hidden" name="upload_id" value="{{isset($imagePath->id) ? $imagePath->id : 0}}">
                            <div class="modal-body">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <textarea class="form-control" name="message" placeholder="{{translate('message')}}" required></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @php
    $i++;
    @endphp
    @endforeach
    @else
    <div class="w-100 text-center ">
        <h5>
            No Image
        </h5>
    </div>
    @endif
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
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>
    function openAccessCodeModel(albumId){
        $('#albumId').val(albumId);
        $('#openAccessCodeModel').modal('show');
    }
    function giveLikes(randomNumber,id,upload_id,type=null){

        if ($('.like'+randomNumber).hasClass("active")) {
            $('.like'+randomNumber).removeClass("active");
            var isLike=0;
        }
        else {
            $('.like'+randomNumber).addClass("active");
            var isLike=1;
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: "{{route('like.model')}}",
            data: {"model_id":id,"is_like":isLike,"upload_id":upload_id,"like_type":type},
            dataType: 'json',
            success: function (data) {
            },
            error: function (data) {
            }
        });
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
            url: "{{route('user.verify-accesscode')}}",
            data: {"access_code":access_code,"albumId":albumId},
            dataType: 'json',
            success: function (data) {
                if(data.success==1){
                    $('.public_album_post_upload').show();
                    $('.post_btn').hide();
                    $('#openAccessCodeModel').modal('hide');
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
