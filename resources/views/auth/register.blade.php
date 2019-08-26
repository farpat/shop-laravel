@extends('_layouts.app')

@section('title'){{ __('Register') }}@endsection

@section('description'){{ __('Register') }}@endsection

@push('scripts')
    <script>
        {{ get_form_store($errors, old()) }}
    </script>
@endpush

@section('content')
    <section>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>

                    <div class="card-body">

                        <form id="register-form" @change="onChange($event)" @submit="onSubmit($event)" method="POST" action="{{ route('register') }}">
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

                            <number-component name="quantity"></number-component>


                            <div class="form-group mt-5">
                                <button type="submit" id="submit" class="btn btn-primary">
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
