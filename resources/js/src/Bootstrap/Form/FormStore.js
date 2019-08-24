import Vue from "vue";
import Security from "../../Security/Security";

class FormStore {
    constructor() {
        this.state = {
            rules:  window.FormStore.rules,
            datas:  window.FormStore.datas,
            errors: window.FormStore.errors,
        };
    }

    changeField(field, value) {
        Vue.set(this.state.datas, field, value);

        this.checkField(field, value);

        let confirmationField = field + '_confirmation';
        if (this.state.rules.hasOwnProperty(confirmationField)) {
            this.checkField(confirmationField, this.state.datas[confirmationField]);
        }
    }

    checkField(field, value) {
        if (this.state.rules && this.state.rules[field]) {
            const fieldRules = this.state.rules[field];
            const error = Security.getError(fieldRules, field, value);
            Vue.set(this.state.errors, field, error);
        }
    }

    hasErrors() {
        for (const field in this.state.errors) {
            if (this.state.errors.hasOwnProperty(field)) {
                return true;
            }
        }

        return false;
    }

    checkForm() {
        for (const field in this.state.rules) {
            this.checkField(field, this.state.datas[field]);
        }
    }
}

export default new FormStore();
