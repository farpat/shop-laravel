@extends('_layouts.app')

@section('title',$category->label)

@section('description', $category->meta_description)

@section('content')
    {{ breadcrumb($breadcrumb) }}

    <section class="header-category-show">
        @if($category->image)
            <img src="{{ $category->image->url }}" alt="{{ $category->image->alt }}">
        @endif

        <h1>
            {{ $category->label }} - {{ $products->count() }} {{ trans_choice('product', $products->count()) }}
        </h1>
    </section>

    <section id="category-show">
        @if($filters->isNotEmpty())
            <filter-component :filters='@json($filters, JSON_FORCE_OBJECT)'></filter-component>
        @endif
        <products-component></products-component>
    </section>
@endsection


@push('scripts')
    <script>
        window._CategoryStore = {
            state: {
                filterValues: @json($filterValues, JSON_FORCE_OBJECT),
                perPage: {{ $perPage }},
                currentPage: {{ $currentPage ?? 1 }}
            },
            data:  {
                allProducts: @json($products),
                baseUrl: '{{ url()->current() }}'
            }
        };
    </script>
@endpush
