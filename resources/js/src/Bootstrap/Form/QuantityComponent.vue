<template>
    <div class="form-group">
        <label :for="getId" v-if="label" class="col-form-label" v-html="label"></label>

        <div class="input-group input-group-quantity" :class="getContainerClass">
            <button :class="getMinusButtonClass" :style="getMinusButtonStyle" type="button" @click="onDecrease">
                <i class="fas fa-minus"></i>
            </button>

            <input :name="name" type="hidden" :value="getValue">

            <span :class="getInputClass">{{ getFormattedValue }}</span>

            <button :class="getPlusButtonClass" :style="getPlusButtonStyle" type="button" @click="onIncrease">
                <i class="fas fa-plus"></i>
            </button>
        </div>

        <error-component :error="getError"></error-component>
    </div>
</template>

<script>
    import ErrorComponent from "./includes/ErrorComponent";
    import StringU from "../../../Utilities/String/StringU";
    import FormElementMixin from "./includes/FormElementMixin";

    export default {
        components: {ErrorComponent},
        mixins: [FormElementMixin],
        props: {
            direction: {type: String, default: 'horizontal'},
            step: {type: Number, default: 1},
            min: {type: Number, default: 0},
            max: {type: Number, default: -1},
        },
        computed: {
            getQuantity: function () {
                return this.getValue || 0;
            },
            getContainerClass: function () {
                return 'input-group-quantity-' + this.direction;
            },
            getMinusButtonStyle: function () {
                return {
                    'cursor': this.canDecrease ? 'pointer' : 'initial'
                };
            },
            getPlusButtonStyle: function () {
                return {
                    'cursor': this.canIncrease ? 'pointer' : 'initial'
                };
            },
            getMinusButtonClass: function () {
                return {
                    'text-muted': !this.canDecrease
                };
            },
            getPlusButtonClass: function () {
                return {
                    'text-muted': !this.canIncrease
                };
            },
            getInputClass: function () {
                return this.getError ? ' is-invalid' : '';
            },
            canIncrease: function () {
                if (this.max === -1) return true;

                return (this.getQuantity + this.step <= this.max);
            },
            canDecrease: function () {
                return (this.getQuantity - this.step >= this.min);
            },
            getFormattedValue: function () {
                return StringU.toLocaleString(this.getQuantity);
            },
        },
        methods: {
            onIncrease: function () {
                if (this.canIncrease) {
                    this.onChange(this.getQuantity + this.step);
                }
            },

            onDecrease: function () {
                if (this.canDecrease) {
                    this.onChange(this.getQuantity - this.step);
                }
            },
        }
    }
</script>
