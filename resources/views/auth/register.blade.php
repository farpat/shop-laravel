@extends('_layouts.app')

@section('title'){{ __('Register') }}@endsection

@section('description'){{ __('Register') }}@endsection

@section('js-require', 'registerForm')

@push('scripts')
    <script>
        {!! get_form_store($errors, old()) !!}
    </script>
@endpush

@section('content')
    <section>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>

                    <div class="card-body">

                        <form id="register-form" method="POST" action="{{ route('register') }}">
                            @csrf

                            <input-component name="name" label="{{ __('Name') }}" autofocus></input-component>

                            <input-component name="email" label="{{ __('E-Mail Address') }}"
                                             type="email"></input-component>


                            <input-component name="password" label="{{ __('Password') }}" type="password"
                            ></input-component>

                            <input-component name="password_confirmation" label="{{ __('Confirm Password') }}"
                                             type="password"
                            ></input-component>

                            <checkbox-component name="accept"
                                                label="{{ __('I accept the registration') }}"></checkbox-component>


                            <div class="form-group mt-5">
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="modal-component"></div>
@endsection
