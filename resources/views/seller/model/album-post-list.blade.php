@extends('seller.layouts.app')

@section('panel_content')
<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3 text-primary">{{ translate('Images') }}</h1>
        </div>
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
                <div class="product_box_btn_group">
                    <a title="View Details" href="{{route('seller.view_model_details',Crypt::encrypt($imagePath->id))}}" class="btn btn-dark"><i class="fa-solid fa-eye"></i></a>
                    <a title="Hire Modal" href="{{ route('seller.model_conversations_create',['model_id' => encrypt(@$imagePath->id) ])}}" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i></a>
                    <a title="Modal Id" href="#" class="btn btn-success">#{{@$imagePath->user_id}}</a>
                    <a title="Albums" href="#" class="btn btn-warning"><i class="fa-solid fa-image"></i></a>
                </div>
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
                        <form method="POST" action="{{route('seller.add.comment')}}">
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

@endsection
@section('script')
<script>
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
            url: "{{route('seller.like.model')}}",
            data: {"model_id":id,"is_like":isLike,"upload_id":upload_id,"like_type":type},
            dataType: 'json',
            success: function (data) {
            },
            error: function (data) {
            }
        });
    }
</script>
@endsection
