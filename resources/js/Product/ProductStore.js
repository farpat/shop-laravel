import CartStore from "../Cart/CartStore";

class ProductStore {
    constructor() {
        this.state = {
            ...window.ProductStore.state,
            currentProductReference: {}
        };

        this.data = {
            ...window.ProductStore.data,
            filledProductFields: {}
        };

        this.setCurrentReference(this.getFirstReference());
    }

    getFirstReference() {
        const firstKey = Object.keys(this.data.productReferences)[0];
        return this.data.productReferences[firstKey];
    }

    getFilledProductValue(reference) {
        if (this.data.filledProductFields[reference.id] === undefined) {
            this.data.filledProductFields[reference.id] = {};

            for (let productFieldId in this.data.productFields) {
                this.data.filledProductFields[reference.id][productFieldId] = {
                    ...this.data.productFields[productFieldId],
                    value: reference.filled_product_fields[productFieldId]
                };
            }
        }

        return this.data.filledProductFields[reference.id];
    }

    setCurrentReference(reference) {
        this.state.currentProductReference = reference;
    }
}

export default new ProductStore();
