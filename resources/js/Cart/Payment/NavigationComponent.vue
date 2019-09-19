<template>
    <ul class="nav nav-pills mb-2 bg-light nav-justified" role="tablist">
        <li class="nav-item">
            <a data-toggle="pill" href="#nav-tab-card" class="nav-link active">
                <i class="fa fa-credit-card"></i> {{ __('Credit Card') }}
            </a>
        </li>
        <li class="nav-item">
            <a data-toggle="pill" href="#nav-tab-paypal" class="nav-link">
                <i class="fab fa-paypal"></i> {{ __('Paypal') }}
            </a>
        </li>
    </ul>
</template>

<script>
    import TranslationMixin from "../../src/Translation/TranslationMixin";
    export default {
        mixins: [TranslationMixin]
    }
</script>