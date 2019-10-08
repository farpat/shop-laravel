import Arr from "../Array/Arr";

class Translation {
    constructor() {
        this.lang = document.querySelector('html').getAttribute('lang') || 'en';
        this.translations = {};
    }

    loadMainTranslation() {
        let json;

        if (this.translations[this.lang + '.json'] === undefined) {
            try {
                json = require(`../../../js-lang/${this.lang}.json`);
            } catch (e) {
                this.lang = 'en';

                try {
                    json = require(`../../../js-lang/${this.lang}.json`);
                } catch (e) {
                    json = {};
                }
            }

            this.translations[this.lang + '.json'] = json;
        }
    }

    loadTranslation(key) {
        let json;

        if (this.translations[this.lang] === undefined) {
            this.translations[this.lang] = {};
        }

        if (this.translations[this.lang][key] === undefined) {
            try {
                json = require(`../../../js-lang/${this.lang}/${key}.json`);
            } catch (e) {
                this.lang = 'en';

                try {
                    json = require(`../../../js-lang/${this.lang}/${key}.json`);
                } catch (e) {
                    json = {};
                }
            }
            this.translations[this.lang][key] = json;
        }
    }

    _getMainTranslation(key) {
        this.loadMainTranslation();

        return this.translations[this.lang + '.json'][key];
    }

    _getTranslation(key) {
        const regex = /([a-z_-]+\.)+([a-z_-]+)/g;

        if (regex.test(key)) {
            const keys = key.split('.');
            this.loadTranslation(keys.slice(0, 1));
            return Arr.getNestedProperty(this.translations[this.lang], keys);
        } else {
            return key;
        }
    }

    get(key) {
        return this._getMainTranslation(key) || this._getTranslation(key) || key;
    }
}

export default new Translation();
