<template>
    <div>
        <div class="row align-items-center" v-if="getItem === undefined">
            <div class="col-auto">
                {{ translate('Quantity') }} :
                <label>
                    <input class="form-control d-inline-block" min="1" style="max-width: 5rem" type="number"
                           v-model.number="quantity">
                </label>
            </div>
            <div class="col-auto">
                <button @click="() => addInCart()" class="btn btn-sm btn-primary" type="button">
                    <i class="fas fa-shopping-cart"></i> {{ translate('Add in cart') }}
                </button>
            </div>
        </div>

        <p v-else>
            {{ translate('Ordering') }} : {{ getItem.quantity }}
        </p>
    </div>
</template>

<script>
    import TranslationMixin from "../src/Translation/TranslationMixin";
    import CartStore from "./CartStore";

    export default {
        mixins: [TranslationMixin],
        props: {
            reference: {type: Object, required: true}
        },
        data: function () {
            return {
                quantity: 1,
            };
        },
        computed: {
            getItem: function () {
                return CartStore.getItem(this.reference.id);
            }
        },
        methods: {
            addInCart: function () {
                CartStore.setItem(this.reference.id, this.quantity);
                this.quantity = 1;
            }
        }
    }
</script>


