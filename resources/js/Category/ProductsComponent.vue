<template>
    <div class="products-component">
        <h1>Products</h1>
        <ul v-if="state.currentProducts.length > 1">
            <ProductComponent v-for="product in getCurrentProducts" :product="product"/>
        </ul>

        <p v-else>
            Sorry no products found!
        </p>

        <nav aria-label="Products pagination" v-show="state.currentProducts.length > 1">
            <ul class="pagination">
                <li :class="firstPageItemClass">
                    <a class="page-link" href="#" aria-disabled="true" @click="(e) => setPreviousPage(e)">Previous</a>
                </li>
                <li :class="pageItemClass(page)" v-for="page in getPages">
                    <a class="page-link" href="#" @click="(e) => setPage(page, e)">{{ page }}</a>
                </li>
                <li :class="lastPageItemClass">
                    <a class="page-link" href="#" aria-disabled="true" @click="(e) => setNextPage(e)">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</template>


<script>
    import categoryStore from './categoryStore';
    import {range} from 'lodash';
    import ProductComponent from "./ProductComponent";

    export default {
        components: {ProductComponent},
        data: function () {
            return {
                state: categoryStore.state
            }
        },
        computed: {
            getCurrentProducts: function () {
                const start = (this.state.currentPage - 1) * this.state.perPage;
                return this.state.currentProducts.slice(start, start + this.state.perPage);
            },
            getPages: function () {
                return range(1, categoryStore.getLastPage() + 1);
            },
            isFirstPage: function () {
                return this.state.currentPage === 1;
            },
            isLastPage: function () {
                return this.state.currentPage === categoryStore.getLastPage()
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
                categoryStore.setCurrentPage(currentPage);
            },
            setPage: function (currentPage, event) {
                event.preventDefault();
                if (currentPage !== this.state.currentPage) {
                    categoryStore.setCurrentPage(currentPage);
                }
            },
            setPreviousPage: function (event) {
                event.preventDefault();
                if (!this.isFirstPage) {
                    categoryStore.setCurrentPage(this.state.currentPage - 1);
                }
            },
            setNextPage: function (event) {
                event.preventDefault();
                if (!this.isLastPage) {
                    categoryStore.setCurrentPage(this.state.currentPage + 1);
                }
            },
        }
    }
</script>
