@extends('_layouts.app')

@section('title', $product->label)

@section('description', $product->meta_description)

@section('content')
    <h1 class="mr-5">{{ __('Product') }} : {{ $product->label }}</h1>

    {{ breadcrumb($breadcrumb) }}

    <section id="product-show">
        <product-references-component></product-references-component>

        <section class="mt-5 text-justify">
            {!!  $product->description !!}
        </section>
    </section>
@endsection


@push('scripts')
    <script>
        window.productStore = {
            state: {
                currentReference: {}
            },
            data: {
                allReferences: @json($product->references),
                baseUrl: '{{ url()->current() }}',
                currency: '{{ $currency }}',
                productFields: @json($productFields)
            }
        };
    </script>
@endpush
