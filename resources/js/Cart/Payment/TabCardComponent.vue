<template>
    <div class="tab-pane fade show active" id="nav-tab-card">
        <div class="form-group">
            <div class="input-group">
                <input class="form-control" name="cardNumber" placeholder="Your card number" required type="text">
                <div class="input-group-append">
                        <span class="input-group-text text-muted">
                            <i class="fab fa-cc-visa mx-1"></i>
                            <i class="fab fa-cc-amex mx-1"></i>
                            <i class="fab fa-cc-mastercard mx-1"></i>
                        </span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-auto">
                <p>{{ __('Expiration') }}</p>

                <div class="form-group">
                    <div class="input-group">
                        <input class="form-control" maxlength="2" placeholder="MM" style="max-width: 4rem"
                               type="text">
                        <div class="input-group-append">
                            <span class="input-group-text">/</span>
                        </div>
                        <input class="form-control" maxlength="2" placeholder="YY" style="max-width: 4rem" type="text">
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <p>{{ __('CVV') }}</p>

                <div class="form-group">
                    <input class="form-control" maxlength="3" placeholder="CVV" style="max-width: 6rem" type="text">
                </div>
            </div>
        </div>
        <button class="subscribe btn btn-primary btn-block" type="button">
            {{ __('Confirm') }}
        </button>
    </div>
</template>


<script>
    import TranslationMixin from "../../src/Translation/TranslationMixin";

    export default {
        mixins: [TranslationMixin]
    }
</script>