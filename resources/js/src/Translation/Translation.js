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

    returnNestedProperty(obj) {
        const args = Array.prototype.slice.call(arguments, 1);

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

    getMainTranslation(key) {
        this.loadMainTranslation();

        if (this.translations[this.lang + '.json'][key] !== undefined) {
            return this.translations[this.lang + '.json'][key];
        }

        return undefined;
    }

    getTranslation(key) {
        const regex = /([a-z_-]+\.)+([a-z_-]+)/g;
        let translation = key;

        if (regex.test(key)) {
            const keys = key.split('.');
            this.loadTranslation(keys.slice(0, 1));
            translation = this.returnNestedProperty(this.translations[this.lang], ...keys);
        }

        return translation;
    }

    get(key) {
        let translation;

        translation = this.getMainTranslation(key);
        if (translation !== undefined) {
            return translation
        }

        translation = this.getTranslation(key);
        if (translation !== undefined) {
            return translation;
        }

        return key;
    }
}

export default new Translation();
