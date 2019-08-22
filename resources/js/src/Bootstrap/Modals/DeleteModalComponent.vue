<template>
    <modal-component :title="title" size="md" :content="deleteForm" type="danger" :close-button-label="closeButtonLabel">
    </modal-component>
</template>


<script>
    import ModalComponent from "./ModalComponent";
    import Requestor from "../../Request/Requestor";

    export default {
        components: {ModalComponent},
        props: {
            action: {type: String, required: true},
            title: {type: String, required: true},
            content: {type: String, default: ''},
            closeButtonLabel: {type: String, default: 'Cancel'}
        },
        computed: {
            deleteForm: function () {
                const token = Requestor.getCsrfToken();

                return `
                <form action="${this.action}" method="POST">
                    <input type="hidden" name="_token" value="${token}">
                    <input type="hidden" name="_method" value="DELETE">
                </form>`;
            }
        },
    }
</script>
