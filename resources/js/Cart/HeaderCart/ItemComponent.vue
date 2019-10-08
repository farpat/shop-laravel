<template>
    <tr class="header-cart-item">
        <td>
            <NumberComponent :min="1" :name="'quantity[' + this.item.product_reference_id + ']'"
                             @decrease="updateItem($event)"
                             @increase="updateItem($event)"
                             direction="horizontal">
            </NumberComponent>
        </td>
        <td>
            <a :href="item.product_reference.url">{{ item.product_reference.product.label + ' | ' +
                item.product_reference.label }}</a>
        </td>
        <td>
            {{ getAmountIncludingTaxes }}
        </td>
        <td class="header-cart-item-td-icon">
            <button @click="(e) => deleteItem(e)" class="btn btn-sm btn-link p-0" type="button"
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
    import {NumberComponent} from "../../src/Bootstrap";

    export default {
        mixins:     [TranslationMixin, StrMixin],
        components: {NumberComponent},
        props:      {
            item: {type: Object, required: true}
        },
        computed:   {
            getAmountIncludingTaxes: function () {
                return this.toLocaleCurrency(this.item.amount_including_taxes, CartStore.data.currency);
            },
            isLoading:               function () {
                return CartStore.state.isLoading[this.item.product_reference_id];
            }
        },
        methods:    {
            deleteItem: function () {
                if (!this.isLoading) {
                    CartStore.deleteItem(this.item.product_reference_id);
                }
            },
            updateItem: function (event) {
                CartStore.updateItem(this.item.product_reference_id, event.quantity);
            }
        }
    }
</script>
