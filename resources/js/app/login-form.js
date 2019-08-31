import Vue from "vue";

import {CheckboxComponent, InputComponent} from "../src/Bootstrap";
import FormStore from "../src/Bootstrap/FormStore";
import FormMixin from "../src/Bootstrap/FormMixin";
import {EmailRule, RequiredRule} from "../src/Security";

new Vue({
    el:         '#login-form',
    components: {InputComponent, CheckboxComponent},
    mixins:     [FormMixin],
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

        onChange: function (e) {
            if (e.target.name === 'password') {
                this.$submitButton.disabled = FormStore.hasErrors();
            }
        }
    }
});
