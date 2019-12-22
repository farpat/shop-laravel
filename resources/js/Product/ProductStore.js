import Arr from "../src/Array/Arr";

/**
 * @property {Object}  state
 * @property {Number}  state.currentProductReference
 * @property {Object}  data
 * @property {String}  data.baseUrl
 * @property {Object}  data.productFields
 * @property {Array}  data.productReferences
 */
class ProductStore {
    constructor() {
        this.state = {
            ...window._ProductStore.state,
            currentProductReference: {}
        };

        this.data = {
            ...window._ProductStore.data,
        };

        this.setCurrentReference(this.guessCurrentReferenceWithHash());
    }

    guessCurrentReferenceWithHash() {
        const hash = window.location.hash;

        if (hash === '') {
            return this.data.productReferences[0];
        }

        const productReferenceId = Number.parseInt(hash.substring(1));
        return this.data.productReferences.find(productReference => productReference.id === productReferenceId) || this.data.productReferences[0];
    }

    setCurrentReference(reference) {
        this.state.currentProductReference = reference;
        window.history.replaceState({}, '', this.data.baseUrl + '#' + reference.id);
    }
}

export default new ProductStore();
