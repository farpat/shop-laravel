import 'bootstrap';
import 'popper.js';

require('./app/search-form');
require('./app/cart');

if (document.querySelector('#category-show')) {
    require('./app/category-show');
}

if (document.querySelector('#product-show')) {
    require('./app/product-show');
}

if (document.querySelector('#login-form')) {
    require('./app/login-form');
}

if (document.querySelector('#register-form')) {
    require('./app/register-form');
}

if (document.querySelector('#informations-form')) {
    require('./app/informations-form');
}

if (document.querySelector('#password-form')) {
    require('./app/password-form');
}

if (document.querySelector('#send-token-password-form')) {
    require('./app/send-token-password-form');
}
