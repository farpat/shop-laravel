<template>
    <div class="form-group row">
        <div class="col-3">
            {{ filter.label }}
        </div>
        <div class="col">
            <div class="row">
                <div class="col">
                    <input type="number" class="form-control" placeholder="min" :value="getMinValue"
                           @input="(e) => setMinFilterValue(filter.id, e.currentTarget.value)">
                </div>
                <div class="col">
                    <input type="number" class="form-control" placeholder="max" :value="getMaxValue"
                           @input="(e) => setMaxFilterValue(filter.id, e.currentTarget.value)">
                </div>
            </div>
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
            'getMinValue': function () {
                return categoryStore.getFilterValue(this.filter.id + '-min');
            },
            'getMaxValue': function () {
                return categoryStore.getFilterValue(this.filter.id + '-max');
            },
        },
        methods: {
            'setMinFilterValue': function (filterId, value) {
                categoryStore.setFilterValue(this.filter.id + '-min', value);
            },
            'setMaxFilterValue': function (filterId, value) {
                categoryStore.setFilterValue(this.filter.id + '-max', value);
            }
        }
    }
</script>
