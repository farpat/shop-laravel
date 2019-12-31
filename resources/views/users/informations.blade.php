@extends('_layouts.app')

@section('title'){{ __('My informations') }}@endsection

@section('description'){{ __('My informations') }}@endsection

@push('scripts')
    <script>
        {{ get_form_store($errors, $form) }}
    </script>
@endpush

@section('script', 'user-informations')

@section('content')
    <div class="container">
        <h1>{{ __('My informations') }}</h1>
        <form id="informations-form" @change="onChange($event)"
              method="post" action="{{ route('user.informations') }}">
            @csrf
            @method('PUT')

            <h2>{{ __('Primary informations') }}</h2>
            <div class="row">
                <div class="col-md-6">
                    <input-component name="name" label="{{ __('Name') }}" autofocus
                                     rules="required|min:4"></input-component>
                </div>

                <div class="col-md-6">
                    <input-component name="email" label="{{ __('E-Mail Address') }}"
                                     type="email" rules="required|email"></input-component>
                </div>
            </div>


            <h2 class="mt-3">{{ __('Addresses') }}</h2>
            <addresses-component
                    lang="{{ app()->getLocale() }}"
                    :initial-addresses='@json($form['addresses'])'
                    api-key="{{ config('app.algolia_api_key') }}"
                    app-id="{{ config('app.algolia_app_id') }}"></addresses-component>

            <div class="form-group mt-5">
                <a type="button" class="btn btn-link" href="{{ route('user.informations') }}">
                    {{ __('Cancel') }}
                </a>

                <button type="submit" id="submit" class="btn btn-primary">
                    {{ __('Change my informations') }}
                </button>
            </div>
        </form>
    </div>
@endsection
