@extends('_layouts.app')

@section('title', __('Login'))

@section('description', __('Login'))

@push('scripts')
    <script>
        {{ get_form_store($errors, old()) }}
    </script>
@endpush

@section('content')
    <div class="container">
        <section class="row justify-content-center">
            <div class="col-md-8">
                <section class="card">
                    <div class="card-header">{{ __('Login') }}</div>

                    <div class="card-body">
                        <form id="login-form" method="post" action="{{ route('login') }}"
                              aria-label="{{ __('Login') }}">
                            @csrf

                            <input type="hidden" name="purchase" value="{{ $wantPurchase }}">

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
                </section>

                <section class="users-section mt-5">
                    <h2>{{ __('Users') }}</h2>

                    <div class="card-group">
                        @foreach($users->chunk(5) as $chunkedUsers)
                            <div class="card">
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        @foreach($chunkedUsers as $id => $email)
                                            <li><a href="{{ route('spy', ['user' => $id]) }}">{{ $email }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

            </div>
        </section>
    </div>
@endsection
