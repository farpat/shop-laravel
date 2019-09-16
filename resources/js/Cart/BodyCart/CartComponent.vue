<template>
    <table class="table table-hover table-borderless">
        <tbody>
        <ItemComponent
                :item="item" :key="item.product_reference_id"
                v-for="item in cartState.cartItems">
        </ItemComponent>
        </tbody>

        <TotalComponent :items="cartState.cartItems"></TotalComponent>
    </table>
</template>


<script>
    import TranslationMixin from "../../src/Translation/TranslationMixin";
    import CartStore from "../CartStore";
    import ItemComponent from "./ItemComponent";
    import TotalComponent from "./TotalComponent";
    import FormMixin from "../../src/Bootstrap/FormMixin";

    export default {
        components: {TotalComponent, ItemComponent},
        mixins:     [TranslationMixin, FormMixin],
        data:       function () {
            return {
                cartState: CartStore.state
            }
        }
    }
</script>
