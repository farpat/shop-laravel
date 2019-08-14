import Security from "../../../Security/Security";

export default {
    data: function () {
        window.formStore = window.formStore || {errors: {}, datas: {}};
        window.formStore.errors = window.formStore.errors || {};
        window.formStore.datas = window.formStore.datas || {};

        return {
            datas: window.formStore.datas,
            errors: window.formStore.errors,
            rules: {},
        }
    },
    methods: {
        onChangeElement: function (name, value) {
            this.$set(this.datas, name, value);
            if (this.rules && this.rules[name]) {
                this.$set(this.errors, name, Security.getError(this.rules[name], name, value));
            }
        },
        verifyForm: function () {
            for (let key in this.rules) {
                this.$set(this.errors, key, Security.getError(this.rules[key], key, this.datas[key]));
            }
        },
        hasErrors: function () {
            for (let key in this.errors) {
                if (this.errors.hasOwnProperty(key) && this.errors[key] !== '') {
                    return true;
                }
            }

            return false;
        }
    }
};
