<div class="card category">
    @if($category->image)
    <img src="{{ $category->image->url_thumbnail }}" class="card-img-top" alt="{{ $category->label }}">
    @endif
    <div class="card-body">
        <h3 class="card-title">
            <a href="{{ $category->url }}">{{ $category->label }}</a>
        </h3>
    </div>
</div>
