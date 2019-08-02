<template>
    <section v-if="getLength > 0">
        <button aria-expanded="false" aria-haspopup="true"
                class="nav-link btn btn-link dropdown-toggle mr-md-2" data-toggle="dropdown" id="button-cart">
            <i class="fas fa-shopping-cart"></i>
            - {{ getLength }}
        </button>
        <div aria-labelledby="button-cart" class="dropdown-menu dropdown-menu-right">
            <div class="container">
                <div v-show="getLength > 0">
                    <ItemComponent
                        :item="item" :key="item.product_reference_id"
                        v-for="item in cartState.cartItems">
                    </ItemComponent>

                    <div class="dropdown-divider"></div>

                    <TotalComponent :items="cartState.cartItems"></TotalComponent>
                </div>

                <div v-show="getLength === 0">
                    {{ translate('Empty cart') }}
                </div>
            </div>
        </div>
    </section>

    <section v-else>
        <div class="nav-link">
            <i class="fas fa-shopping-cart"></i>
        </div>
    </section>
</template>


<script>
    import TranslationMixin from "../../src/Translation/TranslationMixin";
    import CartStore from "../CartStore";
    import ItemComponent from "./ItemComponent";
    import TotalComponent from "./TotalComponent";

    export default {
        components: {TotalComponent, ItemComponent},
        mixins: [TranslationMixin],
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
