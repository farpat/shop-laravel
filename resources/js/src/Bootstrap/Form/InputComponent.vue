<template>
    <div class="form-group">
        <label :for="getId" v-if="label" class="col-form-label" v-html="label"></label>
        <required-component :label="label" :required="isRequired"></required-component>

        <div :class="getContainerClass">
            <div v-if="before" class="input-group-prepend"><span class="input-group-text" v-html="before"></span>
            </div>

            <input :type="type" :readonly="readonly" :autofocus="autofocus" :id="getId" :class="getInputClass"
                   :name="name" :value="getValue" :placeholder="placeholder" :required="isRequired"
                   @change="onChange($event.target.value)">

            <div v-if="after" class="input-group-append"><span class="input-group-text" v-html="after"></span></div>

            <error-component :error="getError"></error-component>
        </div>
    </div>
</template>


<script>
    import FormElementMixin from "./includes/FormElementMixin";
    import RequiredComponent from "./includes/RequiredComponent";
    import ErrorComponent from "./includes/ErrorComponent";

    export default {
        components: {RequiredComponent, ErrorComponent},
        mixins: [FormElementMixin],
        props: {
            before: {type: String, default: ''},
            after: {type: String, default: ''},
            type: {type: String, default: 'text'},
            readonly: {type: Boolean, default: false},
            plain: {type: String, default: ''},
            placeholder: {type: String, default: ''},
            autofocus: {type: Boolean, default: false},
        },
        computed: {
            getInputClass: function () {
                if (this.plain) {
                    return 'form-control-plaintext';
                }

                return {
                    'form-control': true,
                    'is-invalid': this.getError
                };
            },
            getContainerClass: function () {
                if (this.after || this.before) {
                    return 'input-group';
                }

                return '';
            }
        }
    }
</script>



