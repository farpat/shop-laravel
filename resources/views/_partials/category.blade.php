<div class="card category">
    <img src="{{ $category->image->url_thumbnail ?? '' }}" class="card-img-top" alt="{{ $category->label }}">
    <div class="card-body">
        <h3 class="card-title">{{ $category->label }}</h3>
    </div>
</div>
