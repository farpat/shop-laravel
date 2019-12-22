<template>
    <div class="form-group">
        <p class="mb-1">{{ filter.label }}</p>
        <div class="row no-gutters">
            <div class="col">
                <input :value="getMinFilterValue" @input="(e) => setMinFilterValue(e.currentTarget.value)"
                       class="form-control"
                       placeholder="min"
                       type="number">
            </div>
            <div class="col">
                <input :value="getMaxFilterValue" @input="(e) => setMaxFilterValue(e.currentTarget.value)"
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
                return this.getFilterValue(`${this.filter.snake_label}-min-${this.filter.id}`);
            },
            getMaxFilterValue: function () {
                return this.getFilterValue(`${this.filter.snake_label}-max-${this.filter.id}`);
            },
        },
        methods:  {
            setMinFilterValue: function (value) {
                this.setFilterValue(`${this.filter.snake_label}-min-${this.filter.id}`, value);
            },
            setMaxFilterValue: function (value) {
                this.setFilterValue(`${this.filter.snake_label}-max-${this.filter.id}`, value);
            }
        }
    }
</script>
