<template>
    <div class="form-group">
        <p class="mb-1">{{ filter.label }}</p>
        <input :placeholder="filter.label" :value="getFilterValue(filter.id)"
               @input="(e) => setFilterValue(filter.id, e.currentTarget.value)" class="form-control"
               type="text">
    </div>
</template>

<script>
    import FilterMixin from "./FilterMixin";

    export default {
        mixins: [FilterMixin]
    }
</script>
