@php
    $elementsParameter = app(App\Repositories\ModuleRepository::class)->getParameter('home', 'elements');
    $elements = collect($elementsParameter->value ?? []);
@endphp

@if($elements->isNotEmpty())
    <section class="home-section elements row">
        @foreach($elements as $element)
            <div class="col">
                @include('_partials.element', compact('element'))
            </div>
        @endforeach
    </section>
@endif