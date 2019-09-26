@extends('_layouts.app')

@section('title'){{ __('Welcome') }}@endsection

@section('description'){{ __('Welcome') }}@endsection

@section('content')
    <div class="container">

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

        @if($categories->isNotEmpty())
            <section class="home-section categories">
                <h2>{{ __('Categories in the spotlight') }}</h2>
                <div class="card-deck">
                    @foreach($categories as $category)
                        @include('_partials.category', compact('category'))
                    @endforeach
                </div>
            </section>
        @endif

        @if($elements->isNotEmpty())
            <section class="home-section elements row">
                @foreach($elements as $element)
                    <div class="col">
                        @include('_partials.element', compact('element'))
                    </div>
                @endforeach
            </section>
        @endif

        @if($slides->isNotEmpty())
            <section class="home-section slides">
                @include('_partials.carousel', compact('slides'))
            </section>
        @endif
    </div>
@endsection
