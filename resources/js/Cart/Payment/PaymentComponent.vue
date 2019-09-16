<template>
    <div class="rounded-lg py-2">
        <h2>{{ __('Payment') }}</h2>

        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="p-5">
                    <NavigationComponent></NavigationComponent>

                    <form class="tab-content">
                        <TabCardComponent></TabCardComponent>
                        <TabPaypalComponent></TabPaypalComponent>
                    </form>
                </div>
            </div>
        </div>
    </div>
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