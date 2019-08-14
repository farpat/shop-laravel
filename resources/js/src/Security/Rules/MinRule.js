export default class MinRule {
    constructor(params) {
        this.params = params;
        this.name = 'min';
    }

    check(value) {
        if (typeof value === 'string') {
            return (value.length === 0 || value.length > this.params);
        }

        return false;
    }
}
