<template>
    <li>
        {{ product.label }}
    </li>
</template>

<script>
    export default {
        props: {
            product: {type: Object, required: true}
        }
    }
</script>
