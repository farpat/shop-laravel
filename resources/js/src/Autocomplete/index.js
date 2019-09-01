function hasClass(el, className) {
    return el.classList.contains(className);
}

function addEvent(el, type, handler) {
    el.addEventListener(type, handler);
}

function removeEvent(el, type, handler) {
    el.removeEventListener(type, handler);
}

function live(elClass, event, cb, context) {
    addEvent(context || document, event, function (e) {
        let found, el = e.target;
        while (el && !(found = hasClass(el, elClass))) el = el.parentElement;
        if (found) cb.call(el, e);
    });
}

export default class Index {
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
        this.inputs = this.options.selector instanceof HTMLElement ? [this.options.selector] : document.querySelectorAll(this.options.selector);
        this.inputs.forEach((input) => {
            input.sc = document.createElement('div');
            input.sc.classList.add('autocomplete-suggestions');
            if (this.options.menuClass !== undefined) {
                input.sc.classList.add(this.options.menuClass);
            }

            input.autocompleteAttr = input.getAttribute('autocomplete');
            input.setAttribute('autocomplete', 'off');
            input.cache = {};
            input.lastValue = '';

            input.updateSC = (resize, next) => {
                var rect = input.getBoundingClientRect();
                input.sc.style.left = Math.round(rect.left + window.pageXOffset + this.options.offsetLeft) + 'px';
                input.sc.style.top = Math.round(rect.bottom + window.pageYOffset + this.options.offsetTop) + 'px';
                input.sc.style.width = Math.round(rect.right - rect.left) + 'px'; // outerWidth
                if (!resize) {
                    input.sc.style.display = 'block';
                    if (!input.sc.maxHeight) {
                        input.sc.maxHeight = parseInt(getComputedStyle(input.sc, null).maxHeight);
                    }
                    if (!input.sc.suggestionHeight) {
                        input.sc.suggestionHeight = input.sc.querySelector('.autocomplete-suggestion').offsetHeight;
                    }
                    if (input.sc.suggestionHeight) {
                        if (!next) {
                            input.sc.scrollTop = 0;
                        } else {
                            var scrTop = input.sc.scrollTop,
                                selTop = next.getBoundingClientRect().top - input.sc.getBoundingClientRect().top;
                            if (selTop + input.sc.suggestionHeight - input.sc.maxHeight > 0) {
                                input.sc.scrollTop = selTop + input.sc.suggestionHeight + scrTop - input.sc.maxHeight;
                            } else if (selTop < 0) {
                                input.sc.scrollTop = selTop + scrTop;
                            }
                        }
                    }
                }
            };

            addEvent(window, 'resize', input.updateSC);
            document.body.appendChild(input.sc);

            live('autocomplete-suggestion', 'mouseleave', function (e) {
                var sel = input.sc.querySelector('.autocomplete-suggestion.selected');
                if (sel) setTimeout(function () {
                    sel.classList.remove('selected');
                }, 20);
            }, input.sc);

            live('autocomplete-suggestion', 'mouseover', function (e) {
                var sel = input.sc.querySelector('.autocomplete-suggestion.selected');
                if (sel) {
                    sel.classList.remove('selected');
                }
                this.classList.add('selected');
            }, input.sc);

            live('autocomplete-suggestion', 'mousedown', function (e) {
                if (hasClass(this, 'autocomplete-suggestion')) { // else outside click
                    var value = this.getAttribute('data-val');
                    input.value = value;
                    this.options.onSelect(e, value, this);
                    input.sc.style.display = 'none';
                }
            }, input.sc);

            input.blurHandler = () => {
                var overSB;

                try {
                    overSB = document.querySelector('.autocomplete-suggestions:hover');
                } catch (e) {
                    overSB = false;
                }
                if (!overSB) {
                    input.lastValue = input.value;
                    input.sc.style.display = 'none';
                    setTimeout(function () {
                        input.sc.style.display = 'none';
                    }, 350); // hide suggestions on fast input
                } else if (input !== document.activeElement) {
                    setTimeout(function () {
                        input.focus();
                    }, 20);
                }
            };
            addEvent(input, 'blur', input.blurHandler);

            var suggest = (data) => {
                var val = input.value;
                input.cache[val] = data;
                if (data.length && val.length >= this.options.minChars) {
                    var s = '';
                    for (let i = 0; i < data.length; i++) {
                        s += this.options.renderItem(data[i], val);
                    }
                    input.sc.innerHTML = s;
                    input.updateSC(0);
                } else
                    input.sc.style.display = 'none';
            };

            input.keydownHandler = (e) => {
                var key = e.keyCode;
                // down (40), up (38)
                if ((key == 40 || key == 38) && input.sc.innerHTML) {
                    let next;
                    var sel = input.sc.querySelector('.autocomplete-suggestion.selected');
                    if (!sel) {
                        next = (key == 40) ?
                            input.sc.querySelector('.autocomplete-suggestion') :
                            input.sc.children[input.sc.children.length - 1]; // first : last
                        next.classList.add('selected');
                        input.value = next.getAttribute('data-val');
                    } else {
                        next = (key == 40) ?
                            sel.nextElementSibling :
                            sel.previousElementSibling;

                        if (next) {
                            sel.className = sel.className.replace('selected', '');
                            next.classList.add('selected');
                            input.value = next.getAttribute('data-val');
                        } else {
                            sel.className = sel.className.replace('selected', '');
                            input.value = input.lastValue;
                            next = 0;
                        }
                    }
                    input.updateSC(0, next);
                    return false;
                }
                // esc
                else if (key == 27) {
                    input.value = input.lastValue;
                    input.sc.style.display = 'none';
                }
                // enter
                else if (key == 13 || key == 9) {
                    var sel = input.sc.querySelector('.autocomplete-suggestion.selected');
                    if (sel && input.sc.style.display != 'none') {
                        this.options.onSelect(e, sel.getAttribute('data-val'), sel);
                        setTimeout(function () {
                            input.sc.style.display = 'none';
                        }, 20);
                    }
                }
            };
            addEvent(input, 'keydown', input.keydownHandler);

            input.keyupHandler = (e) => {
                var key = e.keyCode;
                if (!key || (key < 35 || key > 40) && key != 13 && key != 27) {
                    var val = input.value;
                    if (val.length >= this.options.minChars) {
                        if (val != input.lastValue) {
                            input.lastValue = val;
                            clearTimeout(input.timer);
                            if (this.options.cache) {
                                if (val in input.cache) {
                                    suggest(input.cache[val]);
                                    return;
                                }
                                // no requests if previous suggestions were empty
                                for (let i = 1; i < val.length - this.options.minChars; i++) {
                                    var part = val.slice(0, val.length - i);
                                    if (part in input.cache && !input.cache[part].length) {
                                        suggest([]);
                                        return;
                                    }
                                }
                            }
                            input.timer = setTimeout(() => {
                                this.options.source(val, suggest)
                            }, this.options.delay);
                        }
                    } else {
                        input.lastValue = val;
                        input.sc.style.display = 'none';
                    }
                }
            };
            addEvent(input, 'keyup', input.keyupHandler);

            input.focusHandler = (e) => {
                input.lastValue = '\n';
                input.keyupHandler(e)
            };
            if (!this.options.minChars) {
                addEvent(input, 'focus', input.focusHandler);
            }
        });
    }

    destroy() {
        this.inputs.forEach((input) => {
            removeEvent(window, 'resize', input.updateSC);
            removeEvent(input, 'blur', input.blurHandler);
            removeEvent(input, 'focus', input.focusHandler);
            removeEvent(input, 'keydown', input.keydownHandler);
            removeEvent(input, 'keyup', input.keyupHandler);
            if (input.autocompleteAttr)
                input.setAttribute('autocomplete', input.autocompleteAttr);
            else
                input.removeAttribute('autocomplete');
            document.body.removeChild(input.sc);
            input = null;
        })
    }
}
