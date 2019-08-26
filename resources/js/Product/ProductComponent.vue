<template>
    <section>
        <NavComponent></NavComponent>

        <keep-alive>
            <ProductReferenceComponent :key="getCurrentReference.id"></ProductReferenceComponent>
        </keep-alive>
    </section>
</template>

<script>
    import NavComponent from "./Nav/NavComponent";
    import TranslationMixin from "../src/Translation/TranslationMixin";
    import ProductStore from "./ProductStore";
    import ProductReferenceComponent from "./ProductReference/ItemComponent";

    export default {
        mixins:     [TranslationMixin],
        components: {ProductReferenceComponent, NavComponent},
        data:       function () {
            return {
                productState: ProductStore.state,
            }
        },
        computed:   {
            getCurrentReference: function () {
                return ProductStore.state.currentProductReference
            }
        }
    }
</script>
