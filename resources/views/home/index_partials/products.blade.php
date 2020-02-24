@php($products = app(\App\Repositories\ProductRepository::class)->getProductsInHome())

@if($products->isNotEmpty())
    <section class="home-section products">
        <h2>{{ __('Products in the spotlight') }}</h2>
        <div class="card-deck">
            @foreach($products as $product)
                @include('_partials.product', compact('product'))
            @endforeach
        </div>
    </section>
@endif