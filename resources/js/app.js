import 'bootstrap';
import 'popper.js';
import "./app/search-form";
import "./app/cart";

const script = document.body.dataset.script;

if (script) {
    import (`./app/${script}`);
}