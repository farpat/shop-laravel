import FormStore from "../FormStore";

export default {
    props:    {
        id:             {type: String, required: false},
        name:           {type: String, required: true},
        label:          {type: String, default: ''},
        dataAttributes: {
            type: Object, default: function () {
                return {};
            }
        },
    },
    computed: {
        getDataAttributes: function () {
            let dataAttributes = {};
            if (JSON.stringify(this.dataAttributes) !== '{}') {
                for (let key in this.dataAttributes) {
                    const realKey = key.startsWith('data-') ? key : 'data-' + key;
                    dataAttributes[realKey] = this.dataAttributes[key];
                }
            }

            return dataAttributes;
        },
        getName:           function () {
            return this.name + (this.multiple ? '[]' : '');
        },
        getId:             function () {
            return this.id || this.name;
        },
        isRequired:        function () {
            if (FormStore.state.rules === {} || !FormStore.state.rules[this.name]) {
                return false;
            }

            return FormStore.state.rules[this.name].find(rule => rule.name === 'required') !== undefined;
        },
        getValue:          function () {
            return FormStore.getData(this.name);
        },
        getError:          function () {
            return FormStore.getError(this.name);
        },
    },

    methods: {
        change: function (value) {
            FormStore.changeField(this.name, value);
        },
    }
};
