class Arr {
    firstKey(arr) {
        return Object.keys(arr)[0];
    }

    getNestedProperty(obj) {
        const args = Array.isArray(arguments[1]) ?
            arguments[1] :
            Array.from(arguments).slice(1);

        for (let i = 0; i < args.length; i++) {
            let key = args[i];

            if (!obj || !obj.hasOwnProperty(key)) {
                return undefined;
            }

            obj = obj[key];
        }

        return obj;
    }

    createNestedObject(string, value) {
        let nestedObject = {};
        let nextObject = {};

        const matches = Array.from(string.matchAll(/\[?([\w_-]+)\]?/g));

        for (let i = 0; i < matches.length; i++) {
            let key = matches[i][1];

            if (i === 0) {
                nestedObject[key] = {};
                nextObject = nestedObject[key];
            }

            else if (i === matches.length - 1) {
                nextObject[key] = value;
            }
            else {
                nextObject[key] = {};
                nextObject = nextObject[key];
            }
        }

        return nestedObject;
    }
}

export default new Arr();
