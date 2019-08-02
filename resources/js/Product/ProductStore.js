import CartStore from "../Cart/CartStore";

class ProductStore {
    constructor() {
        this.state = {
            ...window.ProductStore.state,
            currentReference: {}
        };

        this.data = {
            ...window.ProductStore.data,
            filledProductFields: {}
        };

        this.setCurrentReference(this.getFirstReference());
    }

    getFirstReference() {
        const firstKey = this.data.productReferenceIds[0];
        return CartStore.data.allProductReferences[firstKey];
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
        this.state.currentReference = reference;
    }
}

export default new ProductStore();
