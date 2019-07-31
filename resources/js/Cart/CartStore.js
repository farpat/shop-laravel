import Vue from 'vue';

class CartStore {
    constructor() {
        this.state = {
            ...window.CartStore.state
        };

        this.data = {
            ...window.CartStore.data
        };
    }

    setItem(referenceId, quantity) {
        const cartItem = {...this.data.allReferences[referenceId], quantity};
        Vue.set(this.state.cart, referenceId, cartItem);
    }

    getItem(referenceId) {
        return this.state.cart[referenceId];
    }

    deleteItem(referenceId) {
        delete this.state.cart[referenceId];
    }
}

export default new CartStore();
