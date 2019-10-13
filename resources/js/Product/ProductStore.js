import Arr from "../src/Array/Arr";

class ProductStore {
    constructor() {
        this.state = {
            ...window._ProductStore.state,
            currentProductReference: {}
        };

        this.data = {
            ...window._ProductStore.data,
            filledProductFields: {}
        };

        this.setCurrentReference(Arr.first(this.data.productReferences));
    }

    setCurrentReference(reference) {
        this.state.currentProductReference = reference;
    }
}

export default new ProductStore();
