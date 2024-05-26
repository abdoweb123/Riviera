@extends('Admin.Layouts.layout')

@section('content')
<div class="title-wrapper pt-30">
    <div class="row align-items-center">
        <div class="col-md-6">
            <div class="title mb-30">
                <h2>{{ __('trans.My Profile') }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card-styles">
    <div class="card-content">
        <form action="{{ route('admin.profile.update') }}" method="POST" accept-charset="UTF-8" id="signUP">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-6">
                    <div class="input-style-1">
                        <label for="name">{{ __('trans.user_name') }}</label>
                        <input type="text" name="name" id="name" placeholder="{{ __('trans.user_name') }}" value="{{ old('name', auth()->user()->name) }}" required>
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
           
                <div class="col-6">
                    <div class="input-style-1">
                        <label for="phone">{{ __('trans.phone') }}</label>
                        <input type="hidden" name="country_code" id="country_code" value="{{ old('country_code',country_code()) }}">
                        <input type="hidden" name="phone_code" id="phone_code" value="{{ old('phone_code',phone_code()) }}">
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required>
                        @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="input-style-1">
                        <label for="email">{{ __('trans.email') }}</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
            
                <div class="col-6">
                    <div class="input-style-1">
                        <label for="password">{{ __('trans.password')}}</label>
                        <input type="password" name="password" id="password" placeholder="{{  __('trans.password')}}">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="input-style-1">
                        <label for="password_confirmation">{{ __('trans.confirmPassword')}}</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="{{  __('trans.confirmPassword')}}">
                    </div>
                </div>
             


                <div class="col-12">
                    <div class="button-group d-flex justify-content-center flex-wrap">
                        <button type="submit" class="main-btn main-btn btn-hover w-100 text-center" id="submitform">
                            {{ __('trans.Submit') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

@include('intlTelInput',['country_code'=>auth()->user()->country_code])
@endsection