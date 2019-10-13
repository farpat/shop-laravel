class Arr {
    getNestedProperty(object, keys) {
        for (let i = 0; i < keys.length; i++) {
            let key = keys[i];

            if (!object || !object.hasOwnProperty(key)) {
                return undefined;
            }

            object = object[key];
        }

        return object;
    }

    first(arr) {
        const keys = Object.keys(this.data.productReferences);

        if (keys.length === 0) {
            return null;
        }

        return this.data.productReferences[keys[0]];
    }

    isEmpty(arr) {
        if (arr.length !== undefined && arr.length === 0) {
            return true;
        }

        if (typeof arr === 'object' && Object.keys(arr).length === 0) {
            return true;
        }

        return false;
    }

    returnNestedObject(arr, string, value) {
        let nestedObject = {...arr}; //To ensure don't reset arr's reference
        let nextObject = {};

        const matches = Array.from(string.matchAll(/\[?([\w_-]+)\]?/g));

        for (let i = 0; i < matches.length; i++) {
            let key = matches[i][1];

            if (i === 0) { //start
                if (nestedObject[key] === undefined) {
                    nestedObject[key] = {};
                }
                nextObject = nestedObject[key];
            } else if (i === matches.length - 1) { //end
                nextObject[key] = value;
            } else { //middle
                if (nextObject[key] === undefined) {
                    nextObject[key] = {};
                }
                nextObject = nextObject[key];
            }
        }

        return nestedObject;
    }
}

export default new Arr();
