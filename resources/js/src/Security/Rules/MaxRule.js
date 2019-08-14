import StringU from "../../String/StringU";

export default class MaxRule {
    constructor(params) {
        this.params = params;
        this.maxSize = StringU.sizeToBytes(params);
        this.name = 'max';
    }

    /**
     * Vérifie que le poids de la liste des fichiers ne dépasse pas size
     * @param {Array} files
     * @return boolean
     */
    verifyFileSize(files) {
        let totalSize = files.reduce((acc, file) => acc + file.size, 0);
        return totalSize <= this.maxSize;
    }

    check(value) {
        if (value instanceof Array) {
            return this.verifyFileSize(value);
        } else if (typeof value === 'string') {
            return (value.length === 0 || value.length < this.params);
        }

        return false;
    }
}
