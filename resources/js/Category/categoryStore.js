class CategoryStore {
    constructor() {
        this.state = window.categoryStore;
    }

    setCurrentPage(currentPage) {
        this.state = Object.assign({}, this.state, {currentPage});
        window.history.replaceState({}, '', '?page=' + currentPage);
    }
}

export default new CategoryStore();
