class CategoryStore {
    constructor() {
        this.state = {
            ...window.CategoryStore.state,
            currentProducts: [],
        };

        this.data = {
            ...window.CategoryStore.data,
            currentQueryString: ''
        };

        this._refreshProducts();
    }

    setCurrentPage(currentPage) {
        this.state.currentPage = currentPage;
        this._refreshUrl();
    }

    _addQueryString(key, value) {
        let prefix = this.data.currentQueryString.length === 0 ? '?' : '&';
        this.data.currentQueryString += prefix + key + '=' + value;
    }

    _refreshUrl() {
        this.data.currentQueryString = '';

        for (const filterId in this.state.filterValues) {
            this._addQueryString('f[' + filterId + ']', this.getFilterValue(filterId));
        }

        if (this.state.currentPage > 1) {
            this._addQueryString('page', this.state.currentPage);
        }

        window.history.replaceState({}, '', this.data.baseUrl + this.data.currentQueryString);
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

        this._refreshUrl();
        this._refreshProducts();
    }

    _refreshProducts() {
        this.state.currentProducts = this.data.allProducts.filter((product) => this._filterProduct(product));

        if (this.getLastPage() === 0) {
            //when not current products found
            this.setCurrentPage(1);
        } else if (this.getLastPage() < this.state.currentPage) {
            //when the last current page is too big with new filters
            this.setCurrentPage(this.getLastPage());
        }
    }

    _filterProduct(product) {
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
