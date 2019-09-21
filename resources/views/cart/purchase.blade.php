@extends('_layouts.app')

@section('title', __('pucharse cart'))

@section('description', __('pucharse cart'))

@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
@endpush

@section('content')
    <div class="container-fluid px-5">
        <h1>{{ __('pucharse cart') }}</h1>

        <section id="cart-section" class="cart-section row">
            <div class="col-lg-8 bg-light mb-5">
                <body-cart-component></body-cart-component>
            </div>

            <div class="col-lg-4 mb-5">
                <payment-component stripe-key="{{ config('services.stripe.key') }}"
                                   action="{{ route('cart.purchase') }}"></payment-component>
            </div>
        </section>
    </div>
@endsection