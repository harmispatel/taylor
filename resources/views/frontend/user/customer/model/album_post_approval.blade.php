@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Albums List') }}</h5>
        </div>
        @if(count($approvalList) > 0)
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('No')}}</th>
                            <th data-breakpoints="md">{{ translate('Requestor Name')}}</th>
                            <th data-breakpoints="md">{{ translate('User Type')}}</th>
                            <th data-breakpoints="md">{{ translate('Post')}}</th>
                            <th data-breakpoints="md">{{ translate('Status')}}</th>
                            <th class="text-right">{{ translate('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1; @endphp
                        @foreach ($approvalList as $key => $value)
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{@$value->user->name}}</td>
                                <td>{{@$value->user->user_type}}</td>
                                <td>
                                    @if(@$value->type=='video')
                                        <video class="w-100" controls>
                                            <source src="{{ url('/public') . '/' . $value->file_name }}" type="video/mp4">
                                        </video>
                                    @else
                                        <img src="{{ url('/public') . '/' . $value->file_name }}" width="50px"class="shadow-1-strong rounded mb-4"
                                        alt="Boat on Calm Water" />
                                    @endif
                                </td>
                                <td>
                                    @if(@$value->model_images->approval==2)
                                        <span class="badge bg-warning text-dark w-100">{{ translate('Pending')}}</span>
                                    @elseif(@$value->model_images->approval==3)
                                        <span class="badge badge-success w-100">{{ translate('Approved')}}</span>
                                    @elseif(@$value->model_images->approval==4)
                                        <span class="badge badge-danger w-100">{{ translate('Rejected')}}</span>
                                    @endif
                                </td>
                                <td class="text-right">

                                    <a href="{{route('user.view_post_detail',Crypt::encrypt($value->id))}}" class="btn btn-soft-info btn-icon btn-circle btn-sm" title="{{translate('View Post')}}"><i class="las la-eye"></i></a>
                                    @if(@$value->model_images->approval==2)
                                        <a href="{{route('approve_post',['post_id' => Crypt::encrypt(@$value->model_images->id),'approve'=>3])}}"  class="btn btn-success btn-icon btn-circle btn-sm" title="{{translate('Approve')}}"><i class="fa-solid fa-check"></i></a>
                                        <a href="{{route('approve_post',['post_id' => Crypt::encrypt(@$value->model_images->id),'approve'=>4])}}"  class="btn btn-danger btn-icon btn-circle btn-sm" title="{{translate('Reject')}}"><i class="fa-solid fa-times"></i></a>
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
        {!! $approvalList->links() !!}
    </div>
@endsection
@section('script')
<script type="text/javascript">
</script>
@endsection
