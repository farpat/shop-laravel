import Vue from 'vue';
import Requestor from "../src/Request/Requestor";

class CartStore {
    constructor() {
        this.state = {
            ...window.CartStore.state
        };

        this.data = {
            ...window.CartStore.data,
            endPoint: '/api/cart-items'
        };
    }

    updateItem(productReferenceId, quantity) {
        const request = Requestor.newRequest();
        request
            .patch(this.data.endPoint + '/' + productReferenceId, {
                quantity
            })
            .then(cartItem => {
                console.log('update item response', cartItem);
                Vue.set(this.state.cartItems, productReferenceId, cartItem)
            });
    }

    deleteItem(productReferenceId) {

        const request = Requestor.newRequest();
        request
            .delete(this.data.endPoint + '/' + productReferenceId)
            .then(() => {
                this.state.cartItemsLength--;
                Vue.delete(this.state.cartItems, productReferenceId)
            });
    }

    addItem(productReferenceId, quantity) {
        const request = Requestor.newRequest();
        request
            .post(this.data.endPoint, {
                product_reference_id: productReferenceId,
                quantity
            })
            .then(cartItem => {
                this.state.cartItemsLength++;
                Vue.set(this.state.cartItems, productReferenceId, cartItem)
            });
    }

    getItem(productReferenceId) {
        return this.state.cartItems[productReferenceId];
    }
}

export default new CartStore();
