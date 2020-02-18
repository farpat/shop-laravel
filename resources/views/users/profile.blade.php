@extends('_layouts.app')

@section('title', __('Profile management'))

@section('description', __('Profile management'))

@section('content')
    <div class="container">
        <h1>{{ __('Profile management') }}</h1>

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

            <h2>Administrator action</h2>

            <ul>
                <li>
                    First item
                </li>
            </ul>
        @endif
    </div>
@endsection
