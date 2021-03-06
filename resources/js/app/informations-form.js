import Vue from "vue";

import {CheckboxComponent, InputComponent, NumberComponent} from "../src/Bootstrap";
import FormStore from "../src/Bootstrap/FormStore";
import {EmailRule, RequiredRule} from "../src/Security";
import FormMixin from "../src/Bootstrap/FormMixin";

new Vue({
    el:         '#informations-form',
    components: {InputComponent, CheckboxComponent, NumberComponent},
    mixins:     [FormMixin],
    mounted:    function () {
        this.$submitButton = this.$el.querySelector('#submit');

        FormStore.setRules({
            name:  [new RequiredRule()],
            email: [new RequiredRule(), new EmailRule()],
        });
    },
    methods:    {
        onSubmit: function (event) {
            FormStore.checkForm();

            if (FormStore.hasErrors()) {
                this.$submitButton.disabled = true;
                event.preventDefault();
            }
        },

        onChange: function () {
            this.$submitButton.disabled = FormStore.hasErrors();
        }
    }
});