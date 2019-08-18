<template>
    <div :class="getLiClass" class="nav-product-reference-item col-md-3">
        <a @click="(e) => setCurrentProductReference(reference, e)" class="row no-gutters nav-product-reference-item-container">
            <img :alt="reference.label" :src="reference.main_image.url_thumbnail" v-if="reference.main_image">
            <h2 :class="getTitleClass" class="mt-0 mb-1 h6">{{ reference.label }}</h2>
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
