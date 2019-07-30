<template>
    <section>
        <nav>
            <NavProductReferenceComponent
                v-for="reference in getProductReferences" :key="reference.id"
                :reference="reference"
            >
            </NavProductReferenceComponent>
        </nav>

        <ProductReferenceComponent :reference="state.currentReference"></ProductReferenceComponent>
    </section>
</template>

<script>
    import ProductReferenceComponent from "./ProductReferenceComponent";
    import NavProductReferenceComponent from "./NavProductReferenceComponent";
    import productStore from "./productStore";
    import TranslationMixin from "../src/Translation/TranslationMixin";

    export default {
        mixins: [TranslationMixin],
        data: function () {
            return {
                state: productStore.state
            }
        },
        components: {NavProductReferenceComponent, ProductReferenceComponent},
        computed: {
            getProductReferences: function () {
                return productStore.data.allReferences
            }
        }
    }
</script>
