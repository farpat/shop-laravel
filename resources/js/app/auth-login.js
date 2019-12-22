import Vue from "vue";
import {CheckboxComponent, InputComponent} from "../src/Bootstrap";
import FormMixin from "../src/Bootstrap/FormMixin";

new Vue({
    el:         '#login-form',
    components: {InputComponent, CheckboxComponent},
    mixins:     [FormMixin],
});
