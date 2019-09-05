<template>
    <div class="row align-items-center" v-if="getItem === undefined">
        <div class="col-auto">
            <number-component :min="1" :name="'quantity-' + this.reference.id"></number-component>
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
    import NumberComponent from "../src/Bootstrap/components/NumberComponent";
    import FormStore from "../src/Bootstrap/FormStore";

    export default {
        mixins:     [TranslationMixin],
        components: {NumberComponent},
        props:      {
            reference: {type: Object, required: true}
        },
        data:       function () {
            return {
                cartState: CartStore.state,
                formState: FormStore.state
            };
        },
        computed:   {
            getItem:     function () {
                return CartStore.getItem(this.reference.id);
            },
            isLoading:   function () {
                return this.cartState.isLoading[this.reference.id];
            },
            getQuantity: function () {
                return this.formState.datas['quantity-' + this.reference.id]
            }
        },
        methods:    {
            addInCart: function () {
                CartStore.addItem(this.reference.id, this.getQuantity);
                window.setTimeout(() => FormStore.changeField('quantity-' + this.reference.id, 1), 500);
            }
        }
    }
</script>


