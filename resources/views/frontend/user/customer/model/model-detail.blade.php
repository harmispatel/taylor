@extends('frontend.layouts.user_panel')

@section('panel_content')

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Model Details List') }}</h5>
        </div>
        @if (count($modelDetails) > 0)
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('No')}}</th>
                            <th data-breakpoints="md">{{ translate('Category')}}</th>
                            <th data-breakpoints="md">{{ translate('Size')}}</th>
                            <th data-breakpoints="md">{{ translate('Purchase Link')}}</th>
                            <th data-breakpoints="md">{{ translate('TylormadeId')}}</th>
                            <th data-breakpoints="md">{{ translate('Target')}}</th>
                            <th>{{ translate('Height')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1; @endphp
                        @foreach ($modelDetails as $key => $value)

                            <tr>
                                <td>{{$i}}</td>
                                <td>
                                    @if($value->category_id == 1)
                                        {{ translate('Clothing') }}
                                    @elseif($value->category_id == 2)
                                        {{ translate('Shoes') }}
                                    @elseif($value->category_id == 3)
                                        {{ translate('Jewelry') }}
                                    @elseif($value->category_id == 4)
                                        {{ translate('Watches') }}
                                    @elseif($value->category_id == 5)
                                        {{ translate('Handbags') }}
                                    @elseif($value->category_id == 6)
                                        {{ translate('Accessories') }}
                                    @elseif($value->category_id == 7)
                                        {{ translate('Hair') }}
                                    @elseif($value->category_id == 8)
                                        {{ translate('Skin') }}
                                    @elseif($value->category_id == 9)
                                        {{ translate('Makeup') }}
                                    @elseif($value->category_id == 10)
                                        {{ translate('Foot, Hand and Nail') }}
                                    @endif
                                </td>
                                <td>{{$value->model_size}}</td>
                                <td>{{$value->purchase_link}}</td>
                                <td>{{$value->tylormade_id}}</td>
                                <td>
                                    @if($value->target_id == 1)
                                            {{ translate('Women’s fashion') }}
                                        @elseif($value->target_id == 2)
                                            {{ translate('Men’s fashion') }}
                                        @elseif($value->target_id == 3)
                                            {{ translate('Women’s beauty') }}
                                        @elseif($value->target_id == 4)
                                            {{ translate('Men’s beauty') }}
                                    @endif
                                </td>
                                <td>{{$value->height}}</td>
                            </tr>
                            @php $i++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
        <center>No Data Available</center>
        @endif
        {!! $modelDetails->links() !!}
    </div>

@endsection

@section('script')

@endsection
