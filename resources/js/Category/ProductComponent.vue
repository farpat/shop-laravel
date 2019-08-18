<template>
    <div class="col-md-3 align-self-stretch">
        <article class="card product">
            <a :href="product.url">
                <img src="https://picsum.photos/300" class="card-img-top" :alt="product.label">
            </a>
            <div class="card-body">
                <h3 class="card-title"><a :href="product.url">{{ product.label }}</a></h3>
                <p class="card-text">{{ product.excerpt }}</p>
            </div>
        </article>
    </div>
</template>

<script>
    export default {
        props: {
            product: {type: Object, required: true}
        }
    }
</script>
