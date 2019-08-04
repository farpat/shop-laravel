@php($carouselId = 'carousel-home')

<div id="{{ $carouselId }}" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        @for($i=0; $i < $slides->count(); $i++)
            <li data-target="#{{ $carouselId }}" data-slide-to="{{ $i }}" class="{{ $i === 0 ? 'active' : '' }}"></li>
        @endfor
    </ol>
    <div class="carousel-inner">
        @foreach($slides as $slide)
            <div class="carousel-item{{ $loop->first ? ' active' : '' }}">
                <img src="{{ $slide->img }}" alt="{{ $slide->title }}" class="d-block w-100"
                     style="max-height: 350px;">
                <div class="carousel-caption">
                    <h3>{{ $slide->title }}</h3>
                    <p>{{ $slide->description }}</p>
                </div>
            </div>
        @endforeach
    </div>
    <a class="carousel-control-prev" href="#{{ $carouselId }}" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">{{ __('Previous') }}</span>
    </a>
    <a class="carousel-control-next" href="#{{ $carouselId }}" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">{{ __('Next') }}</span>
    </a>
</div>
