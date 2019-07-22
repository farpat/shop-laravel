<div class="card product">
    <img src="https://picsum.photos/300" class="card-img-top" alt="{{ $product->label }}">
    <div class="card-body">
        <h3 class="card-title"><a href="{{ $product->url }}">{{ $product->label }}</a></h3>
        <p class="card-text">{{ $product->excerpt }}</p>
    </div>
</div>
