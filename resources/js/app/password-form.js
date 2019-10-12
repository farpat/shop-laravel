import Vue from "vue";

import {CheckboxComponent, InputComponent, NumberComponent} from "../src/Bootstrap";
import FormStore from "../src/Bootstrap/Store";
import {ConfirmedRule, MinRule, RequiredRule} from "../src/Security";
import FormMixin from "../src/Bootstrap/FormMixin";

new Vue({
    el:         '#password-form',
    components: {InputComponent, CheckboxComponent, NumberComponent},
    mixins:     [FormMixin],
    mounted:    function () {
        this.$submitButton = this.$el.querySelector('#submit');

        FormStore.setRules({
            password:              [new RequiredRule(), new MinRule(5)],
            new_password:          [new RequiredRule(), new MinRule(6)],
            new_password_confirmation: [new RequiredRule(), new ConfirmedRule('#new_password')],
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
