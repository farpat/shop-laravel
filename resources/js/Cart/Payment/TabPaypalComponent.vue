<template>
    <div class="tab-pane fade" id="nav-tab-paypal">
        <p>{{ __('Paypal is easiest way to pay online') }}</p>
        <p>
            <button class="btn btn-primary rounded-pill" type="button">
                <i class="fab fa-paypal mr-2"></i> {{ __('Log into my Paypal') }}
            </button>
        </p>
        <p class="text-muted">{{ __('Paypal is good!') }}
        </p>
    </div>
</template>

<script>
    import TranslationMixin from "../../src/Translation/TranslationMixin";

    export default {
        mixins: [TranslationMixin]
    }
</script>