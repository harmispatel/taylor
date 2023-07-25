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
                                <a href="{{route('seller.album_post_list',Crypt::encrypt($value->id))}}" class="btn btn-soft-info btn-icon btn-circle btn-sm" title="{{translate('View Post')}}"><i class="las la-eye"></i></a>
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
                                    <select name="is_public" class="form-control" id="is_public">
                                        <option value="0">Private</option>
                                        <option value="1">Public</option>
                                    </select>
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
