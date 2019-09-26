<template>
    <div class="filter-component">
        <component
            v-for="filter in filters"
            :filter="filter"
            :key="filter.id"
            :is="currentTabComponent(filter)"
        />
    </div>
</template>


<script>
    import NumberComponent from "./filters/NumberComponent.vue";
    import StringComponent from "./filters/StringComponent.vue";
    import TranslationMixin from "../src/Translation/TranslationMixin";

    export default {
        mixins: [TranslationMixin],
        props: {
            filters: {type: Object, required: true}
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
