import Vue from 'vue';
import HeaderCartComponent from "../Cart/HeaderCart/CartComponent";
import BodyCartComponent from "../Cart/BodyCart/CartComponent";
import PaymentComponent from "../Cart/Payment/PaymentComponent";

$(document).on('click', '#cart-nav', function (event) {
    event.stopPropagation();
});

new Vue({
    el:         '#cart-nav',
    components: {HeaderCartComponent}
});

if (document.querySelector('#cart-section')) {
    new Vue({
        el:         '#cart-section',
        components: {BodyCartComponent, PaymentComponent}
    });
}
