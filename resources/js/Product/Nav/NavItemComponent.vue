<template>
    <div :class="getLiClass" class="nav-product-reference-item">
        <a @click="(e) => setCurrentProductReference(reference, e)" class="nav-product-reference-item-container">
            <img :alt="reference.label" :src="reference.main_image.url_thumbnail"
                 class="nav-product-reference-item-image" v-if="reference.main_image">
            <h2 :class="getTitleClass" class="nav-product-reference-item-title">{{ reference.label }}</h2>
        </a>
    </div>
</template>

<script>
    import ProductStore from "../ProductStore";

    export default {
        props: {
            reference: {type: Object, required: true}
        },
        computed: {
            getLiClass: function () {
                return {
                    'bg-primary': this.reference === ProductStore.state.currentProductReference
                };
            },
            getTitleClass: function () {
                return {
                    'text-white': this.reference === ProductStore.state.currentProductReference
                }
            }
        },
        methods: {
            setCurrentProductReference: function (reference, event) {
                event.preventDefault();

                if (reference !== ProductStore.state.currentProductReference) {
                    ProductStore.setCurrentReference(reference);
                }
            }
        }
    }
</script>
