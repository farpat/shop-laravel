<template>
    <section>
        <Menu></Menu>

        <ProductReference :key="getCurrentReference.id"></ProductReference>
    </section>
</template>

<script>
    import Menu from "./Menu/MenuComponent";
    import TranslationMixin from "../src/Translation/TranslationMixin";
    import ProductStore from "./ProductStore";
    import ProductReference from "./ProductReference/ItemComponent";

    export default {
        mixins: [TranslationMixin],
        components: {ProductReference, Menu},
        data: function () {
            return {
                productState: ProductStore.state,
            }
        },
        computed: {
            getCurrentReference: function () {
                return ProductStore.state.currentReference
            }
        }
    }
</script>
