import Vue from "vue";
import Security from "../Security/Security";
import Str from "../String/Str";
import Arr from "../Array/Arr";

class Store {
    constructor() {
        const store = window._Store || {};

        this.state = {
            datas:  store.datas || {},
            errors: store.errors || {},
        };

        this.rules = {};
    }

    get(stateKey, field) {
        if (field === undefined) {
            return this.state[stateKey];
        }

        const keys = Str.parseKeysInString(field);

        return Array.isArray(keys) ?
            Arr.getNestedProperty(this.state[stateKey], keys) :
            this.state[stateKey][field];
    }

    getData(field) {
        return this.get('datas', field);
    }

    getError(field) {
        return this.get('errors', field);
    }

    set(object, key, value) {
        Vue.set(object, key, value);
    }

    _privateSet(stateKey, field, value) {
        const keys = Str.parseKeysInString(field);

        if (Array.isArray(keys)) {
            const parsedValue = Arr.returnNestedObject(this.state[stateKey], field, value);
            Vue.set(this.state[stateKey], keys[0], {...parsedValue[keys[0]]}); //to force a new reference for object
        } else {
            Vue.set(this.state[stateKey], field, value);
        }
    }

    setData(field, value) {
        this._privateSet('datas', field, value);
    }

    setError(field, value) {
        this._privateSet('errors', field, value);
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
}

export default new Store();
