@extends('seller.layouts.app')

@section('panel_content')
<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3 text-primary">{{ translate('Models') }}</h1>
        </div>
    </div>
</div>

<div class="row">
    @php
        $i=1;
    @endphp
    @foreach ($models as $model)
        @php
            $likeClass=getModelLikeSellervise($model->id,auth()->user()->id,'modelLike');
        @endphp
    <div class="col-sm-6 col-md-4  col-xxl-3">
        <div class="card product_box">
            <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light">
                <div class="product_img">
                    <img src="{{ @$model->avatarImage->file_name ? url('/public').'/'.@$model->avatarImage->file_name : url('/public').'/assets/img/avatar-place.png' }}" class="img-fluid w-100" />
                </div>
                <a href="#!">
                    <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                </a>
            </div>
            <div class="card-body">
                <h5 class="card-title">{{@$model->name}}</h5>
                <div class="product_box_btn_group">
                    <a title="See Images" href="{{ route('seller.single_model_gallery',@$model->id)}}" class="btn btn-dark"><i class="fa-solid fa-eye"></i></a>
                    <a title="Hire Modal" href="{{ route('seller.model_conversations_create',['model_id' => encrypt(@$model->id) ])}}" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i></a>
                    <a title="Modal Id" href="#" class="btn btn-success">#{{@$model->id}}</a>
                    <a title="Albums" href="{{route('seller.album_list',encrypt(@$model->id))}}" class="btn btn-warning"><i class="fa-solid fa-image"></i></a>
                </div>
            </div>
            <div class="product_short_icon">
                <a  class="like{{$i}} {{$likeClass}}"  onclick="giveLike({{$i}},{{@$model->id}},{{isset($model->avatarImage->id) ? $model->avatarImage->id : 0 }},{{"'modelLike'"}})">
                    <i class="fa-solid fa-thumbs-up"></i>
                </a>
                <button class="btn" data-toggle="modal" data-target="#myModal">
                    <i class="las la-comment"></i>
                </button>
            </div>
            <div class="modal" id="myModal">
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
                            <input type="hidden" name="model_id" value="{{isset($model->id) ? $model->id :''}}">
                            <input type="hidden" name="upload_id" value="{{isset($model->avatarImage->id) ? $model->avatarImage->id :''}}">
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

</div>


@endsection
