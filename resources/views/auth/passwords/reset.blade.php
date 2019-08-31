@extends('_layouts.app')

@section('title', __('Reset Password'))

@section('description', __('Reset Password'))

@push('scripts')
    <script>
        {{ get_form_store($errors, old()) }}
    </script>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Reset Password') }}</div>

                    <div class="card-body">
                        <form id="reset-password-form" method="post" action="{{ route('password.request') }}"
                              aria-label="{{ __('Reset Password') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <input-component label="{{ __('E-Mail Address') }}" type="email"
                                             name="email"></input-component>


                            <input-component label="{{ __('Password') }}" type="password"
                                             name="password"></input-component>

                            <input-component label="{{ __('Confirm Password') }}" type="password"
                                             name="password_confirmation"></input-component>


                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
