import 'bootstrap';
import 'popper.js';
import "./app/search-form";
import "./app/cart";

if (document.querySelector('#category-show')) {
    import('./app/category-show');
}

if (document.querySelector('#product-show')) {
    import('./app/product-show');
}

if (document.querySelector('#login-form')) {
    import('./app/login-form');
}

if (document.querySelector('#register-form')) {
    import('./app/register-form');
}

if (document.querySelector('#informations-form')) {
    import('./app/informations-form');
}

if (document.querySelector('#password-form')) {
    import('./app/password-form');
}

if (document.querySelector('#send-token-password-form')) {
    import('./app/send-token-password-form');
}
