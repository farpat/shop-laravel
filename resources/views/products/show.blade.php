@extends('_layouts.app')

@section('title', $product->label)

@section('description', $product->meta_description)

@section('content')
    <section>
        <h1>{{ __('Product') }} : {{ $product->label }}</h1>
    </section>
@endsection
