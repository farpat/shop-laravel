export default class ConfirmedRule {
    constructor(params) {
        this.elementThatNeedsToConfirm = !(params instanceof HTMLElement) ?
            document.querySelector(params) :
            params;

        this.name = 'confirmed';
    }

    check(confirmationValue) {
        return (confirmationValue === this.elementThatNeedsToConfirm.value);
    }
}
