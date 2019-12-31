@extends('_layouts.app')

@section('title', __('Your profile'))

@section('description', __('Your profile'))

@section('content')
    <div class="container">
        <h1>{{ __('Your profile') }}</h1>

        <ul class="list-group">
            <li class="list-group-item">
                <a href="{{ route('user.informations') }}">{{ __('Change my informations') }}</a>
            </li>
            <li class="list-group-item">
                <a href="{{ route('user.password') }}">{{ __('Change my password') }}</a>
            </li>
            <li class="list-group-item">
                <a href="{{ route('user.billings') }}">{{ __('See your billings') }}</a>
            </li>
        </ul>

        @if($user->is_admin)
            <hr>

            <ul>
                <li>
                    Administrator action
                </li>
            </ul>
        @endif
    </div>
@endsection
