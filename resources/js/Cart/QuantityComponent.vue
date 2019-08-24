<template>
    <div class="row align-items-center" v-if="getItem === undefined">
        <div class="col-auto">
            {{ translate('Quantity') }} :
            <input class="form-control d-inline-block" min="1" style="max-width: 5rem" type="number"
                   v-model.number="quantity">
        </div>
        <div class="col-auto">
            <button @click="() => addInCart()" class="btn btn-primary" type="button" v-show="!isLoading">
                <i class="fas fa-shopping-cart"></i> {{ translate('Add in cart') }}
            </button>

            <span v-show="isLoading">
                <i class="fas fa-spinner spinner"></i>
            </span>
        </div>
    </div>

    <p v-else>
        {{ translate('Ordering') }} : {{ getItem.quantity }}
    </p>
</template>

<script>
    import TranslationMixin from "../src/Translation/TranslationMixin";
    import CartStore from "./CartStore";
    import QuantityComponent from "../src/Bootstrap/Form/QuantityComponent";

    export default {
        components: {QuantityComponent},
        mixins: [TranslationMixin],
        props: {
            reference: {type: Object, required: true}
        },
        data: function () {
            return {
                quantity: 1,
                state: CartStore.state
            };
        },
        computed: {
            getItem: function () {
                return CartStore.getItem(this.reference.id);
            },
            isLoading: function () {
                return CartStore.state.isLoading[this.reference.id];
            }
        },
        methods: {
            addInCart: function () {
                CartStore.addItem(this.reference.id, this.quantity);
                window.setTimeout(() => this.quantity = 1, 500);
            }
        }
    }
</script>


