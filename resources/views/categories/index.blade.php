@extends('_layouts.app')

@section('title',__('All categories'))

@section('description',__('All categories'))

@section('content')
    <div class="container">
        <h1>{{ __('All categories') }}</h1>

        {{ breadcrumb($breadcrumb) }}

        <section id="category-index" class="category-index">
            {{ $html }}
        </section>
    </div>
@endsection
