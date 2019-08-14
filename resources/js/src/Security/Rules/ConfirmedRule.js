export default class ConfirmedRule {
    constructor(params) {
        if (!(params instanceof HTMLElement)) {
            this.elementThatNeedsToConfirm = document.querySelector(params);
        } else {
            this.elementThatNeedsToConfirm = params;
        }

        this.name = 'confirmed';
    }

    check(value) {
        return (value === this.elementThatNeedsToConfirm.value);
    }
}
