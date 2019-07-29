@extends('_layouts.app')

@section('title',$category->label)

@section('description', $category->meta_description)

@section('content')
    <h1>{{ __('Category') }} : {{ $category->label }}</h1>

    {!!  breadcrumb($breadcrumb) !!}

    @if($products->isNotEmpty())
        <section id="category-show">
            <filter-component :filters='@json($filters)'></filter-component>
            <products-component></products-component>
        </section>
    @endif
@endsection


@if($products->isNotEmpty())
    @push('scripts')
        <script>
            window.categoryStore = {
                state: {
                    currentProducts: [],
                    filterValues: @json($filterValues, JSON_FORCE_OBJECT),
                    perPage: {{ $perPage }},
                    currentPage: {{ $currentPage }}
                },
                data: {
                    allProducts: @json($products),
                    baseUrl: '{{ url()->current() }}'
                }
            };
        </script>
    @endpush
@endif
