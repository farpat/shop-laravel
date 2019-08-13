<template>
    <section class="container">
        <div class="row align-items-center mb-4 mb-md-2">
            <div class="col-3 col-md-2">
                <input :value="quantity" @change="(e) => this.updateItem(e)" class="form-control" type="number">
            </div>
            <div class="col-9 col-md-6">
                {{ item.product_reference.label }}
            </div>
            <div class="col-9 col-md-3 text-right">
                {{ getAmountIncludingTaxes }}
            </div>
            <div class="">
                <button @click="(e) => deleteItem(e)" class="btn btn-sm btn-danger" type="button">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </section>
</template>


<script>
    import TranslationMixin from "../../src/Translation/TranslationMixin";
    import CartStore from "../CartStore";
    import StrMixin from "../../src/String/StrMixin";

    export default {
        mixins: [TranslationMixin, StrMixin],
        data: function () {
            return {
                quantity: this.item.quantity
            }
        },
        props: {
            item: {type: Object, required: true}
        },
        computed: {
            getAmountIncludingTaxes: function () {
                return this.toLocaleCurrency(this.item.amount_including_taxes, CartStore.data.currency);
            }
        },
        methods: {
            deleteItem: function (event) {
                CartStore.deleteItem(this.item.product_reference_id);
                event.stopPropagation();
            },
            updateItem: function (event) {
                CartStore.updateItem(this.item.product_reference_id, event.target.value);
                this.quantity = event.target.value;
                event.stopPropagation();
            }
        }
    }
</script>
