<template>
    <article class="row">
        <div class="col-md-8" v-if="getCurrentReference.main_image">
            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel" style="margin-bottom: 45px;">
                <ol class="carousel-indicators mb-0" style="bottom: -45px;">
                    <li :class="{'active':index===0}"
                        v-for="(image, index) in getCurrentReference.images"
                        :data-slide-to="index" data-target="#carouselExampleControls">
                        <img :src="image.url_thumbnail" class="d-block w-100" :alt="image.alt_thumbnail">
                    </li>
                </ol>
                <div class="carousel-inner">
                    <div :class="{'active':index===0}" :key="image.id"
                         class="carousel-item"
                         data-interval="50000000000000000"
                         v-for="(image,index) in getCurrentReference.images">
                        <img :src="image.url" class="d-block w-100" :alt="image.alt">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">{{ translate('pagination.previous') }}</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">{{  translate('pagination.next') }}</span>
                </a>
            </div>
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

    export default {
        mixins: [TranslationMixin],
        components: {QuantityComponent},
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
