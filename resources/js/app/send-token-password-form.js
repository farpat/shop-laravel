import Vue from "vue";

import {InputComponent} from "../src/Bootstrap";
import FormStore from "../src/Bootstrap/FormStore";
import FormMixin from "../src/Bootstrap/FormMixin";
import {EmailRule, RequiredRule} from "../src/Security";

new Vue({
    el:         '#send-token-password-form',
    components: {InputComponent},
    mixins:     [FormMixin],
    mounted:    function () {
        this.$submitButton = this.$el.querySelector('#submit');

        FormStore.setRules({
            email: [new RequiredRule(), new EmailRule()],
        });
    },
    methods:    {
        onSubmit: function (event) {
            if (FormStore.hasErrors()) {
                event.preventDefault();
            }
        },
        onChange: function () {
            this.$submitButton.disabled = FormStore.hasErrors();
        }
    }
});
