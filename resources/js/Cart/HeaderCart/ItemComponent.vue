<template>
    <section class="container">
        <div class="row align-items-center mb-4 mb-md-2">
            <div class="col-3 col-md-2">
                <input :value="item.quantity" class="form-control" type="text">
            </div>
            <div class="col-9 col-md-6">
                {{ item.label }}
            </div>
            <div class="col-9 col-md-3 text-right">
                {{ getAmountIncludingTaxes }}
            </div>
            <div class="">
                <button @click="() => deleteItem()" class="btn btn-sm btn-danger" type="button">
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
    import ProductStore from "../../Product/ProductStore";

    export default {
        mixins: [TranslationMixin, StrMixin],
        props: {
            item: {type: Object, required: true}
        },
        computed: {
            getAmountIncludingTaxes: function () {
                return this.toLocaleCurrency(this.item.amount_including_taxes, ProductStore.data.currency);
            }
        },
        methods: {
            deleteItem: function () {
                CartStore.deleteItem(this.item.id);
            }
        }
    }
</script>
