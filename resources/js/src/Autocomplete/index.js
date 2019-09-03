import Input from "./Input";

export default class Autocomplete {
    constructor(options) {
        this.setOptions(options);
        this.init();
    }

    setOptions(options) {
        var defaultOptions = {
            selector:   0,
            source:     0,
            minChars:   3,
            delay:      150,
            offsetLeft: 0,
            offsetTop:  1,
            cache:      1,
            menuClass:  undefined,
            renderItem: function (item, search) {
                // escape special characters
                search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
                var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
                return '<div class="autocomplete-suggestion" data-val="' + item + '">' + item.replace(re, "<b>$1</b>") + '</div>';
            },
            onSelect:   function (e, term, item) {
            }
        };
        this.options = {...defaultOptions, ...options};
    }

    init() {
        this.inputElements = this.options.selector instanceof HTMLElement ? [this.options.selector] : document.querySelectorAll(this.options.selector);
        this.inputs = [];
        this.inputElements.forEach((inputElement) => {
            this.inputs.push(new Input(inputElement, this.options));
        });
    }

    destroy() {
        this.inputs.forEach(function(input) {
            input.destroy();
        });
    }
}
