<template>
    <ul class="list-unstyled">
        <li :class="getLiClass(reference)" v-for="reference in getProductReferences" :key="reference.id">
            <a href="#" @click="(e) => setCurrentProductReference(reference, e)">
                <img :src="reference.main_image.url_thumbnail" v-if="reference.main_image" class="mr-3"
                     :alt="reference.label">
            </a>

            <div class="media-body">
                <h5 class="mt-0 mb-1">{{ reference.label }}</h5>
            </div>
        </li>
    </ul>
</template>

<script>
    import productStore from "./productStore";

    export default {
        data: function () {
            return {
                state: productStore.state
            }
        },
        computed: {
            getProductReferences: function () {
                return productStore.data.allReferences
            }
        },
        methods: {
            getLiClass: function (reference) {
                return {
                    'media': true,
                    'mb-4': true,
                    'bg-primary': reference === productStore.state.currentReference,
                };
            },
            setCurrentProductReference: function (reference, event) {
                event.preventDefault();

                if (reference !== productStore.state.currentReference) {
                    productStore.setCurrentProductReference(reference);
                }
            }
        }
    }
</script>
