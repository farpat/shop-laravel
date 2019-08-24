import Vue from "vue";

import InputComponent from "../src/Bootstrap/Form/InputComponent";
import CheckboxComponent from "../src/Bootstrap/Form/CheckboxComponent";

import RequiredRule from "../src/Security/Rules/RequiredRule";
import EmailRule from "../src/Security/Rules/EmailRule";

import FormStore from "../src/Bootstrap/Form/FormStore";

new Vue({
    el:         '#login-form',
    components: {InputComponent, CheckboxComponent},
    data:       function () {
        return {
            state: FormStore.state
        }
    },
    mounted:    function () {
        this.$submitButton = this.$el.querySelector('#submit');

        this.rules = {
            email:    [new RequiredRule(), new EmailRule()],
            password: [new RequiredRule()],
        };
    },
    methods:    {
        onSubmit: function (event) {
            this.checkForm();

            if (this.hasErrors()) {
                this.$submitButton.disabled = true;
                event.preventDefault();
            }
        },

        onChange: function () {
            this.$submitButton.disabled = this.hasErrors();
        }
    }
});
