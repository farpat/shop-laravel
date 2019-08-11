@extends('_layouts.app')

@section('title', $product->label)

@section('description', $product->meta_description)

@section('content')
    <h1 class="mr-5">{{ __('Product') }} : {{ $product->label }}</h1>

    {{ breadcrumb($breadcrumb) }}

    <section id="product-show">
        <product-component></product-component>

        <section class="mt-2 text-justify">
            {!! $product->description !!}
        </section>
    </section>
@endsection


@push('scripts')
    <script>
        window.ProductStore = {
            state: {},
            data: {
                baseUrl: '{{ url()->current() }}',
                currency: '{{ $currency }}',
                productFields: @json($productFields),
                productReferenceIds: @json($product->references->pluck('id'))
            }
        };
    </script>
@endpush
