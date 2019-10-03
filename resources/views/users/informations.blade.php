@extends('_layouts.app')

@section('title'){{ __('My informations') }}@endsection

@section('description'){{ __('My informations') }}@endsection

@push('scripts')
    <script>
        {{ get_form_store($errors, $form) }}
    </script>
@endpush

@section('content')
    <div class="container">
        <section>
            <h1 class="row justify-content-center">
                <h1 class="mb-5">{{ __('My informations') }}</h1>

                <form id="informations-form"
                      @change="onChange($event)" @submit="onSubmit($event)"
                      method="post" action="{{ route('user.informations') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <input-component name="name" label="{{ __('Name') }}" autofocus></input-component>
                        </div>

                        <div class="col-md-6">
                            <input-component name="email" label="{{ __('E-Mail Address') }}"
                                             type="email"></input-component>
                        </div>
                    </div>


                    <h2 class="mt-3">{{ __('Addresses') }}</h2>
                    <addresses-component></addresses-component>

                    <div class="form-group mt-5">
                        <a type="button" class="btn btn-link">
                            {{ __('Cancel') }}
                        </a>

                        <button type="submit" id="submit" class="btn btn-primary">
                            {{ __('Change my informations') }}
                        </button>
                    </div>
                </form>
        </section>
    </div>
@endsection
