@extends('_layouts.app')

@section('title'){{ __('Welcome') }}@endsection

@section('description'){{ __('Welcome') }}@endsection

@section('js-require', 'home')

@section('content')
    @if($products->isNotEmpty())
        <section class="products">
            <h2>{{ __('Products in the spotlight') }}</h2>
            <div class="card-group">
                @foreach($products as $product)
                    @include('_partials.product')
                @endforeach
            </div>
        </section>
    @endif

    @if($categories->isNotEmpty())
        <section class="categories">
            <h2>{{ __('Categories in the spotlight') }}</h2>
            <div class="card-group">
                @foreach($categories as $category)
                    @include('_partials.category')
                @endforeach
            </div>
        </section>
    @endif

    @if($elements->isNotEmpty())
        <section class="elements row">
            @foreach($elements as $element)
                <div class="col element">
                    @include('_partials.element')
                </div>
            @endforeach
        </section>
    @endif

    @if($slides->isNotEmpty())
        @include('_partials.carousel')
    @endif
@endsection
