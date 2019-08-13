<template>
    <section>
        <div class="row text-right">
            <div class="offset-5 col-4">{{ translate('Total price') }} :</div>
            <div class="col-3 text-left">{{ getFormattedAmountIncludingTaxes }}</div>
        </div>
        <div class="row text-right">
            <div class="offset-5 col-4">{{ translate('Including VAT') }} :</div>
            <div class="col-3 text-left">{{ getFormattedIncludingVat }}</div>
        </div>
    </section>
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
                let totalPriceExcludingVat = 0;

                for (let productReferenceId in this.items) {
                    totalPriceExcludingVat += this.items[productReferenceId]['amount_excluding_taxes'];
                }
                return totalPriceExcludingVat;
            },
            getIncludingVat: function () {
                return this.getAmountIncludingTaxes - this.getAmountExcludingTaxes;
            },
            getFormattedIncludingVat: function () {
                return this.toLocaleCurrency(this.getIncludingVat, CartStore.data.currency);
            }
        }
    }
</script>
