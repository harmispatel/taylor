<form class="" id="package_payment_form" action="{{ route('purchase.store.ticket') }}" method="post" enctype= "multipart/form-data">
@csrf

@php
    $userId=isset(auth()->user()->id) ? auth()->user()->id : '';
    $addressDetails=App\Models\Address::where('user_id',$userId)->where('set_default',1)->first();
@endphp
<div class="modal fade" id="buy-store-ticket-modal"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ translate('User Details') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="p-3">
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{ translate('Name')}}</label>
                        </div>
                        <div class="col-md-10">
                        <input type="name" name="name" placeholder="{{ translate('Name')}}" value="{{isset(auth()->user()->name) ? auth()->user()->name : ''}}" class="form-control mb-3" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{ translate('Email')}}</label>
                        </div>
                        <div class="col-md-10">
                            <input type="email" placeholder="{{ translate('Email')}}" name="email" value="{{isset(auth()->user()->email) ? auth()->user()->email : '' }}" class="form-control mb-3" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{ translate('Address')}}</label>
                        </div>
                        <div class="col-md-10">
                            <textarea class="form-control mb-3"  rows="2" name="address" required>{{isset($addressDetails->address) ? $addressDetails->address : '' }}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{ translate('Address1')}}</label>
                        </div>
                        <div class="col-md-10">
                            <textarea class="form-control mb-3" placeholder="{{ translate('Your Address')}}" rows="2" name="address1" ></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{ translate('Address2')}}</label>
                        </div>
                        <div class="col-md-10">
                            <textarea class="form-control mb-3" placeholder="{{ translate('Your Address')}}" rows="2" name="address2"></textarea>
                        </div>
                    </div>

                    @if (get_setting('google_map') == 0)
                        <div class="row">
                            <ul id="geoData">
                                <li style="display: none;">Full Address: <span id="location"></span></li>
                                <li style="display: none;">Postal Code: <span id="postal_code"></span></li>
                                <li style="display: none;">Country: <span id="country"></span></li>
                                <li style="display: none;">Latitude: <span id="lat"></span></li>
                                <li style="display: none;">Longitude: <span id="lon"></span></li>
                            </ul>
                        </div>
                        <div class="row">
                            <div class="col-md-10" id="">
                                <input type="hidden" class="form-control mb-3" id="longitude" name="longitude" readonly="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10" id="">
                                <input type="hidden" class="form-control mb-3" id="latitude" name="latitude" readonly="">
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{ translate('Phone')}}</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" class="form-control mb-3" placeholder="{{ translate('+880')}}" name="phone" value="" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{ translate('Document')}}</label>
                        </div>
                        <div class="col-md-10">
                            <input type="file" class="form-control mb-3"  name="document"  required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <button type="button" class="btn btn-sm btn-primary mt-4 GetLocation" >Pin To Current Location</button>

                        </div>

                    <div id="map">
                    </div>
                    <div class="form-group text-right">
                        <a  onclick="selectPaymentMethod()" class="btn btn-sm btn-primary mt-4">{{translate('Save And Pay')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Online payment Modal -->
<div class="modal fade" id="payment_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Make Payment') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>

                <input type="hidden" name="amount" id="amount">
                <div class="modal-body gry-bg px-3 pt-3">
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{ translate('Payment Method')}}</label>
                        </div>
                        <div class="col-md-10">
                            <div class="mb-3">
                                <select class="form-control selectpicker" data-live-search="true" name="payment_option">
                                    @if(get_setting('paypal_payment') == 1)
                                        <option value="paypal">{{ translate('Paypal')}}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button type="button" class="btn btn-sm btn-secondary transition-3d-hover mr-1" data-dismiss="modal">{{translate('cancel')}}</button>
                        <button type="submit" class="btn btn-sm btn-primary transition-3d-hover mr-1">{{translate('Confirm')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
 <!-- Modal -->
 <div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">{{ translate('Login')}}</h6>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-3">
                        <form class="form-default" role="form" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                @if (addon_is_activated('otp_system'))
                                    <input type="text" class="form-control h-auto form-control-lg {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{ translate('Email Or Phone')}}" name="email" id="email">
                                @else
                                    <input type="email" class="form-control h-auto form-control-lg {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{  translate('Email') }}" name="email">
                                @endif
                                @if (addon_is_activated('otp_system'))
                                    <span class="opacity-60">{{  translate('Use country code before number') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <input type="password" name="password" class="form-control h-auto form-control-lg" placeholder="{{ translate('Password')}}">
                            </div>

                            <div class="row mb-2">
                                <div class="col-6">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <span class=opacity-60>{{  translate('Remember Me') }}</span>
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                                <div class="col-6 text-right">
                                    <a href="{{ route('password.request') }}" class="text-reset opacity-60 fs-14">{{ translate('Forgot password?')}}</a>
                                </div>
                            </div>

                            <div class="mb-5">
                                <button type="submit" class="btn btn-primary btn-block fw-600">{{  translate('Login') }}</button>
                            </div>
                        </form>

                        <div class="text-center mb-3">
                            <p class="text-muted mb-0">{{ translate('Dont have an account?')}}</p>
                            <a href="{{ route('user.registration') }}">{{ translate('Register Now')}}</a>
                        </div>
                        @if(get_setting('google_login') == 1 ||
                            get_setting('facebook_login') == 1 ||
                            get_setting('twitter_login') == 1)
                            <div class="separator mb-3">
                                <span class="bg-white px-3 opacity-60">{{ translate('Or Login With')}}</span>
                            </div>
                            <ul class="list-inline social colored text-center mb-5">
                                @if (get_setting('facebook_login') == 1)
                                    <li class="list-inline-item">
                                        <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="facebook">
                                            <i class="lab la-facebook-f"></i>
                                        </a>
                                    </li>
                                @endif
                                @if(get_setting('google_login') == 1)
                                    <li class="list-inline-item">
                                        <a href="{{ route('social.login', ['provider' => 'google']) }}" class="google">
                                            <i class="lab la-google"></i>
                                        </a>
                                    </li>
                                @endif
                                @if (get_setting('twitter_login') == 1)
                                    <li class="list-inline-item">
                                        <a href="{{ route('social.login', ['provider' => 'twitter']) }}" class="twitter">
                                            <i class="lab la-twitter"></i>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@section('script')
    <script type="text/javascript">
        function openUserDetailsForm(){
            if('{{ \Auth::check() }}' !== '1'){
                return $('#login_modal').modal();
            }
            $('#buy-store-ticket-modal').modal('show');
        }
        function selectPaymentMethod(){
            $('#payment_model').modal('show');

        }

    </script>
    @if(get_setting('google_map') == 0)
        @include('frontend.partials.google_map')
    @endif
@endsection
