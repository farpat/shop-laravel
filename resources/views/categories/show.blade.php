@extends('_layouts.app')

@section('title'){{ $category->label }}@endsection

@section('description'){{ $category->meta_description }}@endsection

@section('content')
    <h1>{{ __('Category') }} : {{ $category->label }}</h1>

    @if($products->isNotEmpty())
        <section id="category-show">
            <filter-component></filter-component>
            <products-component :products='@json($products)'></products-component>
        </section>
    @endif
@endsection


@if($products->isNotEmpty())
    @push('scripts')
        <script>
            window.categoryStore = {
                filters: @json($filters),
                filterValues: @json($filterValues, JSON_FORCE_OBJECT),
                perPage: {{ $perPage }},
                currentPage: {{ $currentPage }}
            };

            window.baseUrl = '{{ url()->current() }}';
        </script>
    @endpush
@endif
