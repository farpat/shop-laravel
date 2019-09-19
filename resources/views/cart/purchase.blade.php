@extends('_layouts.app')

@section('title', __('pucharse cart'))

@section('description', __('pucharse cart'))

@section('content')
    <h1>{{ __('pucharse cart') }}</h1>

    <section id="cart-section" class="cart-section row">
        <div class="col-md-8 bg-light mb-5">
            <body-cart-component></body-cart-component>
        </div>

        <div class="col-md-4 mb-5">
            <payment-component></payment-component>
        </div>
    </section>
@endsection