import Vue from "vue";

import {CheckboxComponent, InputComponent} from "../src/Bootstrap";
import FormStore from "../src/Bootstrap/Store";
import FormMixin from "../src/Bootstrap/FormMixin";
import {EmailRule, RequiredRule} from "../src/Security";

new Vue({
    el:         '#login-form',
    components: {InputComponent, CheckboxComponent},
    mixins:     [FormMixin],
    mounted:    function () {
        FormStore.setRules({
            email:    [new RequiredRule(), new EmailRule()],
            password: [new RequiredRule()],
        });
    },
});
