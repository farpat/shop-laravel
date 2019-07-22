@extends('_layouts.app')

@section('title'){{ $product->label }}@endsection

@section('description'){{ $product->meta_description }}@endsection

@section('content')
    <section>
        <h1>{{ __('Product') }} : {{ $product->label }}</h1>
    </section>
@endsection
