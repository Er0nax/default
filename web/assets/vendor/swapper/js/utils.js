let debugMode = getDebugMode();
let rewriteRoutesInfoShown = false;
const scriptDirectory = getScriptDirectory("swapper.js");
const additionalFiles = ['css/lines.css']

/**
 * toggle the loading line
 * @param show
 */
export function toggleLoadingLine(show = true) {
    const loadingLine = document.querySelector('.swapper-loading-line');

    if (show) {
        log('Loading-line style switched to "block".');
        loadingLine.style.display = 'block';
    } else {
        log('Loading-line style switched to "none".');
        loadingLine.style.display = 'none';
    }
}

/**
 * add the swapper loading line at the top of the body
 */
export function createLoadingLine() {
    const loadingLine = document.querySelector('.swapper-loading-line');

    if (!loadingLine) {
        log('No loading line found. Creating one...');

        const html = '<div class="swapper-loading-line"></div>';
        document.body.insertAdjacentHTML('afterbegin', html);
    }
}

/**
 * returns the current script directory
 * @param scriptName
 * @returns {null|string}
 */
function getScriptDirectory(scriptName) {
    const scripts = document.getElementsByTagName('script');

    if (!scripts) {
        log('Could not find any scripts!', 'throw');
    }

    for (let i = 0; i < scripts.length; i++) {
        if (scripts[i].src.includes(scriptName)) {
            const scriptUrl = scripts[i].src;
            const lastSlashIndex = scriptUrl.lastIndexOf('/');
            const directory = scriptUrl.substring(0, scriptUrl.substring(0, lastSlashIndex).lastIndexOf('/') + 1);
            log('Found script directory: ' + directory);
            return directory;
        }
    }

    return null;
}

/**
 * include additional important files
 */
export function includeFiles() {
    if (!additionalFiles) {
        log('Could not find any additional files!', 'throw');
    }

    for (let i = 0; i < additionalFiles.length; i++) {
        const fileType = getFileType(additionalFiles[i]);
        if (fileType === 'css') {
            includeCssFile(additionalFiles[i]);
        } else if (fileType === 'js') {
            includeJsFile(additionalFiles[i]);
        }
    }
}

/**
 * returns a filetype by filename
 * @param filename
 * @returns {unknown}
 */
function getFileType(filename) {
    return filename.split('.').pop();
}

/**
 * load css file
 * @param filename
 */
function includeCssFile(filename) {
    if (!scriptDirectory) {
        log('Could not get the current script directory!', 'throw');
    }

    const head = document.head || document.getElementsByTagName('head')[0];
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.type = 'text/css';
    link.href = scriptDirectory + filename;
    head.appendChild(link);
    log('Appended CSS file: ' + scriptDirectory + filename);
}

/**
 * load js file
 * @param filename
 */
function includeJsFile(filename) {
    const script = document.createElement('script');
    script.src = scriptDirectory + filename;
    document.body.appendChild(script);
    log('Appended JS file: ' + scriptDirectory + filename);
}

/**
 * returns the HTMLElement for the content container or throws error
 * @returns {HTMLElement}
 */
export function getContentContainer() {
    // variable exists?
    if (!window['contentContainerID']) {
        log('swapper - Could not find "window.contentContainerID" variable!', 'throw');
    }

    // get container
    const container = document.getElementById(window['contentContainerID']);

    // container exists?
    if (!container) {
        log('swapper - Could not find container with id "' + window['contentContainerID'] + '"!', 'throw');
    }

    // return the HTMLElement
    return container;
}

/**
 * returns object with rewrite routes
 * @returns {{}|*}
 */
export function getRoutes() {
    if (window['rewriteRoutes']) {
        return window['rewriteRoutes'];
    }

    if (!rewriteRoutesInfoShown) {
        log('No rewrite routes have been specified. For help, see the documentation.');
        rewriteRoutesInfoShown = true;
    }
    return {};
}

/**
 * returns if we should log everything
 * @returns {boolean}
 */
export function getDebugMode() {
    if (!window['debugMode']) {
        return false;
    }

    return window['debugMode'];
}

export function log(message, type = "info", info = null) {
    const text = 'swapper - ' + message;

    switch (type) {
        case 'info':
            if (debugMode) {
                (info) ? console.info(text, info) : console.info(text);
            }
            break;
        case 'warn':
            (info) ? console.warn(text, info) : console.warn(text);
            break;
        case 'error':
            (info) ? console.error(text, info) : console.error(text);
            break;
        default:
            // hide loading line cause js will stop working after throw
            toggleLoadingLine(false);
            throw (text);
    }
}