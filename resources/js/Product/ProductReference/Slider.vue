<template>
    <div class="carousel slide" data-ride="carousel" id="carouselExampleControls" style="margin-bottom: 45px;">
        <ol class="carousel-indicators mb-0" style="bottom: -45px;">
            <li :class="{'active':isFirst(index)}"
                :data-slide-to="index"
                data-target="#carouselExampleControls" v-for="(image, index) in images">
                <img :alt="image.alt_thumbnail" :src="image.url_thumbnail" class="d-block w-100">
            </li>
        </ol>
        <div class="carousel-inner">
            <div :class="{'active':isFirst(index)}" :key="image.id"
                 class="carousel-item"
                 v-for="(image,index) in images">
                <img :alt="image.alt" :src="image.url" class="d-block w-100">
            </div>
        </div>
        <a class="carousel-control-prev" data-slide="prev" href="#carouselExampleControls" role="button">
            <span aria-hidden="true" class="carousel-control-prev-icon"></span>
            <span class="sr-only">{{ translate('pagination.previous') }}</span>
        </a>
        <a class="carousel-control-next" data-slide="next" href="#carouselExampleControls" role="button">
            <span aria-hidden="true" class="carousel-control-next-icon"></span>
            <span class="sr-only">{{ translate('pagination.next') }}</span>
        </a>
    </div>
</template>

<script>
    import TranslationMixin from "../../src/Translation/TranslationMixin";

    export default {
        mixins: [TranslationMixin],
        props: {
            images: {type: Array, required: true}
        },
        methods: {
            isFirst: function (index) {
                return index === 0;
            }
        }
    }
</script>
