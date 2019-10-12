import Vue from "vue";
import Security from "../Security/Security";
import Str from "../String/Str";
import Arr from "../Array/Arr";

class Store {
    static rulesCache = {};

    constructor() {
        let store = window._Store || {};

        this.state = {
            datas:  store.datas || {},
            errors: store.errors || {},
        };

        this.rules = {};
    }

    get(stateKey, field) {
        let keys = Str.parseKeysInString(field);

        return Array.isArray(keys) ?
            Arr.getNestedProperty(this.state[stateKey], keys) :
            this.state[stateKey][field];
    }

    getData(field) {
        if (field === undefined) {
            return this.state.datas;
        }

        return this.get('datas', field);
    }

    getError(field) {
        if (field === undefined) {
            return this.state.errors;
        }

        return this.get('errors', field) || '';
    }

    set(object, key, value) {
        Vue.set(object, key, value);
    }

    setData(field, value) {
        let keys = Str.parseKeysInString(field);

        if (Array.isArray(keys)) {
            const parsedValue = Arr.setNestedObject(this.state.datas, field, value);
            Vue.set(this.state.datas, keys[0], {...parsedValue[keys[0]]});
        } else {
            Vue.set(this.state.datas, field, value);
        }
    }

    setError(field, value) {
        let keys = Str.parseKeysInString(field);

        if (Array.isArray(keys)) {
            const parsedValue = Arr.setNestedObject(this.state.errors, field, value);
            Vue.set(this.state.errors, keys[0], {...parsedValue[keys[0]]});
        } else {
            Vue.set(this.state.errors, field, value);
        }
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
        if (fields === undefined) {
            fields = this.state.errors;
        }

        const keys = Object.keys(fields);

        if (keys.length === 0) {
            console.trace('toto');
            return false;
        }

        for (let i = 0; i < keys.length; i++) {
            const key = keys[i];

            if (typeof fields[key] === 'object') {
                return this.hasErrors(fields[key]);
            } else {
                if (typeof fields[key] === 'string') {
                    console.log('erreur donc true', fields[key]);
                    return true;
                }
            }
        }

        return false;
    }

    setRules(field, rules) {
        this.rules[field] = rules;
    }
}

export default new Store();
