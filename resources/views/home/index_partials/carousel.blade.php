@php
    $carouselParameter = app(App\Repositories\ModuleRepository::class)->getParameter('home', 'carousel');
    $slides = collect($carouselParameter->value ?? []);
@endphp

@if($slides->isNotEmpty())
    <section class="home-section slides">
        @include('_partials.carousel', compact('slides'))
    </section>
@endif