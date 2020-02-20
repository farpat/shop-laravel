import Vue from 'vue';
import Requestor from "@farpat/api";
import Store from "../src/Bootstrap/Store";

/**
 * @property {Object} state
 * @property {Object} state.cartItems
 * @property {Number} state.cartItemsLength
 * @property {Object} state.isLoading
 * @property {Object} data
 * @property {String} data.currencyCode
 * @property {String} data.purchaseUrl
 * @property {String} data.endPoint
 */
class CartStore {
    constructor() {
        this.state = {
            ...window._CartStore.state,
            isLoading: {},
        };

        this.data = {
            ...window._CartStore.data,
            endPoint: '/cart-items'
        };

        for (let productReferenceId in this.state.cartItems) {
            Store.setData(`quantity[${productReferenceId}]`, this.state.cartItems[productReferenceId].quantity);
        }
    }

    updateItem(productReferenceId, quantity) {
        Vue.set(this.state.isLoading, productReferenceId, true);

        return Requestor.newRequest()
            .patch(`${this.data.endPoint}/${productReferenceId}`, {quantity})
            .then(cartItem => Vue.set(this.state.cartItems, productReferenceId, cartItem))
            .finally(() => Vue.set(this.state.isLoading, productReferenceId, false));
    }

    deleteItem(productReferenceId) {
        Vue.set(this.state.isLoading, productReferenceId, true);

        return Requestor.newRequest()
            .delete(`${this.data.endPoint}/${productReferenceId}`)
            .then(() => {
                this.state.cartItemsLength--;
                Vue.delete(this.state.cartItems, productReferenceId);
                Store.deleteData(`quantity[${productReferenceId}]`);
            })
            .finally(() => Vue.set(this.state.isLoading, productReferenceId, false));
    }

    addItem(productReferenceId, quantity) {
        Vue.set(this.state.isLoading, productReferenceId, true);

        return Requestor.newRequest()
            .post(this.data.endPoint, {product_reference_id: productReferenceId, quantity})
            .then(cartItem => {
                this.state.cartItemsLength++;
                Vue.set(this.state.cartItems, productReferenceId, cartItem);
            })
            .finally(() => Vue.set(this.state.isLoading, productReferenceId, false));
    }

    getItem(productReferenceId) {
        return this.state.cartItems[productReferenceId];
    }
}

export default new CartStore();
