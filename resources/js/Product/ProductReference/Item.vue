<template>
    <article class="row">
        <div class="col-md-8" v-if="getCurrentReference.main_image">
            <keep-alive>
                <Slider :images="getCurrentReference.images" :key="getCurrentReference.id"></Slider>
            </keep-alive>
        </div>
        <div class="col-md">
            <ul>
                <li>
                    {{ translate('Unit price') }} : {{
                    getUnitPriceIncludingTaxes(getCurrentReference.unit_price_including_taxes) }}
                </li>
                <li>
                    <keep-alive>
                        <QuantityComponent :key="getCurrentReference.id"
                                           :reference="getCurrentReference"></QuantityComponent>
                    </keep-alive>
                </li>
            </ul>
        </div>
    </article>
</template>

<script>
    import ProductStore from "../ProductStore";
    import TranslationMixin from "../../src/Translation/TranslationMixin";
    import Str from "../../src/String/Str";
    import QuantityComponent from "../../Cart/QuantityComponent";
    import Slider from "./Slider";

    export default {
        mixins: [TranslationMixin],
        components: {QuantityComponent, Slider},
        methods: {
            getFilledProductValue: function (reference) {
                return ProductStore.getFilledProductValue(reference)
            },
            getUnitPriceIncludingTaxes: function (amount) {
                return Str.toLocaleCurrency(amount, ProductStore.data.currency);
            },
        },
        computed: {
            getCurrentReference: function () {
                return ProductStore.state.currentReference;
            }
        }
    }
</script>
