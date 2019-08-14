<template>
    <div class="modal fade" tabindex="-1" role="dialog">
        <div :class="getModalClass" role="document">
            <div class="modal-content">
                <div class="modal-header" :class="getModalHeaderClass">
                    <p v-if="title" :class="getModalTitleClass">{{ title }}</p>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" v-html="content" :class="getModalBodyClass">
                </div>
                <div class="modal-footer" :class="getModalFooterClass">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ closeButtonLabel }}</button>
                    <button type="button" :class="getOkButtonClass" @click="onSubmit">O.K.</button>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    import 'bootstrap/js/dist/modal';

    export default {
        props: {
            type: {type: String, default: ''},
            title: {type: String, default: ''},
            size: {type: String, default: ''},
            content: {type: String, default: ''},
            closeButtonLabel: {type: String, default: 'Close'}
        },
        computed: {
            getModalHeaderClass: function () {
                return {};
            },
            getModalBodyClass: function () {
                return {};
            },
            getModalClass: function () {
                let classes = ['modal-dialog'];

                if (this.size) {
                    classes.push('modal-' + this.size);
                }

                return classes;

            },
            getModalTitleClass: function () {
                let classes = ['modal-title'];

                if (this.type) {
                    classes.push('text-' + this.type);
                }

                return classes;
            },
            getModalFooterClass: function () {
                return '';
            },
            getOkButtonClass: function () {
                let classes = ['btn'];

                if (this.type) {
                    classes.push('btn-' + this.type);
                }

                return classes;
            }
        },
        mounted: function () {
            $(this.$el).modal('show');
            $(this.$el).on('hidden.bs.modal', () => this.$destroy());
            this.$form = this.$el.querySelector('form');
        },
        destroyed: function () {
            this.$el.remove();
        },
        methods: {
            onSubmit: function () {
                if (this.$form) {
                    this.$form.submit();
                }
            }
        }
    }
</script>
