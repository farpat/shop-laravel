import 'bootstrap';

console.log('app.js loading ...');

const jsRequire = document.body.dataset.jsRequire;
if (jsRequire) {
    require('./front/' + jsRequire);
}
