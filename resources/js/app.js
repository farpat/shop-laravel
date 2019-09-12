import 'bootstrap';

require('./app/search-form');
require('./app/cart');

$(document).on('click', '.js-nav-item-cart .dropdown-menu', function (e) {
    e.stopPropagation();
});

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

if (document.querySelector('#send-token-password-form')) {
    require('./app/send-token-password-form');
}
