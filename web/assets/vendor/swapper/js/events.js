import * as utils from './utils.js';

/**
 * create new event listeners for the whole site
 */
export function createDocumentEvents() {
    // get all elements at the start
    const allClickElementsOnStart = document.querySelectorAll('.click');

    // add listeners for elements
    addEventListeners(allClickElementsOnStart);

    if (window['debugMode'] ?? false) {
        getElementsWithHrefButWithoutClickClass();
        getElementsWithClickButWithoutHref(allClickElementsOnStart);
    }

    utils.log(allClickElementsOnStart.length + ' elements with .click class found.');
}

/**
 * add event listeners for each given element
 * @param elements
 */
export function addEventListeners(elements) {
    // check if any elements were found
    if (!elements) {
        utils.log('Could not find any elements!', 'throw');
    }

    // loop through elements
    for (let element of elements) {

        // check if button has page attribute
        if (!element.dataset.page) {
            utils.log('Element has no page attribute. ' + element, 'warn');
            continue;
        }

        // event listener for clicks
        element.addEventListener('click', (e) => {
            // prevent all default behaviours
            e.preventDefault();

            // add attributes
            const details = {
                page: element.dataset.page
            }

            // call event
            document.dispatchEvent(new CustomEvent("swclick", {
                detail: details,
                bubbles: true,
                cancelable: true,
                composed: false,
            }));

            utils.log('##### swclick dispatched. #####');
        });
    }
}

/**
 * add event listners for the content container
 * @returns {*|*[]}
 */
export function addEventListenersForContentContainer() {
    // get all .click elements
    const container = utils.getContentContainer();
    const elements = container.querySelectorAll('.click');

    if (elements) {
        utils.log('Found new click elements: ' + elements.length);
        addEventListeners(elements);
    } else {
        utils.log('No new click elements found!');
    }
}

/**
 * show elements without click class in console
 */
function getElementsWithHrefButWithoutClickClass() {
    // all anchors with hrefs
    const hrefs = document.querySelectorAll('a[href]');

    // loop through hrefs and check if they have .click class
    for (let href of hrefs) {
        if (!href.classList.contains('click')) {
            utils.log('Found element without .click class: ', 'info', href);
        }
    }

    utils.log(hrefs.length + ' elements with href found.');
}

/**
 * check for any anchors without href
 * @param allClickElementsOnStart
 */
function getElementsWithClickButWithoutHref(allClickElementsOnStart) {
    for (let link of allClickElementsOnStart) {
        if (link.getAttribute('href') === null) {
            utils.log('Found element with .click class but without href: ', 'warn', link);
        }
    }
}

/**
 * triggered when a new page was loaded
 * @param newPage
 */
export function triggerEventChanged(newPage) {

    const details = {
        page: newPage
    }

    document.dispatchEvent(new CustomEvent("swchanged", {
        detail: details,
        bubbles: true,
        cancelable: true,
        composed: false,
    }));
}