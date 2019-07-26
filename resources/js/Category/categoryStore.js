class CategoryStore {
    constructor() {
        this.state = window.categoryStore;
        this.baseUrl = window.baseUrl;
        this.currentQueryString = '';
    }

    setCurrentPage(currentPage) {
        this.state = Object.assign({}, this.state, {currentPage});
        this.refreshUrl();
    }

    addQueryString(key, value) {
        let prefix = this.currentQueryString.length === 0 ? '?' : '&';
        this.currentQueryString += prefix + key + '=' + value;
    }

    refreshUrl() {
        this.currentQueryString = '';

        for (const filterId in this.state.filterValues) {
            const value = this.getFilterValue(filterId);
            if (value !== '') {
                this.addQueryString('f[' + filterId + ']', value);
            }
        }

        if (this.state.currentPage > 1) {
            this.addQueryString('page', this.state.currentPage);
        }

        window.history.replaceState({}, '', this.baseUrl + this.currentQueryString);
    }

    getFilterValue(filterId) {
        return this.state.filterValues[filterId] || '';
    }

    setFilterValue(filterId, value) {
        this.state.filterValues[filterId] = value;
        this.state = Object.assign({}, this.state, {filterValues: this.state.filterValues});
        this.refreshUrl();
    }
}

export default new CategoryStore();
