import Vue from "vue";

import {CheckboxComponent, InputComponent, NumberComponent} from "../src/Bootstrap";
import AddressesComponent from "../Profile/Address/AddressesComponent";
import FormStore from "../src/Bootstrap/Store";
import FormMixin from "../src/Bootstrap/FormMixin";

new Vue({
    el:         '#informations-form',
    components: {InputComponent, CheckboxComponent, NumberComponent, AddressesComponent},
    mixins:     [FormMixin],
    mounted:    function () {
        this.$submitButton = this.$el.querySelector('#submit');
    },
    methods:    {
        onSubmit: function (event) {
            if (FormStore.hasErrors()) {
                this.$submitButton.disabled = true;
                event.preventDefault();
            }
        },

        onChange: function () {
            console.log('change has errors', FormStore.hasErrors());
            this.$submitButton.disabled = FormStore.hasErrors();
        }
    }
});