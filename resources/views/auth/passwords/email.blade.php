@extends('_layouts.app')

@section('title', __('Reset Password'))

@section('description', __('Reset Password'))

@push('scripts')
    <script>
        {{ get_form_store($errors, old()) }}
    </script>
@endpush

@section('script', 'auth-email')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Reset Password') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form id="send-token-password-form" method="post" action="{{ route('password.email') }}"
                              aria-label="{{ __('Reset Password') }}">
                            @csrf

                            <input-component label="{{ __('E-Mail Address') }}" name="email"
                                             autofocus></input-component>

                            <div class="form-group">
                                <button type="submit" id="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
