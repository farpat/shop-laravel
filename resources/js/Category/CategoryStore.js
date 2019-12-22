import Arr from "../src/Array/Arr";

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

    for (const filterKey in this.state.filterValues) {
        addQueryString.call(this, filterKey, this.getFilterValue(filterKey));
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
    if (Arr.isEmpty(this.state.filterValues)) {
        return true;
    }

    for (const filterKey in this.state.filterValues) {
        let value = this.state.filterValues[filterKey];

        let matches;
        if ((matches = /max-(\d+)$/.exec(filterKey)) !== null) {
            let filterId = matches[1];
            if (product.references.find((reference) => reference.filled_product_fields[filterId] <= value) === undefined) {
                return false;
            }
        } else if ((matches = /min-(\d+)$/.exec(filterKey)) !== null) {
            let filterId = matches[1];
            if (product.references.find((reference) => reference.filled_product_fields[filterId] >= value) === undefined) {
                return false;
            }
        } else if ((matches = /-(\d+)$/.exec(filterKey)) !== null) {
            let filterId = matches[1];
            if (product.references.find((reference) => reference.filled_product_fields[filterId].includes(value)) === undefined) {
                return false;
            }
        }
    }

    return true;
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

        this.refreshProducts();
        this.refreshPages();
    }

    refreshProducts() {
        this.state.currentProducts = this.data.allProducts.filter((product) => filterProduct.call(this, product));
    }

    refreshPages() {
        const lastPage = this.getLastPage();
        if (lastPage === 0) {
            //when not current products found
            this.setCurrentPage(1);
        } else if (lastPage < this.state.currentPage) {
            //when the last current page is too big with new filters
            this.setCurrentPage(lastPage);
        }
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
     * @param {String} filterKey
     * @returns {String}
     */
    getFilterValue(filterKey) {
        return this.state.filterValues[filterKey];
    }

    /**
     *
     * @param {Number} filterKey
     * @param {String} value
     */
    setFilterValue(filterKey, value) {
        if (value !== '') {
            this.state.filterValues[filterKey] = value;
        } else {
            delete this.state.filterValues[filterKey];
        }

        refreshUrl.call(this);
        this.refreshProducts();
        this.refreshPages();
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
