@extends('frontend.layouts.user_panel')

@section('panel_content')
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
                                    <a href="{{route('user.album_post_list',Crypt::encrypt($value->id))}}" class="btn btn-soft-info btn-icon btn-circle btn-sm" title="{{translate('View Post')}}"><i class="las la-eye"></i></a>
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
@endsection
@section('script')
<script type="text/javascript">
</script>
@endsection
