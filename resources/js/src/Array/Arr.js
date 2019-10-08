class Arr {
    returnNestedProperty(obj) {
        const args = Array.isArray(arguments[1]) ?
            arguments[1] :
            Array.prototype.slice.call(arguments, 1);

        for (let i = 0; i < args.length; i++) {
            if (!obj || !obj.hasOwnProperty(args[i])) {
                return undefined;
            }

            obj = (i + 1 === args.length) ?
                obj[args[i]] :
                Object.assign({}, obj[args[i]]);
        }

        return obj;
    }
}

export default new Arr();
