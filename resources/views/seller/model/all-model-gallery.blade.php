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
                        <option value="{{$key}}" {{(@$post['user_id']== $key) ? 'selected' : ''}}>{{ $user }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-2">
                <select name="target_id" onchange="formsubmit()" class="form-control">
                    <option value="">Target</option>
                    <option value="">{{ translate('Select Target') }}</option>
                    <option value="1" {{(@$post['target_id']== 1) ? 'selected' : ''}}>{{ translate('Women’s fashion') }}</option>
                    <option value="2" {{(@$post['target_id']== 2) ? 'selected' : ''}}>{{ translate('Men’s fashion') }}</option>
                    <option value="3" {{(@$post['target_id']== 3) ? 'selected' : ''}}>{{ translate('Women’s beauty') }}</option>
                    <option value="4" {{(@$post['target_id']== 4) ? 'selected' : ''}}>{{ translate('Men’s beauty') }}</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="size" onchange="formsubmit()" class="form-control">
                    <option value="">Body Size</option>
                    <option value="">{{ translate('Select Size') }}</option>
                    <option value="{{ translate('XXS') }}" {{(@$post['size']== translate('XXS')) ? 'selected' : ''}} >{{ translate('XXS') }}</option>
                    <option value="{{ translate('XS') }}" {{(@$post['size']== translate('XS')) ? 'selected' : ''}} >{{ translate('XS') }}</option>
                    <option value="{{ translate('S') }}" {{(@$post['size']== translate('S')) ? 'selected' : ''}} >{{ translate('S') }}</option>
                    <option value="{{ translate('M') }}" {{(@$post['size']== translate('M')) ? 'selected' : ''}}>{{ translate('M') }}</option>
                    <option value="{{ translate('L') }}" {{(@$post['size']== translate('L')) ? 'selected' : ''}}>{{ translate('L') }}</option>
                    <option value="{{ translate('XL') }}" {{(@$post['size']== translate('XL')) ? 'selected' : ''}}>{{ translate('XL') }}</option>
                    <option value="{{ translate('XXL') }}" {{(@$post['size']== translate('XLL')) ? 'selected' : ''}}>{{ translate('XXL') }}</option>
                    <option value="{{ translate('3XL') }}" {{(@$post['size']== translate('3XL')) ? 'selected' : ''}}>{{ translate('3XL') }}</option>
                    <option value="{{ translate('4XL') }}"{{(@$post['size']== translate('4XL')) ? 'selected' : ''}} >{{ translate('4XL') }}</option>
                    <option value="{{ translate('5XL') }}" {{(@$post['size']== translate('5XL')) ? 'selected' : ''}} >{{ translate('5XL') }}</option>
                    <option value="{{ translate('6XL') }}" {{(@$post['size']== translate('6XL')) ? 'selected' : ''}} >{{ translate('6XL') }}</option>
                    <option value="{{ translate('7XL') }}" {{(@$post['size']== translate('7XL')) ? 'selected' : ''}} >{{ translate('7XL') }}</option>
                    <option value="{{ translate('36') }}" {{(@$post['size']== translate('36')) ? 'selected' : ''}} >{{ translate('36') }}</option>
                    <option value="{{ translate('37') }}" {{(@$post['size']== translate('37')) ? 'selected' : ''}} >{{ translate('37') }}</option>
                    <option value="{{ translate('38') }}"{{(@$post['size']== translate('38')) ? 'selected' : ''}} >{{ translate('38') }}</option>
                    <option value="{{ translate('39') }}" {{(@$post['size']== translate('39')) ? 'selected' : ''}} >{{ translate('39') }}</option>
                    <option value="{{ translate('40') }}"{{(@$post['size']== translate('40')) ? 'selected' : ''}} >{{ translate('40') }}</option>
                    <option value="{{ translate('41') }}" {{(@$post['size']== translate('41')) ? 'selected' : ''}} >{{ translate('41') }}</option>
                    <option value="{{ translate('42') }}" {{(@$post['size']== translate('42')) ? 'selected' : ''}} >{{ translate('42') }}</option>
                    <option value="{{ translate('43') }}" {{(@$post['size']== translate('43')) ? 'selected' : ''}} >{{ translate('43') }}</option>
                    <option value="{{ translate('25cm') }}" {{(@$post['size']== translate('25cm')) ? 'selected' : ''}} >{{ translate('25cm') }}</option>
                    <option value="{{ translate('30cm') }}"  {{(@$post['size']== translate('30cm')) ? 'selected' : ''}}>{{ translate('30cm') }}</option>
                    <option value="{{ translate('35cm') }}" {{(@$post['size']== translate('35cm')) ? 'selected' : ''}} >{{ translate('35cm') }}</option>
                    <option value="{{ translate('40cm') }}" {{(@$post['size']== translate('40cm')) ? 'selected' : ''}} >{{ translate('40cm') }}</option>
                    <option value="{{ translate('45cm') }}" {{(@$post['size']== translate('45cm')) ? 'selected' : ''}} >{{ translate('45cm') }}</option>
                    <option value="{{ translate('50cm') }}" {{(@$post['size']== translate('50cm')) ? 'selected' : ''}} >{{ translate('50cm') }}</option>
                    <option value="{{ translate('55cm') }}" {{(@$post['size']== translate('55cm')) ? 'selected' : ''}} >{{ translate('55cm') }}</option>
                    <option value="{{ translate('60cm') }}" {{(@$post['size']== translate('60cm')) ? 'selected' : ''}} >{{ translate('60cm') }}</option>
                    <option value="{{ translate('65cm') }}" {{(@$post['size']== translate('65cm')) ? 'selected' : ''}} >{{ translate('65cm') }}</option>
                    <option value="{{ translate('70cm') }}" {{(@$post['size']== translate('70cm')) ? 'selected' : ''}}>{{ translate('70cm') }}</option>

                </select>
            </div>
            <div class="col-md-3">
                <select name="product_category" onchange="formsubmit()" class="form-control">
                    <option value="">Product Category</option>
                    <option value="">{{ translate('Select Category') }}</option>
                    <option value="1" {{(@$post['product_category']== 1) ? 'selected' : ''}} >{{ translate('Clothing') }}</option>
                    <option value="2" {{(@$post['product_category']== 2) ? 'selected' : ''}}>{{ translate('Shoes') }}</option>
                    <option value="3" {{(@$post['product_category']== 3) ? 'selected' : ''}}>{{ translate('Jewelry') }}</option>
                    <option value="4" {{(@$post['product_category']== 4) ? 'selected' : ''}}>{{ translate('Watches') }}</option>
                    <option value="5" {{(@$post['product_category']== 5) ? 'selected' : ''}}>{{ translate('Handbags') }}</option>
                    <option value="6" {{(@$post['product_category']== 6) ? 'selected' : ''}}>{{ translate('Accessories') }}</option>
                    <option value="7" {{(@$post['product_category']== 7) ? 'selected' : ''}}>{{ translate('Hair') }}</option>
                    <option value="8" {{(@$post['product_category']== 8) ? 'selected' : ''}}>{{ translate('Skin') }}</option>
                    <option value="9" {{(@$post['product_category']== 9) ? 'selected' : ''}}>{{ translate('Makeup') }}</option>
                    <option value="10" {{(@$post['product_category']== 10) ? 'selected' : ''}}>{{ translate('Foot, Hand and Nail') }}</option>
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
                        <option value="{{$value}}" {{(@$post['brand']== $value) ? 'selected' : ''}}>{{ $value }}</option>
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
    $likeClass=getModelLikePostWise(@$imagePath->id,auth()->user()->id,'modelPostLike');
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
                <a class="like{{$i}} {{$likeClass}}" onclick="giveLike({{$i}},{{@$imagePath->user_id}},{{@$imagePath->id}},{{"'modelPostLike'"}})">
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
