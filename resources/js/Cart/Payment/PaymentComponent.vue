<template>
    <form :action="action" @submit="submit($event)" id="payment-form" method="post">
        <input :value="getCsrfToken" name="_token" type="hidden">

        <div class="form-group">
            <label for="card-element">{{ __('Credit card') }}</label>

            <div id="card-element"></div>

            <div class="invalid-feedback d-block" id="card-errors" role="alert"></div>
        </div>

        <button class="btn btn-primary btn-block" id="submit-button" type="submit">
            {{ __('Confirm') }}
        </button>
    </form>
</template>

<script>
    import TranslationMixin from "../../src/Translation/TranslationMixin";
    import Requestor from "@farpat/api";

    export default {
        mixins:   [TranslationMixin],
        props:    {
            stripeKey: {type: String, required: true},
            action:    {type: String, required: true}
        },
        mounted:  function () {
            this.$stripe = Stripe(this.stripeKey);
            const elements = this.$stripe.elements();

            let style = {
                base:    {
                    color:           '#32325d',
                    fontFamily:      '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing:   'antialiased',
                    fontSize:        '16px',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color:     '#fa755a',
                    iconColor: '#fa755a'
                }
            };

            this.$card = elements.create('card', {style});
            this.$card.mount('#card-element');

            this.$errorElement = this.$el.querySelector('#card-errors');

            this.$card.addEventListener('change', (event) => {
                this.$errorElement.textContent = event.error ? event.error.message : '';
            });

            this.$submitButton = this.$el.querySelector('#submit-button');
        },
        computed: {
            getCsrfToken: function () {
                return Requestor.getCsrfToken();
            }
        },
        methods:  {
            submit: function (event) {
                event.preventDefault();

                this.$stripe.createToken(this.$card).then((result) => {
                    if (result.error) {
                        this.$errorElement.textContent = result.error.message;
                    } else {
                        this.$el.insertAdjacentHTML('beforeend', `<input type="hidden" name="stripe_token" value="${result.token.id}">`);
                        this.$submitButton.disabled = true;
                        this.$submitButton.innerText = this.__('Loading ...');
                        this.$el.submit();
                    }
                });
            },
        }
    }
</script>