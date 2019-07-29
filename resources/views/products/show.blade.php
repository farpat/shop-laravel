@extends('_layouts.app')

@section('title', $product->label)

@section('description', $product->meta_description)

@section('content')
    <h1 class="mr-5">{{ __('Product') }} : {{ $product->label }}</h1>

    {!!  breadcrumb($breadcrumb) !!}

    @if($product->references->isNotEmpty())
        <section id="product-show">
            <product-references-component>
            </product-references-component>
        </section>

    @endif
@endsection


@if($product->references->isNotEmpty())
    @push('scripts')
        <script>
            window.productStore = {
                state: {
                    currentReference: {}
                },
                data: {
                    allReferences: @json($product->references),
                    baseUrl: '{{ url()->current() }}'
                }
            };
        </script>
    @endpush
@endif
