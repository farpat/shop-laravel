import Vue from "vue";

import {InputComponent, CheckboxComponent} from "../src/Bootstrap";
import FormStore from "../src/Bootstrap/FormStore";
import FormMixin from "../src/Bootstrap/FormMixin";
import {RequiredRule, EmailRule} from "../src/Security";

new Vue({
    el:         '#login-form',
    components: {InputComponent, CheckboxComponent},
    mixins: [FormMixin],
    mounted:    function () {
        this.$submitButton = this.$el.querySelector('#submit');

        FormStore.setRules({
            email:    [new RequiredRule(), new EmailRule()],
            password: [new RequiredRule()],
        });
    },
    methods:    {
        onSubmit: function (event) {
            if (FormStore.hasErrors()) {
                event.preventDefault();
            }
        },

        onChange: function () {
            FormStore.checkForm();
            this.$submitButton.disabled = FormStore.hasErrors();
        }
    }
});
