<template>
    <nav class="nav-product-reference">
        <item :key="reference.id" :reference="reference" v-for="reference in getReferences"></item>
    </nav>
</template>


<script>
    import Item from "./NavItemComponent";
    import ProductStore from "../ProductStore";

    export default {
        components: {Item},
        computed: {
            getReferences: function () {
                return ProductStore.data.productReferences;
            }
        }
    }
</script>
