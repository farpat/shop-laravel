class Str {
    toLocaleCurrency(amount, currency) {
        return amount.toLocaleString(undefined, {style: 'currency', currency});
    }
}

export default new Str();
