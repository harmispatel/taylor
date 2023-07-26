@extends('frontend.layouts.user_panel')



@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Model Album Gallery') }}</h1>
            </div>
        </div>
    </div>
    <!-- Basic Info-->

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Basic Info') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('model_upload_image') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="album_id" value="{{$id}}">
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">{{ translate('Photo') }}</label>
                    <div class="col-md-10">
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}
                                </div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="photo" class="selected-files" required>
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
                            <input type="hidden" name="video" class="selected-files" required>
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
        @foreach ($imagesPath as $imagePath)
            <div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
                <div class="modal_box">
                    <div class="modal_img video_box">
                        @if(@$imagePath->type=='video')
                            <video class="w-100" controls>
                                <source src="{{ url('/public') . '/' . $imagePath->file_name }}" type="video/mp4">
                            </video>
                        @else
                            <img src="{{ url('/public') . '/' . $imagePath->file_name }}" class="w-100 shadow-1-strong rounded mb-4"
                            alt="Boat on Calm Water" />
                        @endif
                    </div>
                    <a href="#" data-href="{{route('delete_model_picture',Crypt::encrypt($imagePath->id))}}" class="btn btn-danger dt_btn  confirm-delete"><i class="fa-solid fa-trash"></i></a>
                    <button  data-toggle="modal" onclick="openDetailsModel({{isset($imagePath->id) ? $imagePath->id : ''}},{{$id}})" class="btn btn-success add_btn">Add</button>
                </div>
            </div>
        @endforeach
    </div>
    <div class="csm_pagination">
        {!! $imagesPath->links() !!}
    </div>
    <!--Model details pop up model-->
    <div class="modal fade" id="detail-modal" >
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Model Details') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('store_model_details') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="upload_id" id="upload_id">
                    <input type="hidden" name="album_id" id="albumId">
                    <div class="modal-body">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Product Category')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <select class="form-control aiz-selectpicker mb-3" data-live-search="true" data-placeholder="{{ translate('Select your country') }}"  id="category_id" name="category_id" onchange="getBodySize(this.value)" required>
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
                            </div>
                            <div class="row cloth"  style="display:none;">
                                <div class="col-md-2">
                                    <label>{{ translate('Size')}}</label>
                                </div>
                                <div class="col-md-10 category_select_box">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Brand')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="brand" class="form-control mb-3" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Purchase Link')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="purchase_link" class="form-control mb-3"required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Target')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <select class="form-control aiz-selectpicker mb-3" data-live-search="true" data-placeholder="{{ translate('Select Target') }}"  name="target_id" required>
                                        <option value="">{{ translate('Select Target') }}</option>
                                        <option value="1">{{ translate('Women’s fashion') }}</option>
                                        <option value="2">{{ translate('Men’s fashion') }}</option>
                                        <option value="3">{{ translate('Women’s beauty') }}</option>
                                        <option value="4">{{ translate('Men’s beauty') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Height')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="height" class="form-control mb-3" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('TaylorMade ID')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="tylormade_id" class="form-control mb-3" required>
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
    <script type="text/javascript">
        $('.new-email-verification').on('click', function() {
            $(this).find('.loading').removeClass('d-none');
            $(this).find('.default').addClass('d-none');
            var email = $("input[name=email]").val();

            $.post('{{ route('user.new.verify') }}', {
                _token: '{{ csrf_token() }}',
                email: email
            }, function(data) {
                data = JSON.parse(data);
                $('.default').removeClass('d-none');
                $('.loading').addClass('d-none');
                if (data.status == 2)
                    AIZ.plugins.notify('warning', data.message);
                else if (data.status == 1)
                    AIZ.plugins.notify('success', data.message);
                else
                    AIZ.plugins.notify('danger', data.message);
            });
        });
        function openDetailsModel(uploadId,albumId){

            $('#upload_id').val(uploadId);
            $('#albumId').val(albumId);
            $('#detail-modal').modal('show');
        }
        function getBodySize(categoryId){

            if(categoryId==1){
                var html='<select class="form-control aiz-selectpicker mb-3" name="model_size" data-live-search="true" data-placeholder="{{ translate('Select your country') }}"  required>'+
                '<option value="1">{{ translate('XXS(Int) /32 (EU) /4 (UK) /0 (US)') }}</option>'
                    +'<option value="2">{{ translate('XS(Int) /34 (EU) /6 (UK) /2 (US)') }}</option>'
                    +'<option value="3">{{ translate('S(Int) /36 (EU) /8 (UK) /4 (US)') }}</option>'
                    +'<option value="4">{{ translate('M(Int) /38 (EU) /10 (UK) /6 (US)') }}</option>'
                    +'<option value="5">{{ translate('L(Int) /40 (EU) /12 (UK) /8 (US)') }}</option>'
                    +'<option value="6">{{ translate('XL(Int) /42 (EU) /14 (UK) /10 (US)') }}</option>'
                    +'<option value="7">{{ translate('XXL(Int) /44 (EU)/16 (UK)/12 (US)') }}</option>'
                    +'<option value="8">{{ translate('3XL(Int) /46 (EU) /18 (UK) /14 (US)') }}</option>'
                    +'<option value="9">{{ translate('4XL(Int) /48 (EU) /20 (UK) /16 (US)') }}</option>'
                    +'<option value="10">{{ translate('5XL(Int) /50 (EU) /22 (UK) /18 (US)') }}</option>'
                    +'<option value="11">{{ translate('6XL(Int) /52 (EU) /24 (UK) /20 (US)') }}</option>'
                    +'<option value="12">{{ translate('7XL(Int) /54 (EU) /26 (UK) /22 (US)') }}</option>'
                    +'</select>';
                    $('.cloth').show();
                    $('.category_select_box').html(html);
            }
            else if(categoryId==2){

                var html='<select class="form-control aiz-selectpicker mb-3" name="model_size" data-live-search="true" data-placeholder="{{ translate('Select your country') }}"  required>'+
                '<option value="1">{{ translate('36 /36 (EU) /3.5 (UK) /6 (US) /4.5 (AU)') }}</option>'
                +'<option value="2">{{ translate('37 /37 (EU) /4 (UK) / 6.5(US) /5 (AU)') }}</option>'
                +'<option value="3">{{ translate('39 /39 (EU) /5.5-6 (UK) / 8-8.5(US) /6.5-7 (AU)') }}</option>'
                +'<option value="4">{{ translate('39 /39 (EU) /5.5-6 (UK) / 8-8.5(US) /6.5-7 (AU)') }}</option>'
                +'<option value="5">{{ translate('40 /40 (EU) /6.5 (UK) / 9(US) /7.5 (AU)') }}</option>'
                +'<option value="6">{{ translate('41 /41 (EU) /7 (UK) / 9.5(US) /8 (AU)') }}</option>'
                +'<option value="7">{{ translate('42 /42 (EU) /7.5 (UK) / 10(US) /8.5 (AU)') }}</option>'
                +'<option value="8">{{ translate('43 /43 (EU) /8(UK) / 10.5(US) /9(AU)') }}</option>'
                +'</select>';
                $('.cloth').show();
                $('.category_select_box').html(html);
            }
            else if(categoryId==7){
                var html='<select class="form-control aiz-selectpicker mb-3" name="model_size" data-live-search="true" data-placeholder="{{ translate('Select your country') }}"  required>'+
                '<option value="1">{{ translate('10 25cm') }}</option>'
                +'<option value="2">{{ translate('12 30cm') }}</option>'
                +'<option value="3">{{ translate('14 35cm') }}</option>'
                +'<option value="4">{{ translate('16 40cm') }}</option>'
                +'<option value="5">{{ translate('18 45cm') }}</option>'
                +'<option value="6">{{ translate('20 50cm') }}</option>'
                +'<option value="7">{{ translate('22 55cm') }}</option>'
                +'<option value="8">{{ translate('24 60cm') }}</option>'
                +'<option value="9">{{ translate('26 65cm') }}</option>'
                +'<option value="10">{{ translate('28 70cm') }}</option>'
                +'</select>';
                $('.cloth').show();
                $('.category_select_box').html(html);
            }
            else{
                $('.cloth').hide();
            }

        }
    </script>

    @if (get_setting('google_map') == 1)
        @include('frontend.partials.google_map')
    @endif
@endsection
