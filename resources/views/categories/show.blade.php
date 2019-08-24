@extends('_layouts.app')

@section('title',$category->label)

@section('description', $category->meta_description)

@section('content')
    <h1>{{ __('Category') }} : {{ $category->label }}</h1>

    {{ breadcrumb($breadcrumb) }}

    <section id="category-show">
        @if($filters->isNotEmpty())
            <filter-component :filters='@json($filters, JSON_FORCE_OBJECT)'></filter-component>
        @endif
        <products-component></products-component>
    </section>
@endsection


@push('scripts')
    <script>
        window.CategoryStore = {
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
