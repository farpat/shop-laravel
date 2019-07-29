class ProductStore {
    constructor() {
        this.state = window.productStore.state;
        this.data = window.productStore.data;
        this.currentQueryString = '';

        if (this.state.currentReference) {
            this.setCurrentProductReference(this.data.allReferences[0]);
        }
    }


    setCurrentProductReference(reference) {
        this.state.currentReference = reference;
    }
}

export default new ProductStore();
