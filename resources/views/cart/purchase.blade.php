@extends('_layouts.app')

@section('title', __('pucharse cart'))

@section('description', __('pucharse cart'))

@section('content')
    <h1>{{ __('pucharse cart') }}</h1>

    <section id="cart-section" class="cart-section">
        <body-cart-component></body-cart-component>

        <payment-component></payment-component>
    </section>
@endsection