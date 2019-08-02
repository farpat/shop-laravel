import 'bootstrap';

if (document.querySelector('#category-show')) {
    require('./front/category-show');
}

if (document.querySelector('#product-show')) {
    require('./front/product-show');
}

if (document.querySelector('#cart')) {
    require('./front/cart');
}

$(document).on('click', '.js-nav-item-cart .dropdown-menu', function (e) {
    e.stopPropagation();
});

