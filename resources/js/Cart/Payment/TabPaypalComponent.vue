<template>
    <div class="tab-pane fade" id="nav-tab-paypal">
        <p class="py-1">
            {{ __('Paypal is easiest way to pay online') }}

            <button class="btn btn-primary" type="button">
                <i class="fab fa-paypal mr-2"></i> {{ __('Log into my Paypal') }}
            </button>
        </p>
    </div>
</template>

<script>
    import TranslationMixin from "../../src/Translation/TranslationMixin";

    export default {
        mixins: [TranslationMixin]
    }
</script>