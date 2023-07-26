@extends('seller.layouts.app')

@section('panel_content')
<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3 text-primary">{{ translate('Images') }}</h1>
        </div>
    </div>
    <form method="POST"  action="{{ route('seller.all_model_gallery') }}"  name="filter_form" id="filterForm">
        @csrf
        <div class="row">
        <div class="col-md-3">
                @php
                    $users=App\Models\User::where('user_type','model')->pluck('name','id');
                @endphp
                <select name="user_id" onchange="formsubmit()" class="form-control">
                    <option value="">{{ translate('Profile Name') }}</option>
                    <option value="">{{ translate('Select Name') }}</option>
                    @if(count($users) > 0)
                        @foreach($users as $key=> $user)
                        <option value="{{$key}}">{{ $user }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-2">
                <select name="target_id" onchange="formsubmit()" class="form-control">
                    <option value="">Target</option>
                    <option value="">{{ translate('Select Target') }}</option>
                    <option value="1">{{ translate('Women’s fashion') }}</option>
                    <option value="2">{{ translate('Men’s fashion') }}</option>
                    <option value="3">{{ translate('Women’s beauty') }}</option>
                    <option value="4">{{ translate('Men’s beauty') }}</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="target_filter" class="form-control">
                    <option value="">Body Size</option>
                    <option value="">{{ translate('Select Size') }}</option>
                    <option value="1">{{ translate('Women’s fashion') }}</option>
                    <option value="2">{{ translate('Men’s fashion') }}</option>
                    <option value="3">{{ translate('Women’s beauty') }}</option>
                    <option value="4">{{ translate('Men’s beauty') }}</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="product_category" onchange="formsubmit()" class="form-control">
                    <option value="">Product Category</option>
                    <option value="">{{ translate('Select Category') }}</option>
                    <option value="1">{{ translate('Clothing') }}</option>
                    <option value="2">{{ translate('Shoes') }}</option>
                    <option value="3">{{ translate('Jewelry') }}</option>
                    <option value="4">{{ translate('Watches') }}</option>
                    <option value="5">{{ translate('Handbags') }}</option>
                    <option value="6">{{ translate('Accessories') }}</option>
                    <option value="7">{{ translate('Hair') }}</option>
                    <option value="8">{{ translate('Skin') }}</option>
                    <option value="9">{{ translate('Makeup') }}</option>
                    <option value="10">{{ translate('Foot, Hand and Nail') }}</option>
                </select>
            </div>
            <div class="col-md-2">
                @php
                    $brand=App\Models\ModelDetail::pluck('brand');
                @endphp
                <select name="brand" onchange="formsubmit()" class="form-control">
                    <option value="">Brand</option>
                    <option value="">{{ translate('Select Brand') }}</option>
                    @if(count($brand) > 0)
                        @foreach($brand as $value)
                        <option value="{{$value}}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

        </div>
    </form>
</div>

<div class="row">
    @php
    $i=1;
    @endphp
    @if (count($imagesPath) > 0)
    @foreach ($imagesPath as $imagePath)
    @php
    $likeClass=getModelLikePostWise(@$imagePath->id,auth()->user()->id);
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
                <a href="{{route('seller.view_model_details',Crypt::encrypt($imagePath->id))}}" class="btn-success add_btn">View Detail</a>
            </div>
            <div class="card-body">
                <!-- <h5 class="card-title">{{@$imagePath->name}}</h5> -->
                <div class="product_box_btn_group">
                    <a title="View Details" href="{{route('seller.view_model_details',Crypt::encrypt($imagePath->id))}}" class="btn btn-dark"><i class="fa-solid fa-eye"></i></a>
                    <a title="Hire Modal" href="{{ route('seller.model_conversations_create',['model_id' => encrypt(@$imagePath->id) ])}}" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i></a>
                    <a title="Modal Id" href="#" class="btn btn-success">#{{@$imagePath->id}}</a>
                    <a title="Albums" href="{{route('seller.album_list',encrypt(@$id))}}" class="btn btn-warning"><i class="fa-solid fa-image"></i></a>
                </div>
            </div>
            <div class="product_short_icon">
                <a class="like{{$i}} {{$likeClass}}" onclick="giveLike({{$i}},{{@$imagePath->id}})">
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
                            <input type="hidden" name="model_id" value="{{isset($imagePath->id) ? $imagePath->id :''}}">
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
<script type="text/javascript">
    function formsubmit(){
        $('#filterForm').submit();
    }
</script>
@endsection
