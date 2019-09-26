<template>
    <div class="form-group">
        <p class="mb-1">{{ filter.label }}</p>
        <div class="row">
            <div class="col">
                <input :value="getMinFilterValue" @input="(e) => setMinFilterValue(filter.id, e.currentTarget.value)"
                       class="form-control"
                       placeholder="min"
                       type="number">
            </div>
            <div class="col">
                <input :value="getMaxFilterValue" @input="(e) => setMaxFilterValue(filter.id, e.currentTarget.value)"
                       class="form-control"
                       placeholder="max"
                       type="number">
            </div>
        </div>
    </div>
</template>

<script>
    import FilterMixin from "./FilterMixin";

    export default {
        mixins:   [FilterMixin],
        computed: {
            getMinFilterValue: function () {
                return this.getFilterValue(this.filter.id + '-min');
            },
            getMaxFilterValue: function () {
                return this.getFilterValue(this.filter.id + '-max');
            },
        },
        methods:  {
            setMinFilterValue: function (filterId, value) {
                this.setFilterValue(filterId + '-min', value);
            },
            setMaxFilterValue: function (filterId, value) {
                this.setFilterValue(filterId + '-max', value);
            }
        }
    }
</script>
