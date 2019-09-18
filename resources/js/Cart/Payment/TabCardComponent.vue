<!--suppress XmlInvalidId -->
<template>
    <div class="tab-pane fade show active" id="nav-tab-card">
        <CreditCardNumberComponent :label="__('Credit Card')" name="card-number"></CreditCardNumberComponent>


        <div class="row">
            <div class="col-sm-auto">

                <label class="col-form-label" for="card-month">{{ __('Expiration')}}</label>

                <RequiredComponent :label="true" :required="true"></RequiredComponent>

                <div class="d-flex align-items-center">
                    <InputComponent :length="2" name="card-expire-month" placeholder="MM" :display-error="false"></InputComponent>
                    <div class="mb-3 mx-2"> /</div>
                    <InputComponent :length="2" name="card-expire-year" placeholder="YY" :display-error="false"></InputComponent>
                </div>

            </div>

            <div class="col-sm">
                <InputComponent :label="__('CVC')" :length="3" name="card-cvc" placeholder="CVC"></InputComponent>
            </div>
        </div>
        <button class="btn btn-primary btn-block" type="button">
            {{ __('Confirm') }}
        </button>
    </div>
</template>


<script>
    import TranslationMixin from "../../src/Translation/TranslationMixin";
    import FormMixin from "../../src/Bootstrap/FormMixin";
    import RequiredComponent from "../../src/Bootstrap/includes/RequiredComponent";
    import {CreditCardNumberComponent, InputComponent} from "../../src/Bootstrap";
    import FormStore from "../../src/Bootstrap/FormStore";
    import {MaxRule, MinRule, RegexRule, RequiredRule} from "../../src/Security";

    export default {
        mixins:     [TranslationMixin, FormMixin],
        components: {InputComponent, CreditCardNumberComponent, RequiredComponent},
        mounted() {
            const minYear = parseInt(('' + (new Date).getFullYear()).substr(2, 4));

            FormStore.setRules({
                "card-number":       [new RequiredRule(), new RegexRule(/^(\d{4} ){3}\d{4}$/)],
                "card-expire-month": [new RequiredRule(), new MinRule(1, 'number'), new MaxRule(12, 'number')],
                "card-expire-year":  [new RequiredRule(), new MinRule(minYear, 'number'), new MaxRule(minYear + 10, 'number')],
                "card-cvc":          [new RequiredRule(), new RegexRule(/^\d{3}$/)],
            });
        }
    }
</script>