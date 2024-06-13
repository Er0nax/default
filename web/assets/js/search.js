$(document).ready(function () {

    let value = null;
    let cooldown = true;
    let initialWait = 1000;
    let cooldownTime = 1000;
    const searchForm = $('#search');
    const searchBtn = $('#searchBtn');
    const searchText = $('#searchText');

    // on click
    searchBtn.on('click', (e) => {
        e.preventDefault();
    });

    // on form submit
    searchForm.on('submit', (e) => {
        e.preventDefault();

        // set the value
        value = $('#searchText').val();

        // get the results
        getResults(false);
    })

    // on input writing
    searchText.on('input', (e) => {
        // set the value
        value = $(e.currentTarget).val();

        // value above 3 characters?
        if (value.length <= 3) {
            return;
        }

        // get the results
        getResults()
    });

    // on input focus
    searchText.on('focus', () => {
        setTimeout(function () {
            cooldown = false;
        }, initialWait);
    })

    // set results
    function getResults(checkCooldown = true) {
        // use the cooldown?
        if (checkCooldown) {
            // is cooldown?
            if (cooldown) {
                return;
            }

            // set cooldown to true
            setCooldown()
        }

        // do logic
        searchBtn.attr('data-page', 'search/' + value);
        searchBtn[0].click();
    }

    // update after cooldown
    function setCooldown() {
        // set to true
        cooldown = true;

        // timer to reset cooldown
        setTimeout(function () {
            cooldown = false;
        }, cooldownTime);
    }
});