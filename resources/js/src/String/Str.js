class Str {
    toLocaleCurrency(amount, currency) {
        if (typeof amount === 'string') {
            amount = parseFloat(amount);
        }

        return amount.toLocaleString(undefined, {style: 'currency', currency});
    }

    markValueIntoText(value, text) {
        value = value.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
        let regex = new RegExp("(" + value.split(' ').join('|') + ")", "gi");
        return text.replace(regex, "<mark>$1</mark>");
    }
}

export default new Str();
