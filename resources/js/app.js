import 'bootstrap';

require('./app/search-form');

if (document.querySelector('#category-show')) {
    require('./app/category-show');
}

if (document.querySelector('#product-show')) {
    require('./app/product-show');
}

if (document.querySelector('#cart')) {
    require('./app/cart');
}

if (document.querySelector('#login-form')) {
    require('./app/login-form');
}

if (document.querySelector('#register-form')) {
    require('./app/register-form');
}
