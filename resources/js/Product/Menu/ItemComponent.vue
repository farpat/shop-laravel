<template>
    <div :class="getLiClass(reference)">
        <a href="#" @click="(e) => setCurrentProductReference(reference, e)">
            <img v-if="reference.main_image"
                 :src="reference.main_image.url_thumbnail" class="mr-3" :alt="reference.label">
        </a>

        <div class="media-body" style="cursor:pointer;" @click="(e) => setCurrentProductReference(reference, e)">
            <h3 :class="getTitleClass(reference)">{{ reference.label }}</h3>
        </div>
    </div>
</template>

<script>
    import ProductStore from "../ProductStore";

    export default {
        props: {
            reference: {type: Object, required: true}
        },
        methods: {
            getLiClass: function (reference) {
                return {
                    'media': true, 'mb-4': true,
                    'bg-primary': reference === ProductStore.state.currentProductReference
                };
            },
            getTitleClass: function (reference) {
                return {
                    'mt-0': true, 'mb-1': true,
                    'text-white': reference === ProductStore.state.currentProductReference
                }
            },
            setCurrentProductReference: function (reference, event) {
                event.preventDefault();

                if (reference !== ProductStore.state.currentProductReference) {
                    ProductStore.setCurrentReference(reference);
                }
            }
        }
    }
</script>
