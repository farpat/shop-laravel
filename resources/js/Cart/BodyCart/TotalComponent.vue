<template>
    <tfoot class="header-cart-total">
    <tr style="font-size:1.5rem;">
        <td colspan="2">{{ __('Total to pay') }} :</td>
        <td colspan="2">{{ getFormattedAmountIncludingTaxes }}</td>
    </tr>
    <tr class="header-cart-total-vat">
        <td class="text-right" colspan="2">{{ __('Including taxes') }} :</td>
        <td colspan="2">{{ getFormattedIncludingTaxes }}</td>
    </tr>
    </tfoot>
</template>


<script>
    import TranslationMixin from "../../src/Translation/TranslationMixin";
    import StrMixin from "../../src/String/StrMixin";
    import CartStore from "../CartStore";

    export default {
        mixins:   [TranslationMixin, StrMixin],
        props:    {
            items: {type: Object, required: true}
        },
        computed: {
            getPurchaseUrl:                   function () {
                return CartStore.data.purchaseUrl;
            },
            getAmountIncludingTaxes:          function () {
                let totalPriceIncludingVat = 0;

                for (let productReferenceId in this.items) {
                    totalPriceIncludingVat += this.items[productReferenceId]['amount_including_taxes'];
                }
                return totalPriceIncludingVat;
            },
            getFormattedAmountIncludingTaxes: function () {
                return this.toLocaleCurrency(this.getAmountIncludingTaxes, CartStore.data.currency);
            },
            getAmountExcludingTaxes:          function () {
                let totalPriceExcludingTaxes = 0;

                for (let productReferenceId in this.items) {
                    totalPriceExcludingTaxes += this.items[productReferenceId]['amount_excluding_taxes'];
                }
                return totalPriceExcludingTaxes;
            },
            getIncludingTaxes:                function () {
                return this.getAmountIncludingTaxes - this.getAmountExcludingTaxes;
            },
            getFormattedIncludingTaxes:       function () {
                return this.toLocaleCurrency(this.getIncludingTaxes, CartStore.data.currency);
            }
        }
    }
</script>
