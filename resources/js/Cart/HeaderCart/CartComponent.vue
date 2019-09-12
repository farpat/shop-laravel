<template>
    <div v-if="getLength > 0">
        <button aria-expanded="false" aria-haspopup="true" class="nav-link btn btn-link dropdown-toggle mr-md-2"
                data-toggle="dropdown" id="button-cart">
            <i class="fas fa-shopping-cart"></i> - {{ getLength }}
        </button>
        <div aria-labelledby="button-cart" class="dropdown-menu dropdown-menu-right header-cart">
            <table class="table table-borderless">
                <tbody>
                <ItemComponent
                    :item="item" :key="item.product_reference_id"
                    v-for="item in cartState.cartItems">
                </ItemComponent>
                </tbody>

                <TotalComponent :items="cartState.cartItems"></TotalComponent>
            </table>
        </div>
    </div>

    <div v-else>
        <div class="nav-link">
            <i class="fas fa-shopping-cart"></i>
        </div>
    </div>
</template>


<script>
    import TranslationMixin from "../../src/Translation/TranslationMixin";
    import CartStore from "../CartStore";
    import ItemComponent from "./ItemComponent";
    import TotalComponent from "./TotalComponent";
    import FormMixin from "../../src/Bootstrap/FormMixin";

    export default {
        components: {TotalComponent, ItemComponent},
        mixins: [TranslationMixin, FormMixin],
        data: function () {
            return {
                cartState: CartStore.state
            }
        },
        computed: {
            getLength: function () {
                return this.cartState.cartItemsLength;
            }
        }
    }
</script>
