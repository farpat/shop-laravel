const units = ['o', 'Ko', 'Mo', 'Go', 'To', 'Po', 'Eo', 'Zo', 'Yo'];

class Str {
    toLocaleCurrency(amount, currency) {
        if (typeof amount === 'string') {
            amount = parseFloat(amount);
        }

        return amount.toLocaleString(undefined, {style: 'currency', currency});
    }

    toLocaleNumber(value) {
        if (value === null) {
            return '';
        }

        return parseFloat(value).toLocaleString(undefined, {
            maximumFractionDigits: 2
        });
    }

    bytesToSize(bytes) {
        let i = 0;
        while (bytes >= 1024) {
            bytes /= 1024;
            ++i;
        }

        return bytes.toFixed(2) + ' ' + units[i];
    }

    sizeToBytes(size) {
        let sizes = size.split(' ');
        let bytes = sizes[0];
        let i = 0;
        while (units[i] && units[i] !== sizes[1]) {
            bytes *= 1024;
            ++i;
        }

        return bytes;
    }

    markValueIntoText(neddle, haystack) {
        neddle = neddle.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
        let regex = new RegExp("(" + neddle.split(' ').join('|') + ")", "gi");
        return haystack.replace(regex, "<mark>$1</mark>");
    }
}

export default new Str();
