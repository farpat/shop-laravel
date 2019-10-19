import Vue from "vue";
import Security from "../Security/Security";
import Str from "../String/Str";
import Arr from "../Array/Arr";

/**
 *
 * @param {String} stateKey
 * @param {String} field
 * @param {String|Number} value
 */
const set = function (stateKey, field, value) {
    const keys = Str.parseKeysInString(field);

    if (Array.isArray(keys)) {
        const parsedValue = Arr.returnNestedObject(this.state[stateKey], field, value);
        Vue.set(this.state[stateKey], keys[0], {...parsedValue[keys[0]]}); //to force a new reference for object
    } else {
        Vue.set(this.state[stateKey], field, value);
    }
};
/**
 *
 * @param {String} stateKey
 * @param {String} field
 * @returns {*}
 */
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

    /**
     *
     * @param {String} field
     * @returns {*}
     */
    getData(field) {
        return get.call(this, 'datas', field);
    }

    /**
     *
     * @param {String} field
     * @returns {*}
     */
    getError(field) {
        return get.call(this, 'errors', field);
    }

    /**
     *
     * @param {Object} object
     * @param {String|Number} key
     * @param {*} value
     */
    set(object, key, value) {
        Vue.set(object, key, value);
    }

    /**
     *
     * @param {String} field
     * @param {*} value
     */
    setData(field, value) {
        set.call(this, 'datas', field, value);
    }

    /**
     *
     * @param {String} field
     * @param {String} value
     */
    setError(field, value) {
        set.call(this, 'errors', field, value);
    }

    /**
     *
     * @param {String} field
     */
    deleteData(field) {
        const keys = Str.parseKeysInString(field);
        if (Array.isArray(keys)) {
            Vue.delete(Arr.getNestedProperty(this.state.datas, keys.slice(0, -1)), keys[keys.length - 1]);
        } else {
            Vue.delete(this.state.datas, field);
        }
    }

    /**
     *
     * @param {String} field
     * @param {String|Number|Array} value
     * @param {Array} rules
     * @returns {string}
     */
    checkData(field, value, rules) {
        const confirmationField = field.slice(-1) === ']' ?
            `${field.slice(0, field.length - 1)}_confirmation]` :
            `${field}_confirmation`;

        const rulesConfirmation = this.getRules(confirmationField);
        if (rulesConfirmation !== undefined) {
            this.checkData(confirmationField, this.getData(confirmationField), rulesConfirmation);
        }

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

    checkStore(ruleKeys) {
        if (ruleKeys === undefined) {
            ruleKeys = this.getRuleKeys();
        }

        ruleKeys.forEach(field => {
            const rules = this.getRules(field);
            if (rules.length > 0) {
                const splitedField = field.split('.');

                if (splitedField.length === 1) {
                    this.checkData(field, this.getData(field), rules);
                } else {
                    const object = this.getData(splitedField[0]);

                    if (Arr.isAssociative(object)) {
                        for (let key in object) {
                            if (splitedField[2] !== undefined) {
                                this.checkData(splitedField[2], object[key][splitedField[2]], rules);
                            }
                            else {
                                this.checkData(splitedField[0], object[key], rules);
                            }
                        }
                    } else {
                        console.error('TODO not associative');
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
