<template>
    <tr class="header-cart-item">
        <td>
            <input :value="quantity" @change="(e) => this.updateItem(e)" class="form-control header-cart-item-quantity"
                   min="1" type="number">
        </td>
        <td>
            <a :href="item.product_reference.url">{{ item.product_reference.label }}</a>
        </td>
        <td>
            {{ getAmountIncludingTaxes }}
        </td>
        <td class="header-cart-item-td-icon">
            <button @click="(e) => deleteItem(e)" class="btn btn-sm btn-link text-danger p-0" type="button"
                    v-show="!isLoading">
                <i class="fas fa-times"></i>
            </button>

            <span v-show="isLoading">
                <i class="fas fa-spinner spinner"></i>
            </span>
        </td>
    </tr>
</template>


<script>
    import TranslationMixin from "../../src/Translation/TranslationMixin";
    import CartStore from "../CartStore";
    import StrMixin from "../../src/String/StrMixin";

    export default {
        mixins: [TranslationMixin, StrMixin],
        data: function () {
            return {
                quantity: this.item.quantity
            }
        },
        props: {
            item: {type: Object, required: true}
        },
        computed: {
            getAmountIncludingTaxes: function () {
                return this.toLocaleCurrency(this.item.amount_including_taxes, CartStore.data.currency);
            },
            isLoading: function () {
                return CartStore.state.isLoading[this.item.product_reference_id];
            }
        },
        methods: {
            deleteItem: function () {
                if (!this.isLoading) {
                    CartStore.deleteItem(this.item.product_reference_id);
                }
            },
            updateItem: function (event) {
                const quantity = parseFloat(event.target.value);

                if (quantity > 0) {
                    CartStore.updateItem(this.item.product_reference_id, quantity);
                    this.quantity = quantity;
                }
            }
        }
    }
</script>
