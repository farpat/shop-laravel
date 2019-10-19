import Vue from "vue";
import {CheckboxComponent, InputComponent, NumberComponent} from "../src/Bootstrap";
import FormMixin from "../src/Bootstrap/FormMixin";

new Vue({
    el:         '#password-form',
    components: {InputComponent, CheckboxComponent, NumberComponent},
    mixins:     [FormMixin],
});
