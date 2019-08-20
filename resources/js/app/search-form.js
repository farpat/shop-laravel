import autocomplete from "../src/Autocomplete/autocomplete";
import Requestor from "../src/Request/Requestor";
import Str from "../src/String/Str";
import CartStore from "../Cart/CartStore";
import Translation from "../src/Translation/Translation";

const input = document.querySelector('#form-search-input');

const renderProduct = function (item, searchValue) {
    const text = Str.markValueIntoText(searchValue, item.label);
    const autocompleteImgString = item.image ? `<div class="autocomplete-img"><img src="${item.image}" alt="${item.label}"></div>` : '';

    return `
<div data-url="${item.url}" class="autocomplete-suggestion" data-val="${searchValue}">
    ${autocompleteImgString}
    <div class="autocomplete-description">
        <p class="autocomplete-description-label">${text}</p>
        <p class="autocomplete-description-price">${Translation.getTranslation('From') + ' ' + Str.toLocaleCurrency(item.min_unit_price_including_taxes, CartStore.data.currency)}</p>
    </div>
</div>
`;
};

const renderCategory = function (item, searchValue) {
    const text = Str.markValueIntoText(searchValue, item.label);
    const autocompleteImgString = item.image ? `<div class="autocomplete-img"><img src="${item.image}" alt="${item.label}"></div>` : '';

    return `
<div data-url="${item.url}" class="autocomplete-suggestion" data-val="${searchValue}">
    ${autocompleteImgString}
    <div class="autocomplete-description">
        <p class="autocomplete-description-label">${text}</p>
    </div>
</div>
`;
};

function renderNotItems() {
    return `
<div class="autocomplete-suggestion">
    <div class="autocomplete-description">
        <p class="autocomplete-description-label">${Translation.getTranslation('Not items')}</p>
    </div>
</div>
`;
}

new autocomplete({
    selector: input,
    minChars: 2,
    cache: true,
    delay: 150,
    source: function (q, suggest) {
        input.classList.add('searching');

        Requestor.newRequest()
            .get(input.dataset.url, {q})
            .then(response => {
                input.classList.remove('loading');
                if (response.length > 0) suggest(response);
                else {
                    suggest([{label: null}]);
                }
            });
    },
    renderItem: function (item, searchValue) {
        if (item.label === null) {
            return renderNotItems();
        } else if (item.min_unit_price_including_taxes !== undefined) {
            return renderProduct(item, searchValue);
        } else {
            return renderCategory(item, searchValue);
        }
    },
    onSelect: function (e, term, item) {
        e.preventDefault();
        window.location.href = item.dataset.url;
    }
});


const divSuggestions = document.querySelector('.autocomplete-suggestions');
input.addEventListener('focus', function () {
    if (divSuggestions.innerHTML !== '' && input.value !== '') {
        divSuggestions.style.display = 'block';
    }
});

