<template>
    <nav>
        <Item
            :key="reference.id"
            :reference="reference"
            v-for="reference in getReferences"
        >
        </Item>
    </nav>
</template>


<script>
    import Item from "./ItemComponent";
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
