<template>
    <div class="form-group">
        <div class="custom-control custom-checkbox">
            <input v-if="!getValue" type="hidden" :name="name" value=""/>

            <input type="checkbox" :name="name" :id="getId" :class="getInputClass" :checked="getValue"
                   @change="onChange($event.target.checked)" value="1">

            <label class="custom-control-label" :for="getId" style="cursor: pointer">{{ label }}</label>

            <required-component :label="label" :required="isRequired"></required-component>

            <error-component :error="getError"></error-component>
        </div>
    </div>
</template>

<script>
    import RequiredComponent from "./includes/RequiredComponent";
    import ErrorComponent from "./includes/ErrorComponent";
    import FormElementMixin from "./includes/FormElementMixin";

    export default {
        components: {ErrorComponent, RequiredComponent},
        mixins: [FormElementMixin],
        computed: {
            getInputClass: function () {
                return {
                    'custom-control-input': true,
                    'is-invalid': this.getError
                };
            },
        },
    }
</script>
