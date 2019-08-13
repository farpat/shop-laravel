<template>
    <article class="row">
        <div class="col-md-8" v-if="getCurrentReference.main_image">
            <Slider :reference="getCurrentReference"></Slider>
        </div>
        <div class="col-md">
            <ul>
                <li>
                    {{ translate('Unit price') }} : {{
                    getUnitPriceIncludingTaxes(getCurrentReference.unit_price_including_taxes) }}
                </li>
                <li>
                    <QuantityComponent :reference="getCurrentReference"></QuantityComponent>
                </li>
            </ul>
        </div>
    </article>
</template>

<script>
    import ProductStore from "../ProductStore";
    import CartStore from "../../Cart/CartStore";
    import TranslationMixin from "../../src/Translation/TranslationMixin";
    import Str from "../../src/String/Str";
    import QuantityComponent from "../../Cart/QuantityComponent";
    import Slider from "./SliderComponent";

    export default {
        mixins: [TranslationMixin],
        components: {QuantityComponent, Slider},
        methods: {
            getFilledProductValue: function (reference) {
                return ProductStore.getFilledProductValue(reference)
            },
            getUnitPriceIncludingTaxes: function (amount) {
                return Str.toLocaleCurrency(amount, CartStore.data.currency);
            },
        },
        computed: {
            getCurrentReference: function () {
                return ProductStore.state.currentReference;
            }
        }
    }
</script>
