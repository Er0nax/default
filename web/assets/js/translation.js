let translations = [];
let translationLoaded = false;

(async function ($) {
    // build url
    const url = window['baseUrl'] + 'api/request/translations';

    // get response
    await $.ajax({
        url: url,
        type: 'post',
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function (response) {
            //content = JSON.parse(response.response);
            translations = response['response'];
        }
    });

    translationLoaded = true;
})(window.jQuery);

async function insertTranslation(string) {
    // build url
    const url = window['baseUrl'] + 'api/request/InsertTranslation';

    const data = new FormData();
    data.set('string', string);

    // get response
    await $.ajax({
        url: url,
        type: 'post',
        data: data,
        dataType: 'json',
        contentType: false,
        processData: false
    });

    return string;
}

function getNextId(translations) {
    // Finde die höchste vorhandene ID im Array
    const maxId = translations.reduce((max, item) => {
        return item.id > max ? item.id : max;
    }, 0);

    // Generiere die nächsthöhere ID
    return maxId + 1;
}

export function t(string, object) {
    // translations already loaded?
    if (!translationLoaded) {
        return string;
    }

    // get value by setting all to lower case
    const value = string.toLowerCase();

    // check if translation is in translations array
    const item = translations.find(item => item['value'] === value);

    // was item found?
    if (item) {
        // does language exist as value
        if (item[window['lang']] && item[window['lang']].length !== 0) {
            // set string
            string = item[window['lang']]
        }
    } else {
        // add to translations
        const newTranslation = {
            id: getNextId(translations),
            category: "site",
            value: value,
            de: '',
            en: string,
            active: true,
            updated_at: "2024-05-24 12:00:00",
            created_at: "2024-05-24 12:00:00"
        };

        // add to array
        translations.push(newTranslation);

        // add to db
        insertTranslation(string).then(r => console.log('inserted translation with value: ' + string));
    }

    string = string.replace(/{(\w+)}/g, (match, key) => {
        return object[key];
    });

    return string;
}