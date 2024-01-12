import * as basics from "./basics.js";

$(document).ready(function () {

    // create global variables
    const form = new FormData();
    const pageHistory = [];

    // listen to button clicks
    $(document).on("click", '.click', async function (event) {
        // prevent defaults
        event.preventDefault();

        // get target button
        const target = $(this);

        // get new page
        const page = await getPage(target);
        const param = await getParam(target);
        const isReload = await getReload(target);

        // start logic
        await setPage(page, param, isReload);
    });

    const getReload = async (target) => {
        // is valid?
        if (!target.attr('data-isreload')) {
            return false;
        }

        // is undefined?
        if (target.attr('data-isreload') == 'undefined') {
            return false;
        }

        // is true?
        if (target.attr('dada-isreload') == 'true') {
            return true;
        }

        // return default false
        return false;
    }

    const setPage = async (page, param, isReload) => {
        // show loading line
        await basics.toggleLoadingLine(true);

        // add to form
        form.set('page', page);
        form.set('param', param);

        // get the content
        const content = await getContent();

        // on success
        if (content) {
            if (!isReload) {
                await onSuccess();
            } else {
            }
        } else {
            Swal.fire({
                icon: 'error', 'title': 'Something went wrong.'
            })
        }

        // set content
        await setContent(content, page);

        // hide loading line
        await basics.toggleLoadingLine(false);
    }

    $(window).on('popstate', async function (event) {
        event.preventDefault();
        await pageGoBack();
    });

    $(document).on("click", '.click-back', async function (event) {
        event.preventDefault();
        await pageGoBack();
    });

    const pageGoBack = async () => {
        // get the total length of array
        const length = pageHistory.length;

        // get penultimate one
        let page = pageHistory[length - 2];
        let param = 'undefined';
        let currentPage = basics.getCurrentPage()['page'];

        if (!page) {
            return;
        }

        // check if page includes parameter
        if (page.includes("&")) {
            // get the parameter
            param = '&' + page.split('&')[1];
            page = page.split('&')[0];
        }

        if (page) {
            // start logic
            await setPage(page, param);
        }
    }

    const onSuccess = async () => {
        // get the new page
        let page = form.get('page');
        let param = form.get('param');

        if (!param || param == 'undefined') {
            param = '';
        }

        // set new headline + subline + title ...
        await switchPartials(page);

        // set new active status
        await setActiveStatus(page);

        // add to history
        pageHistory.push(page + param);

        // update reload btn
        const reloadBtn = $('.page-btn-reload');
        reloadBtn.attr('data-page', page);
        reloadBtn.attr('data-param', param);

        // switch & with ? for look
        if (param) {
            param = param.replace(param.charAt(0), "?");
        }

        if (!param) {
            switch (page) {
                case 'index':
                    page = '';
                    break;
            }
        }

        // add also to browser history
        await history.pushState({}, '', basics.getUrl() + page + param);

        // close the sidebar
        await closeSidebarMobile();

        // scroll to top
        if (window.auto_scroll_top) {
            setTimeout(
                async function () {
                    await window.scrollTo(0, 0);
                }, 100);
        }
    }

    const reInit = async (page) => {
        switch (page) {
            case 'pagename':
            // reinit something...
        }
    }

    const closeSidebarMobile = async () => {
        if ($(window).width() < 1200) {
            // close the sidebars ...
        }
    }

    const switchPartials = async (newPage) => {
        // set the title
        document.title = siteTitle;
    }

    // return the page from data attribute
    const getPage = (target) => {
        return target.attr('data-page');
    }

    // return the param from data attribute
    const getParam = (target) => {
        let param = target.attr('data-param');

        if (!param) {
            return '';
        }

        if (param == 'undefined') {
            return '';
        }

        if (param == 'null') {
            return '';
        }

        return param;
    }

    // return the new content for the page
    const getContent = async () => {
        const page = form.get('page');
        let param = form.get('param');
        let content = null;

        // check if page is given
        if (!page) {
            return null;
        }

        // check if param is given
        if (!param || param === 'undefined' || param === 'null') {
            param = '';
        }


        // create the url
        let url = basics.getUrl();

        // check if url is given
        if (!url) {
            return null;
        }

        // add to the url
        url = url + '_content.php?page=' + page + param;

        // get the content
        try {
            content = await $.get(url);
        } catch (error) {
            return null;
        }

        return content;
    }

    // set the new content
    const setContent = async (content, page) => {
        // check if content is given
        if (content) {
            await $('#pagecontent').hide().html(content).fadeIn(100);

            // re-init
            await reInit(page);
        }
    }

    // set new active status for buttons
    const setActiveStatus = function () {
        // get page
        let page = form.get('page');

        // un-active all current buttons
        $('.click').removeClass("active");

        // remove from search bar
        if (page !== 'search') {
            $('.searchIn').val('');
        }

        switch (page) {
            case 'meeting':
                page = 'meetings';
                break;
        }

        // set active class for all buttons with page attribute
        $(".click[data-page='" + page + "']").addClass('active');
    }

    /**
     * set current page to history in array
     */
    const setCurrentPageToHistory = () => {
        const response = basics.getCurrentPage();
        const page = response['page'];
        let param = response['params'];

        pageHistory.push(page + param);

        // switch & with ? for look
        if (param) {
            param = param.replace(param.charAt(0), "?");
        }

        history.pushState({}, '', basics.getUrl() + page + param);
    }

    /**
     * search input was pressed?
     */
    $("#search").submit(async function (e) {
        // prevent page reload
        e.preventDefault();

        // selector
        const searchInput = $('#searchInPC');

        // get the keyword
        const keyword = encodeKeyword(searchInput.val());

        await search(keyword, searchInput);
    });

    /**
     * search input was pressed?
     */
    $("#searchMobile").submit(async function (e) {
        // prevent page reload
        e.preventDefault();

        // selector
        const searchInput = $('.mobileSearchText');

        // get the keyword
        const keyword = encodeKeyword(searchInput.val());

        await search(keyword, searchInput);
    });

    /**
     * @param keyword
     * @param input
     * @returns {Promise<void>}
     */
    const search = async (keyword, input) => {

        // get new page
        const page = 'search';
        let param = '';

        if (keyword) {
            param = '&q=' + keyword;
        }

        // start logic
        await setPage(page, param);

        // remove focus
        input.blur();
    }

    /**
     * encode a custom keyword
     * @param keyword
     * @returns {*}
     */
    const encodeKeyword = (keyword) => {

        // replace all weird words
        keyword = keyword.replace("'", '');
        keyword = keyword.replace('"', '');
        keyword = keyword.replace('<', '');
        keyword = keyword.replace('>', '');
        keyword = keyword.replace('SELECT', '');
        keyword = keyword.replace('DELETE', '');
        keyword = keyword.replace('WHERE', '');
        keyword = keyword.replace('FROM', '');
        keyword = keyword.replace('AVG', '');
        keyword = keyword.replace('SUM', '');
        keyword = keyword.replace('MAX', '');
        keyword = keyword.replace('MIN', '');

        // return the keyword
        return keyword;
    }

    setCurrentPageToHistory();
});