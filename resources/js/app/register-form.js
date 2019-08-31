import Vue from "vue";

import InputComponent from "../src/Bootstrap/Form/InputComponent";
import CheckboxComponent from "../src/Bootstrap/Form/CheckboxComponent";
import NumberComponent from "../src/Bootstrap/Form/NumberComponent"

import RequiredRule from "../src/Security/Rules/RequiredRule";
import EmailRule from "../src/Security/Rules/EmailRule";
import ConfirmedRule from "../src/Security/Rules/ConfirmedRule";
import MinRule from "../src/Security/Rules/MinRule";

import FormStore from "../src/Bootstrap/Form/FormStore";

new Vue({
    el:         '#register-form',
    components: {InputComponent, CheckboxComponent, NumberComponent},
    data:       function () {
        return {
            state: FormStore.state
        }
    },
    mounted:    function () {
        this.$submitButton = this.$el.querySelector('#submit');

        this.state.rules = {
            name:                  [new RequiredRule()],
            email:                 [new RequiredRule(), new EmailRule()],
            password:              [new RequiredRule(), new MinRule(6)],
            password_confirmation: [new RequiredRule(), new ConfirmedRule('#password')],
            accept:                [new RequiredRule()]
        };
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
