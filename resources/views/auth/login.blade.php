@extends('_layouts.app')

@section('title', __('Login'))

@section('description', __('Login'))

@push('scripts')
    <script>
        {{ get_form_store($errors, old()) }}
    </script>
@endpush

@section('content')
    <section class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form id="login-form" @change="onChange($event)" @submit="onSubmit($event)" method="POST"
                          action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                        @csrf

                        <input-component autofocus label="{{ __('E-Mail Address') }}" name="email" type="email"
                        ></input-component>

                        <input-component label="{{ __('Password') }}" name="password" required type="password"
                        ></input-component>


                        <checkbox-component label="{{ __('Remember Me') }}" name="remember">
                        </checkbox-component>

                        <div class="form-group mt-4">
                            <button id="submit" type="submit" class="btn btn-primary">{{ __('Login') }}</button>

                            <a class="btn btn-link"
                               href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
