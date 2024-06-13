$(document).ready(function () {
    // scroll top?
    const userSettings = window['userSettings'] ?? [];

    // returns the value of the setting or false on null/error
    function getSetting(setting) {
        return userSettings[setting] ?? false;
    }

    // when page was updated
    $(document).on('swchanged', (e) => {
        checkSettings(e);
    });

    // auto scroll top
    function checkSettings(e) {
        const page = e.originalEvent.detail.page;

        // scroll top?
        if (getSetting('auto_scroll_top')) {
            window.scrollTo(0, 0);
        }
    }
});