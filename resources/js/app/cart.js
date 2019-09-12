import Vue from 'vue';
import CartComponent from "../Cart/HeaderCart/CartComponent";
import FormStore from "../src/Bootstrap/FormStore";
import CartStore from "../Cart/CartStore";

for (let productReferenceId in CartStore.state.cartItems) {
    let quantity = CartStore.state.cartItems[productReferenceId].quantity;
    FormStore.changeField('quantity-' + productReferenceId, quantity);
}

new Vue({
    el: '#cart-nav',
    components: {CartComponent}
});
