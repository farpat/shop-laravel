<template>
    <section>
        <NavigationComponent></NavigationComponent>

        <div class="tab-content">
            <TabCardComponent></TabCardComponent>
            <TabPaypalComponent></TabPaypalComponent>
        </div>
    </section>
</template>

<script>
    import TabCardComponent from "./TabCardComponent";
    import TabPaypalComponent from "./TabPaypalComponent";
    import NavigationComponent from "./NavigationComponent";
    import TranslationMixin from "../../src/Translation/TranslationMixin";

    export default {
        mixins:     [TranslationMixin],
        components: {NavigationComponent, TabPaypalComponent, TabCardComponent}
    }
</script>