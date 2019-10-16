import Vue from "vue";
import Security from "../Security/Security";
import Str from "../String/Str";
import Arr from "../Array/Arr";

const set = function (stateKey, field, value) {
    const keys = Str.parseKeysInString(field);

    if (Array.isArray(keys)) {
        const parsedValue = Arr.returnNestedObject(this.state[stateKey], field, value);
        Vue.set(this.state[stateKey], keys[0], {...parsedValue[keys[0]]}); //to force a new reference for object
    } else {
        Vue.set(this.state[stateKey], field, value);
    }
};
const get = function (stateKey, field) {
    if (field === undefined) {
        return this.state[stateKey];
    }

    const keys = Str.parseKeysInString(field);

    return Array.isArray(keys) ?
        Arr.getNestedProperty(this.state[stateKey], keys) :
        this.state[stateKey][field];
};

class Store {
    constructor() {
        const store = window._Store || {};

        this.state = {
            datas:  store.datas || {},
            errors: store.errors || {},
        };

        this.rules = {};
    }

    getData(field) {
        return get.call(this, 'datas', field);
    }

    getError(field) {
        return get.call(this, 'errors', field);
    }

    set(object, key, value) {
        Vue.set(object, key, value);
    }

    setData(field, value) {
        set.call(this, 'datas', field, value);
    }

    setError(field, value) {
        set.call(this, 'errors', field, value);
    }

    deleteData(field) {
        const keys = Str.parseKeysInString(field);
        if (Array.isArray(keys)) {
            Vue.delete(Arr.getNestedProperty(this.state.datas, keys.slice(0, -1)), keys[keys.length - 1]);
        } else {
            Vue.delete(this.state.datas, field);
        }
    }

    checkData(field, value, rules) {
        const error = Security.getError(rules, field, value);

        this.setError(field, error);

        return error;
    }

    hasErrors(fields) {
        fields = fields || this.state.errors;

        const keys = Object.keys(fields);

        for (let i = 0; i < keys.length; i++) {
            const key = keys[i];

            if (!Arr.isEmpty(fields[key])) {
                if (this.hasErrors(fields[key])) {
                    return true;
                }
            } else if (typeof fields[key] === 'string') {
                return true;
            }
        }

        return false;
    }

    checkStore(fields) {
        if (fields === undefined) {
            fields = this.getRuleKeys();
        }

        fields.forEach(field => {
            const rules = this.getRules(field);
            if (rules.length > 0) {
                const splitedField = field.split('.');

                if (splitedField.length === 1) {
                    this.checkData(field, this.getData(field), rules);
                } else {
                    const object = this.getData(splitedField[0]);

                    if (Arr.isAssociative(object)) {
                        for (let key in object) {
                            this.checkData(splitedField[2], object[key][splitedField[2]], rules);
                        }
                    } else {
                        console.log('TODO not associative');
                    }
                }
            }
        });
    }

    setRules(field, rules) {
        this.rules[field] = rules;
    }

    getRules(field) {
        return this.rules[field];
    }

    getRuleKeys() {
        return Object.keys(this.rules);
    }
}

export default new Store();
