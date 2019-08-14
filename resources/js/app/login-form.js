import Vue from "vue";
import InputComponent from "../src/Bootstrap/Form/InputComponent";
import CheckboxComponent from "../src/Bootstrap/Form/CheckboxComponent";
import RequiredRule from "../src/Security/Rules/RequiredRule";
import EmailRule from "../src/Security/Rules/EmailRule";
import FormMixin from "../src/Bootstrap/Form/includes/FormMixin";

new Vue({
    el: '#login-form',
    components: {InputComponent, CheckboxComponent},
    mixins: [FormMixin],
    mounted: function () {
        this.$submitButton = this.$el.querySelector('#submit');

        this.rules = {
            email: [new RequiredRule(), new EmailRule()],
            password: [new RequiredRule()],
        };
    },
    methods: {
        onSubmit: function (event) {
            this.verifyForm();

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
