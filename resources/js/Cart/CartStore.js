import Vue from 'vue';
import Requestor from "@farpat/api";

class CartStore {
    constructor() {
        this.state = {
            ...window.CartStore.state,
            isLoading: {},
        };

        this.data = {
            ...window.CartStore.data,
            endPoint: '/cart-items'
        };
    }

    updateItem(productReferenceId, quantity) {
        Vue.set(this.state.isLoading, productReferenceId, true);

        const request = Requestor.newRequest();
        request
            .patch(this.data.endPoint + '/' + productReferenceId, {
                quantity
            })
            .then(cartItem => {
                Vue.set(this.state.cartItems, productReferenceId, cartItem);
                Vue.set(this.state.isLoading, productReferenceId, false);
            });
    }

    deleteItem(productReferenceId) {
        Vue.set(this.state.isLoading, productReferenceId, true);

        const request = Requestor.newRequest();
        request
            .delete(this.data.endPoint + '/' + productReferenceId)
            .then(() => {
                this.state.cartItemsLength--;
                Vue.delete(this.state.cartItems, productReferenceId);
                Vue.set(this.state.isLoading, productReferenceId, false);
            });
    }

    addItem(productReferenceId, quantity) {
        Vue.set(this.state.isLoading, productReferenceId, true);

        const request = Requestor.newRequest();
        request
            .post(this.data.endPoint, {
                product_reference_id: productReferenceId,
                quantity
            })
            .then(cartItem => {
                this.state.cartItemsLength++;
                Vue.set(this.state.cartItems, productReferenceId, cartItem);
                Vue.set(this.state.isLoading, productReferenceId, false);
            });
    }

    getItem(productReferenceId) {
        return this.state.cartItems[productReferenceId];
    }
}

export default new CartStore();
