<template>
    <div :id="getId" class="carousel slide carousel-fade" data-ride="carousel" style="margin-bottom: 45px;">
        <ol class="carousel-indicators mb-0" style="bottom: -45px;">
            <li :class="{'active':index === currentIndex}"
                :data-slide-to="index"
                :data-target="getTarget"
                :key="index" v-for="(image, index) in reference.images">
                <img :alt="image.alt_thumbnail" :src="image.url_thumbnail" class="d-block w-100">
            </li>
        </ol>
        <div class="carousel-inner">
            <div :class="{'active':index === currentIndex}" :key="image.id"
                 class="carousel-item"
                 v-for="(image,index) in reference.images">
                <img :alt="image.alt" :src="image.url" class="d-block w-100">
            </div>
        </div>
        <a :href="getTarget" class="carousel-control-prev" data-slide="prev" role="button">
            <span aria-hidden="true" class="carousel-control-prev-icon"></span>
            <span class="sr-only">{{ translate('pagination.previous') }}</span>
        </a>
        <a :href="getTarget" class="carousel-control-next" data-slide="next" role="button">
            <span aria-hidden="true" class="carousel-control-next-icon"></span>
            <span class="sr-only">{{ translate('pagination.next') }}</span>
        </a>
    </div>
</template>

<script>
    import TranslationMixin from "../../src/Translation/TranslationMixin";

    export default {
        mixins: [TranslationMixin],
        data: function () {
            return {
                currentIndex: 1
            };
        },
        mounted: function () {
            $(this.getTarget).on('slid.bs.carousel', (e) => this.currentIndex = e.to);
        },
        props: {
            reference: {type: Object, required: true}
        },
        computed: {
            getId: function () {
                return 'carousel-reference-' + this.reference.id;
            },
            getTarget: function () {
                return '#' + this.getId;
            }
        }
    }
</script>
