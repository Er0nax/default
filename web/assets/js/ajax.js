import {t} from './translation.js';
import {toggleLoadingLine} from './utils.js';

// simple ajax request
export async function get(form) {
    toggleLoadingLine(true);

    // url given?
    if (!form.get('url')) {
        toggleLoadingLine(false);
        return error(t('Could not find "url" in formdata!'));
    }

    // build url
    const url = getUrl(form) + form.get('url');

    let content = null;

    // fetch
    try {
        await $.ajax({
            url: url,
            type: 'post',
            data: form,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (response) {
                //content = JSON.parse(response.response);
                content = response;
            }
        });

    } catch (msg) {
        error(t('Error while trying to fetch result. Error: {msg}', {msg: msg}));
    }

    toggleLoadingLine(false);
    return content;
}

// return the main url
function getUrl() {
    // base url given?
    if (!window['baseUrl']) {
        error(t('Could not find variable "window.baseUrl".'));
    }

    return window['baseUrl'];
}

// log error to console
function error(text) {
    console.error('requester - ' + text);

    return false;
}