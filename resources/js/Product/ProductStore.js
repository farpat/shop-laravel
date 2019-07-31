class ProductStore {
    constructor() {
        this.state = window.productStore.state;

        this.data = {
            ...window.productStore.data,
            filledProductFields: {}
        };

        if (this.state.currentReference) {
            this.setCurrentProductReference(this.data.allReferences[0]);
        }
    }

    getFilledProductValue(reference) {
        if (this.data.filledProductFields[reference.id] === undefined) {
            this.data.filledProductFields[reference.id] = {};

            for (let productFieldId in this.data.productFields) {
                this.data.filledProductFields[reference.id][productFieldId] = Object.assign(
                    {},
                    this.data.productFields[productFieldId],
                    {value: reference.filled_product_fields[productFieldId]}
                );
            }
        }

        return this.data.filledProductFields[reference.id];
    }

    setCurrentProductReference(reference) {
        this.state.currentReference = reference;
    }
}

export default new ProductStore();
