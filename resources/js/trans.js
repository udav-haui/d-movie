module.exports = {
    methods: {
        /**
         * Translate the given key.
         */
        __(key, replace) {
            let translation, translationNotFound = true;

            try {
                translation = key.split('.').reduce((t, i) => t[i] || null, window._translations.php);
                if (translation) {
                    translationNotFound = false;
                }
            } catch (e) {
                translation = key;
            }

            if (translationNotFound) {
                translation = window._translations.json[key]
                    ? window._translations.json[key]
                    : key;
            }

            _.forEach(replace, (value, key) => {
                translation = translation.replace(':' + key, value)
            });

            return translation;
        }
    },
};
