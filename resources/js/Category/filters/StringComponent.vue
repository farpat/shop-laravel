<template>
    <div class="form-group row">
        <div :class="'col-' + size">
            {{ filter.label }}
        </div>
        <div class="col">
            <input type="text" class="form-control" :placeholder="filter.label" :value="getFilterValue(filter.id)"
                   @input="(e) => setFilterValue(filter.id, e.currentTarget.value)">
        </div>
    </div>
</template>

<script>
    import FilterMixin from "./FilterMixin";

    export default {
        mixins: [FilterMixin]
    }
</script>
