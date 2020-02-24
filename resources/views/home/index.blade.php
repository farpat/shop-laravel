@extends('_layouts.app')

@section('title'){{ __('Welcome') }}@endsection

@section('description'){{ __('Welcome') }}@endsection

@section('content')
    <div class="container">
        @foreach($elementsToDisplayInHomepage as $element)
            @includeIf("home.index_partials.$element")
        @endforeach
    </div>
@endsection
