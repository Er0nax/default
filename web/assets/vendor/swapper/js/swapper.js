import * as utils from './utils.js';
import * as events from './events.js';
import * as request from './requests.js';
import {t} from '../../../js/translation.js';
import {getUrl} from "./requests.js";

// window loaded?
window.onload = async function () {
    let contentContainer = null;
    let currentPage = window['currentPage'] ?? null;
    let ajaxLoading = false;

    // init swapper
    function initSwapper() {
        // create files
        utils.includeFiles();
        utils.createLoadingLine();

        // get the content div container
        contentContainer = utils.getContentContainer();

        // create events
        events.createDocumentEvents();

        // listen to events
        document.addEventListener('swclick', handleClick);

        setFirstPage();
    }

    // when a swapper button was clicked
    async function handleClick(event) {
        // page already loading?
        if (ajaxLoading) {
            return;
        }

        ajaxLoading = true;
        utils.toggleLoadingLine(true);
        const pageObject = parsePage(event.detail.page);

        // set the page content
        await setContent(pageObject);

        utils.toggleLoadingLine(false);
        ajaxLoading = false;
    }

    // set the content
    async function setContent(pageObject) {
        const page = pageObject.page;

        // check if current page is same as new page
        if (page !== 'profile' && page !== 'meeting' && page !== 'search' && currentPage === page) {
            utils.log('New page has the same name as the current page. Aborting.');
            return;
        }

        // build url
        let url = buildURL(pageObject);

        // create form-data
        const data = new FormData();
        data.set('url', 'api/swapper/GetContent/' + url);

        // get response
        const response = await request.get(data);

        if (response && response.response) {
            // has error?
            if (response.error) {
                utils.log('There was an error on our side! Please try again :)', 'error');
                return;
            }

            // is cached?
            if (response['cached']) {
                utils.log('Returned content is cached until ' + response['cachedUntil']);
            }

            // set html
            updateContainer(response.response.html ?? '');

            // update title
            updateTitle(response.response.page.title);

            // update url
            updateURL(url, response.response.html ?? '', pageObject, response.response.page.title)

            // trigger event
            events.triggerEventChanged(page);

            // update current page
            currentPage = page;
        }
    }

    // returns a title for a current page
    function getTitle(title) {
        // site title given?
        if (!window['siteTitle']) {
            utils.log('Could not find "window.siteTitle".', 'warn');
        }

        // page title given?
        if (!title) {
            utils.log('Could not find a valid title for the page.', 'warn');
            return;
        }

        return window['siteTitle'] + ' | ' + t(title);
    }

    // build the url and return it
    function buildURL(pageObject) {
        let url = pageObject.page;
        if (pageObject.params && pageObject.params.length > 0) {
            url += '/' + pageObject.params.join('/');
        }

        return url;
    }

    // get the actual page
    function parsePage(inputString) {
        const parts = inputString.split('/');
        const page = parts[0];
        const params = parts.slice(1);
        const routes = utils.getRoutes();

        // check if page is in rewrites routes
        const pageFromRoutes = routes[page] || page;

        // no params found
        if (params.length === 0) {
            return {page: pageFromRoutes, params: []};
        }

        return {page: pageFromRoutes, params};
    }

    // set the html to the container
    function updateContainer(html) {
        utils.log('Trying to update container content...');

        // container found?
        if (!contentContainer) {
            utils.log('Could not find content container!', 'throw');
        }

        // try to set the html
        try {
            contentContainer.innerHTML = html;
            utils.log('Content inside container updated.');
        } catch (error) {
            utils.log('Could not set content inside container!', 'warn');
        }

        // add event listeners
        utils.log('Scanning for new .click elements...');
        events.addEventListenersForContentContainer();
    }

    // update the page url with params
    function updateURL(url, html, pageObject, pageTitle) {

        // replace index with nothing
        if (url === 'index') {
            url = '';
        }

        const fullURL = request.getUrl() + url;
        const title = getTitle(pageTitle)

        // check if current url is same as new url
        if (location.href === fullURL) {
            utils.log('Current URL is same as new URL. Aborting.');
            return;
        }

        try {
            window.history.pushState({"html": html, "title": title, "page": pageObject.page}, "", fullURL);
            utils.log('URL has been updated.');
        } catch (error) {
            utils.log('Could not set new URL!', 'error');
        }
    }

    // set a new title
    function updateTitle(pageTitle) {
        // update title...
        const title = getTitle(pageTitle);
        document.title = title;
        utils.log('Updated title. New title set to: "' + title + '"');
    }

    function setFirstPage() {
        // get html
        const pageObject = parsePage($('#hidden-click-element').attr('data-page'));
        let page = pageObject.page;
        let params = pageObject.params;
        const html = $('#page-content').html();
        const title = getTitle(page);

        if (page === 'index') {
            page = '';
        } else if(params) {
            // add params if they exist
            page += '/' + params;
        }

        const fullURL = getUrl() + page;

        // add current opened site to page history
        window.history.pushState({"html": html, "title": title, "page": page}, "", fullURL);
    }

    // for- and backward listener
    window.addEventListener("popstate", (e) => {
        if (e.state) {
            contentContainer.innerHTML = e.state.html;
            document.title = e.state.title;
            currentPage = e.state.page;

            // add event listeners for html
            events.addEventListenersForContentContainer();
            events.triggerEventChanged(currentPage);
        }
    });

    // start logic
    initSwapper();
}