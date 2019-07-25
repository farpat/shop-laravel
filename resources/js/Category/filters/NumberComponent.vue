<template>
    <div class="form-group row">
        <div class="col-3">
            {{ filter.label }}
        </div>
        <div class="col">
            <div class="row">
                <div class="col">
                    <input type="number" class="form-control" placeholder="min" :value="getMinValue" @input="(e) => setMinFilterValue(filter.id, e.currentTarget.value)">
                </div>
                <div class="col">
                    <input type="number" class="form-control" placeholder="max" :value="getMaxValue" @input="(e) => setMaxFilterValue(filter.id, e.currentTarget.value)">
                </div>
            </div>
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
            'getMinValue': function () {
                return this.state.filterValues[this.filter.id + '-min'] || '';
            },
            'getMaxValue': function () {
                return this.state.filterValues[this.filter.id + '-max'] || '';
            },
        },
        methods: {
            'setMinFilterValue': function (filterId, value) {
                this.$set(this.state.filterValues, filterId + '-min', value);
            },
            'setMaxFilterValue': function (filterId, value) {
                this.$set(this.state.filterValues, filterId + '-max', value);
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
