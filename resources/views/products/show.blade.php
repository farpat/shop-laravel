@extends('_layouts.app')

@section('title', $product->label)

@section('description', $product->meta_description)

@section('script', 'product-show')

@section('content')
    <div class="container">
        {{ breadcrumb($breadcrumb) }}

        <section class="header-product-show">
            <h1>{{ $product->label }}</h1>
        </section>

        <section id="product-show">
            <product-component></product-component>

            <section class="mt-2 text-justify">
                {!! $product->description !!}
            </section>
        </section>
    </div>
@endsection


@push('scripts')
    <script>
        window._ProductStore = {
            state: {},
            data:  {
                baseUrl: '{{ url()->current() }}',
                productFields: @json($productFields),
                productReferences: @json($product->references)
            }
        };
    </script>
@endpush
