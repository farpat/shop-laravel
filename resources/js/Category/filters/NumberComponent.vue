<template>
    <div class="form-group row">
        <div :class="'col-' + size">
            {{ filter.label }}
        </div>
        <div class="col">
            <div class="row">
                <div class="col">
                    <input type="number" class="form-control" :placeholder="filter.label + ' (min)'"
                           :value="getMinFilterValue"
                           @input="(e) => setMinFilterValue(filter.id, e.currentTarget.value)">
                </div>
                <div class="col">
                    <input type="number" class="form-control" :placeholder="filter.label + ' (max)'"
                           :value="getMaxFilterValue"
                           @input="(e) => setMaxFilterValue(filter.id, e.currentTarget.value)">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import FilterMixin from "./FilterMixin";

    export default {
        mixins: [FilterMixin],
        computed: {
            getMinFilterValue: function () {
                return this.getFilterValue(this.filter.id + '-min');
            },
            getMaxFilterValue: function () {
                return this.getFilterValue(this.filter.id + '-max');
            },
        },
        methods: {
            setMinFilterValue: function (filterId, value) {
                this.setFilterValue(filterId + '-min', value);
            },
            setMaxFilterValue: function (filterId, value) {
                this.setFilterValue(filterId + '-max', value);
            }
        }
    }
</script>
