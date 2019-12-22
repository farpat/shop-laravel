<template>
    <tr class="header-cart-item">
        <td>
            <NumberComponent :min="1" :name="`quantity[${this.item.product_reference_id}]`"
                             @decrease="updateItem($event)"
                             @increase="updateItem($event)"
                             direction="horizontal">
            </NumberComponent>
        </td>
        <td>
            <a :href="item.product_reference.url" @click.prevent="goToReference(item.product_reference.url)">
                {{ item.product_reference.product.label }} | {{ item.product_reference.label }}
            </a>
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
                return this.toLocaleCurrency(this.item.amount_including_taxes, CartStore.data.currencyCode);
            },
            isLoading:               function () {
                return CartStore.state.isLoading[this.item.product_reference_id];
            }
        },
        methods:    {
            goToReference(referenceUrl) {
                const referenceUrlObject = new URL(referenceUrl);
                const currentUrlObject = new URL(window.location.href);

                if (referenceUrlObject.pathname === currentUrlObject.pathname) { //force the url loading if only hash is different
                    window.location.href = referenceUrlObject.origin + referenceUrlObject.pathname + '?r=1' + referenceUrlObject.hash;
                } else {
                    window.location.href = referenceUrl;
                }
            },
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
