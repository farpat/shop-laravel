export default class RequiredRule {
    constructor() {
        this.name = 'required';
    }

    check(value) {
        if (typeof value === 'object') {
            return value.length > 0;
        } else if (typeof value === 'boolean') {
            return value;
        }

        return (value !== undefined && value !== '');
    }
}
