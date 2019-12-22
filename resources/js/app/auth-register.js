import Vue from "vue";
import {InputComponent, CheckboxComponent, NumberComponent, ModalComponent} from "../src/Bootstrap";
import FormMixin from "../src/Bootstrap/FormMixin";
import Translation from "../src/Translation/Translation";

new Vue({
    el:         '#register-form',
    components: {InputComponent, CheckboxComponent, NumberComponent},
    mixins: [FormMixin],
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
