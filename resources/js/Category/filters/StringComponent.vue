<template>
    <div class="form-group row">
        <div class="col-3">
            {{ filter.label }}
        </div>
        <div class="col">
            <input type="text" class="form-control" :placeholder="filter.label" :value="getFilterValue"
                   @input="(e) => setFilterValue(filter.id, e.currentTarget.value)">
        </div>
    </div>
</template>

<script>
    import categoryStore from "../categoryStore";

    export default {
        props: {
            'filter': {
                'type': Object,
                'required': true,
            }
        },
        data: function () {
            return {
                state: categoryStore.state
            };
        },
        computed: {
            'getFilterValue': function () {
                return categoryStore.getFilterValue(this.filter.id);
            },
        },
        methods: {
            'setFilterValue': function (filterId, value) {
                categoryStore.setFilterValue(filterId, value);
            }
        }
    }
</script>
