import FormStore from "../FormStore";

export default {
    props: {
        id: {type: String, required: false},
        name: {type: String, required: true},
        label: {type: String, default: ''},
    },
    computed: {
        getName: function () {
            return this.name + (this.multiple ? '[]' : '');
        },
        getId: function () {
            return this.id || this.name;
        },
        isRequired: function () {
            if (FormStore.state.rules === {} || !FormStore.state.rules[this.name]) {
                return false;
            }

            return FormStore.state.rules[this.name].find(rule => rule.name === 'required') !== undefined;
        },
        getValue: function () {
            return FormStore.state.datas[this.name];
        },
        getError: function () {
            return FormStore.state.errors[this.name] || '';
        },
    },

    methods: {
        change: function (value) {
            FormStore.changeField(this.name, value);
        },
    }
};
