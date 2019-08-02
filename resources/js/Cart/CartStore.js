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
        if (this.state.cartItems[referenceId] === undefined) {
            this.state.cartItemsLength++;
        }

        const reference = this.data.allProductReferences[referenceId];

        const cartItem = {
            ...reference,
            quantity,
            amount_including_taxes: quantity * reference.unit_price_including_taxes,
            amount_excluding_taxes: quantity * reference.unit_price_excluding_taxes,
        };

        Vue.set(this.state.cartItems, referenceId, cartItem);
    }

    getItem(referenceId) {
        return this.state.cartItems[referenceId];
    }

    deleteItem(referenceId) {
        Vue.delete(this.state.cartItems, referenceId);
        this.state.cartItemsLength--;
    }
}

export default new CartStore();
