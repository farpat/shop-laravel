import Vue from "vue";

import {InputComponent} from "../src/Bootstrap";
import FormMixin from "../src/Bootstrap/FormMixin";

new Vue({
    el:         '#send-token-password-form',
    components: {InputComponent},
    mixins:     [FormMixin]
});
