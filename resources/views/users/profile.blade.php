@extends('_layouts.app')

@section('title', __('Your profile'))

@section('description', __('Your profile'))

@section('content')
    <div class="container">
        <h1>{{ __('Your profile') }}</h1>

        <ul>
            <li>
                <a href="{{ route('user.informations') }}">{{ __('Change my informations') }}</a>
            </li>
            <li>
                <a href="{{ route('user.password') }}">{{ __('Change my password') }}</a>
            </li>
        </ul>
    </div>
@endsection
