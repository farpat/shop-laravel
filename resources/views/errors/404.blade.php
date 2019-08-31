@extends('_layouts.error')

@section('title', __('Resource not found'))

@section('description', __('Resource not found'))


@section('content')
    <div class="row">
        <section class="col">
            <img src="{{asset('/svg/404.svg')}}" alt="{{ __('Error 404') }}">
        </section>

        <section class="col">
            <h1>{{ __('Resource not found') }}</h1>
            <p>
                {{ __('main.error404') }} : <a href="{{ route('home.index') }}">{{ route('home.index') }}</a>
            </p>
        </section>
    </div>
@endsection
