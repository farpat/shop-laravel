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
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('My informations') }}</div>

                        <div class="card-body">

                            <form id="informations-form" @change="onChange($event)" @submit="onSubmit($event)" method="post"
                                  action="{{ route('user.informations') }}">
                                @csrf

                                <input-component name="name" label="{{ __('Name') }}" autofocus></input-component>

                                <input-component name="email" label="{{ __('E-Mail Address') }}"
                                                 type="email"></input-component>

                                <div class="form-group mt-5">
                                    <button type="submit" id="submit" class="btn btn-primary">
                                        {{ __('Change my informations') }}
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
