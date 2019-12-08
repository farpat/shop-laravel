const refreshProducts = function () {
    this.state.currentProducts = this.data.allProducts.filter((product) => filterProduct.call(this, product));

    const lastPage = this.getLastPage();
    if (lastPage === 0) {
        //when not current products found
        this.setCurrentPage(1);
    } else if (lastPage < this.state.currentPage) {
        //when the last current page is too big with new filters
        this.setCurrentPage(lastPage);
    }
};

/**
 *
 * @param {String} key
 * @param {String|Number} value
 */
const addQueryString = function (key, value) {
    const prefix = this.data.currentQueryString.length === 0 ? '?' : '&';
    this.data.currentQueryString += `${prefix + key}=${value}`;
};

const refreshUrl = function () {
    this.data.currentQueryString = '';

    for (const filterId in this.state.filterValues) {
        addQueryString.call(this, `f[${filterId}]`, this.getFilterValue(filterId));
    }

    if (this.state.currentPage > 1) {
        addQueryString.call(this, 'page', this.state.currentPage);
    }

    window.history.replaceState({}, '', this.data.baseUrl + this.data.currentQueryString);
};

/**
 *
 * @param {Object} product
 * @returns {boolean}
 */
const filterProduct = function (product) {
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
};


/**
 * @property {Object} state
 * @property {Array} state.currentProducts
 * @property {Object} state.filterValues
 * @property {Number} state.perPage
 * @property {Number} state.currentPage
 * @property {Object} data
 * @property {Array} data.allProducts
 * @property {String} data.baseUrl
 * @property {String} data.currentQueryString
 */
class CategoryStore {
    constructor() {
        this.state = {
            ...window._CategoryStore.state,
            currentProducts: [],
        };

        this.data = {
            ...window._CategoryStore.data,
            currentQueryString: ''
        };

        refreshProducts.call(this);
    }

    /**
     *
     * @param {Number} currentPage
     */
    setCurrentPage(currentPage) {
        this.state.currentPage = currentPage;
        refreshUrl.call(this);
    }

    /**
     *
     * @param {Number} filterId
     * @returns {String}
     */
    getFilterValue(filterId) {
        return this.state.filterValues[filterId];
    }

    /**
     *
     * @param {Number} filterId
     * @param {String} value
     */
    setFilterValue(filterId, value) {
        if (value !== '') {
            this.state.filterValues[filterId] = value;
        } else {
            delete this.state.filterValues[filterId];
        }

        refreshUrl.call(this);
        refreshProducts.call(this);
    }

    /**
     *
     * @returns {Number}
     */
    getLastPage() {
        return Math.ceil(this.state.currentProducts.length / this.state.perPage);
    }
}

export default new CategoryStore();
