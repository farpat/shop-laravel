<template>
    <article class="row">
        <div class="col-md-8">
            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel" style="margin-bottom: 45px;">
                <ol class="carousel-indicators mb-0" style="bottom: -45px;">
                    <li v-for="(image, index) in reference.images" :class="{'active':image === reference.images[0]}"
                        :data-slide-to="index" data-target="#carouselExampleControls">
                        <img :src="image.url_thumbnail" class="d-block w-100" :alt="image.alt_thumbnail">
                    </li>
                </ol>
                <div class="carousel-inner">
                    <div v-for="image in reference.images" :key="image.id"
                         :class="{'carousel-item':true, 'active':image === reference.images[0]}" data-interval="50000000000000000">
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
                    {{ translate('Price') }} : {{ getUnitPriceIncludingTaxes(reference.unit_price_including_taxes) }}
                </li>
            </ul>
        </div>
    </article>
</template>

<script>
    import productStore from "./productStore";
    import TranslationMixin from "../src/Translation/TranslationMixin";
    import Str from "../src/String/Str";

    export default {
        mixins: [TranslationMixin],
        props: {
            reference: {type: Object, required: true}
        },
        methods: {
            getFilledProductValue: function (reference) {
                return productStore.getFilledProductValue(reference)
            },
            getUnitPriceIncludingTaxes: function (amount) {
                return Str.toLocaleCurrency(amount, productStore.data.currency);
            }
        }
    }
</script>
