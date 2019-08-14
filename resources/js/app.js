import 'bootstrap';

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

$(document).on('click', '.js-nav-item-cart .dropdown-menu', function (e) {
    e.stopPropagation();
});
