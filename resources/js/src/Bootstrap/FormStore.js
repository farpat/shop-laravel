import Vue from "vue";
import Security from "../Security/Security";

class FormStore {
    constructor() {
        let formStore = window._FormStore || {};

        this.state = {
            rules:  formStore.rules || {},
            datas:  formStore.datas || {},
            errors: formStore.errors || {},
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
        if (this.state.rules[field] !== undefined) {
            const fieldRules = this.state.rules[field];
            const error = Security.getError(fieldRules, field, value);
            Vue.set(this.state.errors, field, error);
        }
    }

    hasErrors() {
        for (const field in this.state.errors) {
            if (this.state.errors.hasOwnProperty(field) && this.state.errors[field] !== undefined) {
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

    setRules(rules) {
        this.state.rules = rules;
    }
}

export default new FormStore();