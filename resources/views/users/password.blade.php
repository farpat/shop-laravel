@extends('_layouts.app')

@section('title'){{ __('My informations') }}@endsection

@section('description'){{ __('My informations') }}@endsection

@push('scripts')
    <script>
        {{ get_form_store($errors, []) }}
    </script>
@endpush

@section('script', 'user-password')

@section('content')
    <div class="container">
        <section>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Change my password') }}</div>

                        <div class="card-body">

                            <form id="password-form" method="post"
                                  action="{{ route('user.password') }}">
                                @csrf


                                <input-component name="password" type="password" label="{{ __('Current password') }}" rules="required"
                                                 autofocus></input-component>

                                <h2 class="mt-5 h5">{{ __('Change of password') }}</h2>
                                <input-component name="new_password" type="password"
                                                 label="{{ __('New password') }}" rules="required"></input-component>
                                <input-component name="new_password_confirmation" type="password"
                                                 label="{{ __('Confirm password') }}" rules="required|confirmed:#new_password"></input-component>

                                <div class="form-group mt-5">
                                    <button type="submit" id="submit" class="btn btn-primary">
                                        {{ __('Change my password') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
