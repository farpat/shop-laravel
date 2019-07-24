<template>
    <div class="filter-component">
        <h1>Filters</h1>
        <ul>
            <li v-for="filter in state.filters">
                Render <strong>{{ filter.type }}</strong> with label <em>{{ filter.label }}</em>
            </li>
        </ul>
    </div>
</template>


<script>
    import categoryStore from './categoryStore'

    export default {
        data: function () {
            return {
                state: categoryStore.state
            }
        }
    }
</script>
