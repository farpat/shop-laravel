import Vue from 'vue';
import HeaderCartComponent from "../Cart/HeaderCart/CartComponent";
import BodyCartComponent from "../Cart/BodyCart/CartComponent";
import PaymentComponent from "../Cart/Payment/PaymentComponent";

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

const linksUnderCartNav = document.querySelector('#cart-nav');
linksUnderCartNav.addEventListener('click', function (event) {
    if (event.target.id !== 'button-cart') {
        event.stopPropagation();
    }
})