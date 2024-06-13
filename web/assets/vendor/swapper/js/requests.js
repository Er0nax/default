import * as utils from "./utils.js";

export function getUrl() {
    // base url given?
    if (!window['baseUrl']) {
        utils.log('Could not find variable "window.baseUrl"!', 'throw');
    }

    return window['baseUrl'];
}

function getOptions(data) {
    return {
        method: 'POST',
        body: data
    };
}

export async function get(data) {
    const options = getOptions(data);
    const url = getUrl(data) + data.get('url');
    let response = null;

    utils.log('Trying to fetch request... URL: ' + url);

    // fetch
    try {
        response = fetch(url, options)
            .then(async function (response) {
                if (!response.ok) {
                    throw 'swapper - Error while executing the request!';
                }

                return response.text();
            });

        response = JSON.parse(await response);

        return response;
    } catch (error) {
        utils.log('Error while trying to fetch result. Please check developer tab to see the error.', 'warn');
    } finally {
        utils.log('Fetch request done.')
    }
}