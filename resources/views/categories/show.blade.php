@extends('_layouts.app')

@section('title'){{ $category->label }}@endsection

@section('description'){{ $category->meta_description }}@endsection

@section('content')
    <h1>{{ __('Category') }} : {{ $category->label }}</h1>

    @if($products->isNotEmpty())
        <section class="filters">
            Ici les filtres
        </section>

        <section class="products">
            @foreach ($products->chunk(4) as $batchOfProducts)
                <div class="row">
                    @foreach($batchOfProducts as $product)
                        <div class="col">
                            @include('_partials.product')
                        </div>
                    @endforeach
                </div>
            @endforeach

            {{ $products->links() }}
        </section>
    @endif
@endsection
