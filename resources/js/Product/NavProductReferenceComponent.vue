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
    import productStore from "./productStore";

    export default {
        props: {
            reference: {type: Object, required: true}
        },
        data: function () {
            return {
                state: productStore.state
            }
        },
        methods: {
            getLiClass: function (reference) {
                return {
                    'media': true, 'mb-4': true,
                    'bg-primary': reference === this.state.currentReference
                };
            },
            getTitleClass: function (reference) {
                return {
                    'mt-0': true, 'mb-1': true,
                    'text-white': reference === this.state.currentReference
                }
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
