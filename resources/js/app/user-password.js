import Vue from "vue";

import {CheckboxComponent, InputComponent, NumberComponent} from "../src/Bootstrap";
import FormStore from "../src/Bootstrap/Store";
import {ConfirmedRule, MinRule, RequiredRule} from "../src/Security";
import FormMixin from "../src/Bootstrap/FormMixin";

new Vue({
    el:         '#password-form',
    components: {InputComponent, CheckboxComponent, NumberComponent},
    mixins:     [FormMixin],
});
