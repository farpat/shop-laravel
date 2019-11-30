<template>
    <div :id="getId" class="carousel slide carousel-fade carousel-product" data-ride="carousel">
        <div class="carousel-inner">
            <div :class="{'active':index === currentIndex}" :key="image.id"
                 class="carousel-item"
                 v-for="(image,index) in reference.images">
                <img :alt="image.alt" :src="image.url">
            </div>
        </div>

        <a v-if="reference.images.length > 1" :href="getTarget" class="carousel-control-prev" data-slide="prev"
           role="button">
            <span aria-hidden="true" class="carousel-control-prev-icon"></span>
            <span class="sr-only">{{ __('pagination.previous') }}</span>
        </a>
        <a v-if="reference.images.length > 1" :href="getTarget" class="carousel-control-next" data-slide="next"
           role="button">
            <span aria-hidden="true" class="carousel-control-next-icon"></span>
            <span class="sr-only">{{ __('pagination.next') }}</span>
        </a>

        <ol v-if="reference.images.length > 1" class="carousel-indicators">
            <li :class="{'active':index === currentIndex}"
                :data-slide-to="index"
                :data-target="getTarget"
                :key="index" v-for="(image, index) in reference.images">
                <img :alt="image.alt_thumbnail" :src="image.url_thumbnail" class="d-block w-100">
            </li>
        </ol>
    </div>
</template>

<script>
    import TranslationMixin from "../../src/Translation/TranslationMixin";

    export default {
        mixins:   [TranslationMixin],
        data:     function () {
            return {
                currentIndex: 0
            };
        },
        mounted:  function () {
            if (this.reference.images.length > 1) {
                $(this.getTarget).on('slid.bs.carousel', (e) => this.currentIndex = e.to);
            }
        },
        props:    {
            reference: {type: Object, required: true}
        },
        computed: {
            getId:     function () {
                return 'carousel-reference-' + this.reference.id;
            },
            getTarget: function () {
                return '#' + this.getId;
            }
        }
    }
</script>
