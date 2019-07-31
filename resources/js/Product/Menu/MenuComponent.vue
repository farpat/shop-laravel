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
    import CartStore from "../../Cart/CartStore";

    export default {
        components: {Item},
        computed: {
            getReferences: function () {
                return CartStore.data.allReferences;
            }
        }
    }
</script>
