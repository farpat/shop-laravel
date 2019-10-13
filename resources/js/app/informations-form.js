import Vue from "vue";

import {CheckboxComponent, InputComponent, NumberComponent} from "../src/Bootstrap";
import AddressesComponent from "../Profile/Address/AddressesComponent";
import FormMixin from "../src/Bootstrap/FormMixin";

new Vue({
    el:         '#informations-form',
    components: {InputComponent, CheckboxComponent, NumberComponent, AddressesComponent},
    mixins:     [FormMixin],
    methods:    {
        onChange: function (event) {
            console.log('change', event);
        }
    }
});