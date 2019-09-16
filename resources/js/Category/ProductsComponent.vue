<template>
    <div class="products-component">
        <nav :aria-label="translate('Products pagination')" class="mt-2" v-show="hasMorePage">
            <ul class="pagination">
                <li :class="firstPageItemClass">
                    <a class="page-link" href="#" aria-disabled="true" @click="(e) => setPreviousPage(e)" v-html="translate('pagination.previous')"></a>
                </li>
                <li :class="pageItemClass(page)" v-for="page in getPages">
                    <a class="page-link" href="#" @click="(e) => setPage(page, e)">{{ page }}</a>
                </li>
                <li :class="lastPageItemClass">
                    <a class="page-link" href="#" aria-disabled="true" @click="(e) => setNextPage(e)" v-html="translate('pagination.next')"></a>
                </li>
            </ul>
        </nav>

        <div class="row" v-if="state.currentProducts.length > 0">
            <ProductComponent v-for="product in getCurrentProducts" :product="product" :key="product.id"/>
        </div>

        <p v-else>
            {{ __('Sorry, no products found!') }}
        </p>
    </div>
</template>


<script>
    import CategoryStore from './CategoryStore';
    import {range} from 'lodash';
    import ProductComponent from "./ProductComponent";
    import TranslationMixin from "../src/Translation/TranslationMixin";

    export default {
        mixins: [TranslationMixin],
        components: {ProductComponent},
        data: function () {
            return {
                state: CategoryStore.state
            }
        },
        computed: {
            getCurrentProducts: function () {
                const start = (this.state.currentPage - 1) * this.state.perPage;
                return this.state.currentProducts.slice(start, start + this.state.perPage);
            },
            getPages: function () {
                return range(1, CategoryStore.getLastPage() + 1);
            },
            hasMorePage: function () {
                return CategoryStore.getLastPage() > 1;
            },
            isFirstPage: function () {
                return this.state.currentPage === 1;
            },
            isLastPage: function () {
                return this.state.currentPage === CategoryStore.getLastPage()
            },
            firstPageItemClass: function () {
                return {'page-item': true, disabled: this.isFirstPage};
            },
            lastPageItemClass: function () {
                return {'page-item': true, disabled: this.isLastPage};
            },
        },
        methods: {
            pageItemClass: function (page) {
                return {'page-item': true, active: page === this.state.currentPage};
            },
            setCurrentPage: function (currentPage) {
                CategoryStore.setCurrentPage(currentPage);
            },
            setPage: function (currentPage, event) {
                event.preventDefault();
                if (currentPage !== this.state.currentPage) {
                    CategoryStore.setCurrentPage(currentPage);
                }
            },
            setPreviousPage: function (event) {
                event.preventDefault();
                if (!this.isFirstPage) {
                    CategoryStore.setCurrentPage(this.state.currentPage - 1);
                }
            },
            setNextPage: function (event) {
                event.preventDefault();
                if (!this.isLastPage) {
                    CategoryStore.setCurrentPage(this.state.currentPage + 1);
                }
            },
        }
    }
</script>
