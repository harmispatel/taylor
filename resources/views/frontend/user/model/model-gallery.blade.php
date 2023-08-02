@extends('frontend.layouts.user_panel')



@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Model Gallery') }}</h1>
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
                    <button  data-toggle="modal" onclick="openDetailsModel({{isset($imagePath->id) ? $imagePath->id : ''}})" class="btn btn-success add_btn">Add</button>
                </div>
            </div>
        @endforeach
    </div>
    <div class="csm_pagination">
        {!! $imagesPath->links() !!}
    </div>

@endsection
@section('modal')
    @include('modals.delete_modal')
@endsection
@section('script')
    <script type="text/javascript">

    </script>

@endsection
