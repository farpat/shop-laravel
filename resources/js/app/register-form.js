import Vue from "vue";
import InputComponent from "../src/Bootstrap/Form/InputComponent";
import CheckboxComponent from "../src/Bootstrap/Form/CheckboxComponent";
import RequiredRule from "../src/Security/Rules/RequiredRule";
import EmailRule from "../src/Security/Rules/EmailRule";
import FormMixin from "../src/Bootstrap/Form/includes/FormMixin";
import ConfirmedRule from "../src/Security/Rules/ConfirmedRule";
import MinRule from "../src/Security/Rules/MinRule";

new Vue({
    el: '#register-form',
    components: {InputComponent, CheckboxComponent},
    mixins: [FormMixin],
    mounted: function () {
        this.$submitButton = this.$el.querySelector('#submit');

        this.rules = {
            name: [new RequiredRule()],
            email: [new RequiredRule(), new EmailRule()],
            password: [new RequiredRule(), new MinRule(6)],
            password_confirmation: [new RequiredRule(), new ConfirmedRule('#password')],
            accept: [new RequiredRule()]
        };
    },
    methods: {
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
