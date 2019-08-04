<template>
    <section>
        <Menu></Menu>

        <keep-alive>
            <ProductReference :key="getCurrentReference.id"></ProductReference>
        </keep-alive>
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
