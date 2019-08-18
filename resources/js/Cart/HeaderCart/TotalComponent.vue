<template>
    <tfoot class="header-cart-total table-sm">
    <tr>
        <td colspan="2">{{ translate('Total price') }} :</td>
        <td>{{ getFormattedAmountIncludingTaxes }}</td>
        <td></td>
    </tr>
    <tr class="header-cart-total-vat">
        <td colspan="2" class="text-right">{{ translate('Including taxes') }} :</td>
        <td>{{ getFormattedIncludingTaxes }}</td>
        <td></td>
    </tr>
    <tr>
        <td colspan="4">
            <button class="float-right btn btn-primary">{{ translate('Purchase') }}</button>
        </td>
    </tr>
    </tfoot>
</template>


<script>
    import TranslationMixin from "../../src/Translation/TranslationMixin";
    import StrMixin from "../../src/String/StrMixin";
    import CartStore from "../CartStore";

    export default {
        mixins: [TranslationMixin, StrMixin],
        props: {
            items: {type: Object, required: true}
        },
        computed: {
            getAmountIncludingTaxes: function () {
                let totalPriceIncludingVat = 0;

                for (let productReferenceId in this.items) {
                    totalPriceIncludingVat += this.items[productReferenceId]['amount_including_taxes'];
                }
                return totalPriceIncludingVat;
            },
            getFormattedAmountIncludingTaxes: function () {
                return this.toLocaleCurrency(this.getAmountIncludingTaxes, CartStore.data.currency);
            },
            getAmountExcludingTaxes: function () {
                let totalPriceExcludingTaxes = 0;

                for (let productReferenceId in this.items) {
                    totalPriceExcludingTaxes += this.items[productReferenceId]['amount_excluding_taxes'];
                }
                return totalPriceExcludingTaxes;
            },
            getIncludingTaxes: function () {
                return this.getAmountIncludingTaxes - this.getAmountExcludingTaxes;
            },
            getFormattedIncludingTaxes: function () {
                return this.toLocaleCurrency(this.getIncludingTaxes, CartStore.data.currency);
            }
        }
    }
</script>
