<template>
    <div class="products-component">
        <h1>Products</h1>
        <ul>
            <li v-for="product in getCurrentProducts">
                {{ product.label }}
            </li>
        </ul>

        <nav aria-label="Products pagination">
            <ul class="pagination">
                <li :class="{'page-item':true, 'disabled':isFirstPage}">
                    <a class="page-link" href="#" aria-disabled="true" @click="(e) => setPreviousPage(e)">Previous</a>
                </li>
                <li :class="pageItemClass(page)" v-for="page in getPages">
                    <a class="page-link" href="#" @click="(e) => setPage(page, e)">{{ page }}</a>
                </li>
                <li :class="{'page-item':true, 'disabled':isLastPage}">
                    <a class="page-link" href="#" aria-disabled="true" @click="(e) => setNextPage(e)">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</template>


<script>
    import categoryStore from './categoryStore'
    import range from 'lodash/range'

    export default {
        data: function () {
            return {
                state: categoryStore.state
            }
        },
        computed: {
            getCurrentProducts: function () {
                const start = ((this.state.currentPage - 1) * this.state.perPage);
                return this.products.slice(start, start + this.state.perPage);
            },
            getTotalPages: function () {
                return Math.ceil(this.products.length / this.state.perPage);
            },
            getPages: function () {
                return range(1, this.getTotalPages + 1);
            },
            isFirstPage: function () {
                return this.state.currentPage === 1;
            },
            isLastPage: function () {
                return this.state.currentPage === this.getTotalPages
            },
        },
        methods: {
            pageItemClass: function (page) {
                return {'page-item': true, 'active': page === this.state.currentPage};
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
        },
        props: {
            products: {
                type: Array,
                required: true
            }
        }
    }
</script>
