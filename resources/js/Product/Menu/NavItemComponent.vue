<template>
    <div class="media" :class="getLiClass">
        <a href="#" @click="(e) => setCurrentProductReference(reference, e)">
            <img v-if="reference.main_image" :src="reference.main_image.url_thumbnail" class="mr-3" :alt="reference.label">
        </a>

        <div class="media-body" @click="(e) => setCurrentProductReference(reference, e)">
            <h3 :class="getTitleClass">{{ reference.label }}</h3>
        </div>
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
                    'mt-0': true, 'mb-1': true,
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
