<template>
    <div>
        <nav class="nav-product-reference row no-gutters">
            <NavItem :key="reference.id" :reference="reference" v-for="reference in getReferences"></NavItem>
        </nav>

        <hr>
    </div>
</template>


<script>
    import NavItem from "./NavItemComponent";
    import ProductStore from "../ProductStore";

    export default {
        components: {NavItem},
        computed: {
            getReferences: function () {
                return ProductStore.data.productReferences;
            }
        }
    }
</script>
