@extends('_layouts.app')

@section('title'){{ __('Change my password') }}@endsection

@section('description'){{ __('Change my password') }}@endsection

@push('scripts')
    <script>
        {{ get_form_store($errors, []) }}
    </script>
@endpush

@section('script', 'user-password')

@section('content')
    <div class="container">
        <h1>{{ __('Change my password') }}</h1>

        <form id="password-form" method="post"
              action="{{ route('user.password') }}">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <input-component name="password" type="password" label="{{ __('Current password') }}"
                                     rules="required" autofocus></input-component>
                </div>
            </div>

            <h2>{{ __('Choose a new password') }}</h2>
            <div class="row">
                <div class="col-md-6">
                    <input-component name="new_password" type="password" label="{{ __('New password') }}"
                                     rules="required"></input-component>
                </div>
                <div class="col-md-6">
                    <input-component name="new_password_confirmation" type="password"
                                     label="{{ __('Confirm password') }}"
                                     rules="required|confirmed:#new_password"></input-component>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" id="submit" class="btn btn-primary">
                    {{ __('Change my password') }}
                </button>
            </div>
        </form>
    </div>
@endsection
