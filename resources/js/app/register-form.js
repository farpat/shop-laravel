import Vue from "vue";

import {InputComponent, CheckboxComponent, NumberComponent, ModalComponent} from "../src/Bootstrap";
import FormStore from "../src/Bootstrap/FormStore";
import {RequiredRule, EmailRule, ConfirmedRule, MinRule} from "../src/Security";
import FormMixin from "../src/Bootstrap/FormMixin";
import Translation from "../src/Translation/Translation";

new Vue({
    el:         '#register-form',
    components: {InputComponent, CheckboxComponent, NumberComponent},
    mixins: [FormMixin],
    mounted:    function () {
        this.$submitButton = this.$el.querySelector('#submit');

        FormStore.setRules({
            name:                  [new RequiredRule()],
            email:                 [new RequiredRule(), new EmailRule()],
            password:              [new RequiredRule(), new MinRule(6)],
            password_confirmation: [new RequiredRule(), new ConfirmedRule('#password')],
            accept:                [new RequiredRule()]
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

const modalButton = document.querySelector('#terms-of-use-modal-button');
modalButton.addEventListener('click', function () {
    let modalConstructor = Vue.extend(ModalComponent);
    let modalInstance = new modalConstructor({
        propsData: {
            title: Translation.get('Terms of use'),
            content: 'Content',
            type: 'primary'
        },
    });

    modalInstance.$mount();
});
