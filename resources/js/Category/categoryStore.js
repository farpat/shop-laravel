class CategoryStore {
    constructor() {
        this.state = window.categoryStore.state;
        this.data = window.categoryStore.data;
        this.currentQueryString = '';

        this.refreshProducts();
    }

    setCurrentPage(currentPage) {
        this.state.currentPage = currentPage;
        this.refreshUrl();
    }

    addQueryString(key, value) {
        let prefix = this.currentQueryString.length === 0 ? '?' : '&';
        this.currentQueryString += prefix + key + '=' + value;
    }

    refreshUrl() {
        this.currentQueryString = '';

        for (const filterId in this.state.filterValues) {
            this.addQueryString('f[' + filterId + ']', this.getFilterValue(filterId));
        }

        if (this.state.currentPage > 1) {
            this.addQueryString('page', this.state.currentPage);
        }

        window.history.replaceState({}, '', this.data.baseUrl + this.currentQueryString);
    }

    getFilterValue(filterId) {
        return this.state.filterValues[filterId] || '';
    }

    setFilterValue(filterId, value) {
        if (value !== '') {
            this.state.filterValues[filterId] = value;
        } else {
            delete this.state.filterValues[filterId];
        }

        this.refreshUrl();
        this.refreshProducts();
    }

    refreshProducts() {
        this.state.currentProducts = this.data.allProducts.filter((product) => this.filterProduct(product));

        if (this.getLastPage() < this.state.currentPage) {
            this.setCurrentPage(this.getLastPage());
        }
    }

    filterProduct(product) {
        if (JSON.stringify(this.state.filterValues) === '{}') {
            return true;
        }

        for (const filterId in this.state.filterValues) {
            let value = this.state.filterValues[filterId];

            if (filterId.endsWith('-max')) {
                let realFilterId = filterId.substring(0, filterId.length - 4);
                if (product.references.find((reference) => reference.filled_product_fields[realFilterId] <= value)) {
                    return true;
                }
            } else if (filterId.endsWith('-min')) {
                let realFilterId = filterId.substring(0, filterId.length - 4);
                if (product.references.find((reference) => reference.filled_product_fields[realFilterId] >= value)) {
                    return true;
                }
            } else {
                if (product.references.find((reference) => reference.filled_product_fields[filterId].includes(value))) {
                    return true;
                }
            }
        }

        return false;
    }

    getLastPage() {
        return Math.ceil(this.state.currentProducts.length / this.state.perPage);
    }
}

export default new CategoryStore();
