<template>
    <div class="form-group row">
        <div class="col-3">
            {{ filter.label }}
        </div>
        <div class="col">
            <input type="text" class="form-control" :placeholder="filter.label" :value="getValue"
                   @input="(e) => setFilterValue(filter.id, e.currentTarget.value)">
        </div>
    </div>
</template>

<script>
    import categoryStore from "../categoryStore";

    export default {
        data: function () {
            return {
                state: categoryStore.state
            };
        },
        computed: {
            'getValue': function () {
                return this.state.filterValues[this.filter.id] || '';
            },
        },
        methods: {
            'setFilterValue': function (filterId, value) {
                this.$set(this.state.filterValues, filterId, value);
            }
        },
        props: {
            'filter': {
                'type': Object,
                'required': true,
            }
        }
    }
</script>
