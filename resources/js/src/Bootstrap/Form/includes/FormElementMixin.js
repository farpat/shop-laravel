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
            if (this.$parent.rules === {} || !this.$parent.rules[this.name]) {
                return false;
            }

            return this.$parent.rules[this.name].find(rule => rule.name === 'required') !== undefined;
        },
        getValue: function () {
            return this.$parent.datas[this.name];
        },
        getError: function () {
            return this.$parent.errors[this.name] || '';
        },
    },

    methods: {
        change: function (value) {
            this.$parent.changeField(this.name, value);
        },
    }
};
