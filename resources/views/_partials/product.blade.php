<article class="card product">
    @if($product->main_image)
    <img src="{{ $product->main_image->url_thumbnail }}" class="card-img-top" alt="{{ $product->label }}">
    @endif
    <div class="card-body">
        <h3 class="card-title"><a href="{{ $product->url }}">{{ $product->label }}</a></h3>
    </div>
</article>
