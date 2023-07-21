@extends('seller.layouts.app')

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
                            <th class="text-right">{{ translate('Height')}}</th>
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
                                <td>
                                    @if($value->category_id == 1)
                                        @if($value->model_size == 1)
                                            {{ translate('XXS(Int) /32 (EU) /4 (UK) /0 (US)') }}
                                        @elseif($value->model_size == 2)
                                            {{ translate('XS(Int) /34 (EU) /6 (UK) /2 (US)') }}
                                        @elseif($value->model_size == 3)
                                            {{ translate('S(Int) /36 (EU) /8 (UK) /4 (US)') }}
                                        @elseif($value->model_size == 4)
                                            {{ translate('M(Int) /38 (EU) /10 (UK) /6 (US)') }}
                                        @elseif($value->model_size == 5)
                                            {{ translate('L(Int) /40 (EU) /12 (UK) /8 (US)') }}
                                        @elseif($value->model_size == 6)
                                            {{ translate('XL(Int) /42 (EU) /14 (UK) /10 (US)') }}
                                        @elseif($value->model_size == 7)
                                            {{ translate('XXL(Int) /44 (EU)/16 (UK)/12 (US)') }}
                                        @elseif($value->model_size == 8)
                                            {{ translate('3XL(Int) /46 (EU) /18 (UK) /14 (US)') }}
                                        @elseif($value->model_size == 9)
                                            {{ translate('4XL(Int) /48 (EU) /20 (UK) /16 (US)') }}
                                    @elseif($value->category_id == 2)
                                        @if($value->model_size == 1)
                                            {{ translate('36 /36 (EU) /3.5 (UK) /6 (US) /4.5 (AU)') }}
                                        @elseif($value->model_size == 2)
                                            {{ translate('37 /37 (EU) /4 (UK) / 6.5(US) /5 (AU)') }}
                                        @elseif($value->model_size == 3)
                                            {{ translate('39 /39 (EU) /5.5-6 (UK) / 8-8.5(US) /6.5-7 (AU)') }}
                                        @elseif($value->model_size == 4)
                                            {{ translate('39 /39 (EU) /5.5-6 (UK) / 8-8.5(US) /6.5-7 (AU)') }}
                                        @elseif($value->model_size == 5)
                                            {{ translate('40 /40 (EU) /6.5 (UK) / 9(US) /7.5 (AU)') }}
                                        @elseif($value->model_size == 6)
                                            {{ translate('41 /41 (EU) /7 (UK) / 9.5(US) /8 (AU)') }}
                                        @elseif($value->model_size == 7)
                                            {{ translate('42 /42 (EU) /7.5 (UK) / 10(US) /8.5 (AU)') }}
                                        @elseif($value->model_size == 8)
                                        {{ translate('43 /43 (EU) /8(UK) / 10.5(US) /9(AU)') }}
                                    @elseif($value->category_id == 7)
                                        @if($value->model_size == 1)
                                            {{ translate('10 25cm') }}
                                        @elseif($value->model_size == 2)
                                            {{ translate('12 30cm') }}
                                        @elseif($value->model_size == 3)
                                            {{ translate('14 35cm') }}
                                        @elseif($value->model_size == 4)
                                            {{ translate('16 40cm') }}
                                        @elseif($value->model_size == 5)
                                            {{ translate('18 45cm') }}
                                        @elseif($value->model_size == 6)
                                            {{ translate('20 50cm') }}
                                        @elseif($value->model_size == 7)
                                            {{ translate('22 55cm') }}
                                        @elseif($value->model_size == 8)
                                            {{ translate('24 60cm') }}
                                        @elseif($value->model_size == 9)
                                            {{ translate('26 65cm') }}
                                        @elseif($value->model_size == 10)
                                            {{ translate('28 70cm') }}


                                    @endif
                                </td>
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
