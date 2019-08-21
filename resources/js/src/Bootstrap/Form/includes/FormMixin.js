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
        checkField: function (field, value) {
            if (this.rules && this.rules[field]) {
                let error = Security.getError(this.rules[field], field, value);
                this.$set(this.errors, field, error)
            }
        },
        changeField: function (field, value) {
            this.$set(this.datas, field, value);

            this.checkField(field, value);

            let confirmationField = field + '_confirmation';
            if (this.rules.hasOwnProperty(confirmationField)) {
                this.checkField(confirmationField, this.datas[confirmationField]);
            }
        },
        checkForm: function () {
            for (let field in this.rules) {
                this.checkField(field, this.datas[field]);
            }
        },
        hasErrors: function () {
            for (let field in this.errors) {
                if (this.errors.hasOwnProperty(field)) {
                    return true;
                }
            }

            return false;
        }
    }
};
