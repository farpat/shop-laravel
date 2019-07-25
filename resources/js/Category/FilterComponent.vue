<template>
    <div class="filter-component">
        <h1>Filters</h1>
        <component
            v-for="filter in state.filters"
            :filter="filter"
            :key="filter.id"
            :is="currentTabComponent(filter)"
        />
    </div>
</template>


<script>
    import categoryStore from './categoryStore'
    import NumberComponent from "./filters/NumberComponent.vue";
    import StringComponent from "./filters/StringComponent.vue";

    export default {
        data: function () {
            return {
                state: categoryStore.state
            }
        },
        methods: {
            'currentTabComponent': function (filter) {
                switch (filter.type) {
                    case 'number':
                        return NumberComponent;
                    case 'string':
                        return StringComponent;
                }

                throw new Error(`The filter type << ${type} >> is not found`);
            }
        }
    }
</script>
