import Vue from "vue";
import Security from "../Security/Security";
import Str from "../String/Str";
import Arr from "../Array/Arr";

class FormStore {
    constructor() {
        let formStore = window._FormStore || {};

        this.state = {
            rules:  formStore.rules || {},
            datas:  formStore.datas || {},
            errors: formStore.errors || {},
        };
    }

    getData(field) {
        if (Str.looksLikeArray(field)) {
            const matches = field.matchAll(/\[?([\w_-]+)\]?/g);
            return Arr.getNestedProperty(this.state.datas, Array.from(matches).map(match => match[1]));
        }

        return this.state.datas[this.name] || '';
    }

    getError(field) {
        if (Str.looksLikeArray(field)) {
            const matches = field.matchAll(/\[?([\w_-]+)\]?/g);
            return Arr.getNestedProperty(this.state.errors, Array.from(matches).map(match => match[1])) || '';
        }

        return this.state.errors[this.name] || '';
    }


    changeField(field, value) {
        if (Str.looksLikeArray(field)) {
            const arr = Arr.createNestedObject(field, value);

            field = Arr.firstKey(arr);
            value = {...this.state.datas[field], ...arr[field]};
        }

        Vue.set(this.state.datas, field, value);

        this.checkField(field, value);

        let confirmationField = field + '_confirmation';
        if (this.state.rules.hasOwnProperty(confirmationField)) {
            this.checkField(confirmationField, this.state.datas[confirmationField]);
        }
    }

    deleteField(field) {
        Vue.delete(this.state.datas, field);
        this.checkField(field);
    }

    checkField(field, value) {
        if (this.state.rules[field] !== undefined) {
            const fieldRules = this.state.rules[field];
            const error = Security.getError(fieldRules, field, value);
            Vue.set(this.state.errors, field, error);
        }

        return this.hasError(field);
    }

    hasError(field) {
        return (this.state.errors.hasOwnProperty(field) && this.state.errors[field] !== undefined);
    }

    hasErrors() {
        for (const field in this.state.errors) {
            if (this.hasError(field)) {
                return true;
            }
        }

        return false;
    }

    checkForm(fields) {
        if (fields) {
            this.state.errors = {};

            fields.forEach(field => {
                this.checkField(field, this.state.datas[field]);
            });
        } else {
            for (const field in this.state.rules) {
                this.checkField(field, this.state.datas[field]);
            }
        }
    }

    setRules(rules) {
        this.state.rules = rules;
    }
}

export default new FormStore();
