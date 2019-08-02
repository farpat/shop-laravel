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
    import ProductStore from "../ProductStore";
    import {pick} from 'lodash';

    export default {
        components: {Item},
        computed: {
            getReferences: function () {
                const ids = ProductStore.data.productReferenceIds;
                return pick(CartStore.data.allProductReferences, ids);
            }
        }
    }
</script>
