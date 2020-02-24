@php($categories = app(\App\Repositories\CategoryRepository::class)->getCategoriesInHome())

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