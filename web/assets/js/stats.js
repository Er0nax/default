import * as utils from './utils.js';
import * as ajax from './ajax.js';
import {t} from './translation.js';

$(document).ready(function () {

    let charts = [];

    // init all charts
    function init() {
        // get all charts
        charts = $('.chart');

        // check if any charts were found
        if (!charts || charts.length === 0) {
            return utils.log('Could not find any charts.');
        }

        // loop through given charts
        charts.each(async function (index) {
            const chartID = this.getAttribute('id');

            // create chart with data
            await createChart(this, chartID);
        });
    }

    // build chart
    async function createChart(canvas, id) {
        let config = null;

        // get config by id
        switch (id) {
            case 'total-views-chart':
                config = await getViewsChartConfig(id);
                break;
        }

        if (config) {
            new Chart(canvas, config)
        } else {
            utils.log('Could not build config for chart with id: ' + id, 'warn');
        }
    }

    // returns null or the data
    async function getData(id) {
        // build formdata
        const form = new FormData();
        form.set('url', 'api/request/get-chart-data');
        form.set('chartID', id);

        // get response
        const response = await ajax.get(form);

        // check if data contains response
        if (!response['response']) {
            utils.log('Could not find response for chart with id: ' + id, 'warn');
            return null;
        }

        // build config
        return response['response'];
    }

    // returns the config for this specific chart
    async function getViewsChartConfig(id) {
        const data = await getData(id);

        if (!data || !data['clicks'] || !data['views']) {
            return null;
        }

        return {
            type: 'line',
            data: {
                datasets: [{
                    label: t('Clicks'),
                    data: JSON.parse(data['clicks']),
                    tension: 0.4
                }, {
                    label: t('Views'),
                    data: JSON.parse(data['views']),
                    tension: 0.4
                }]
            },
            config: {
                responsive: true,
            }
        }
    }

    function defaultConfig(data, title = t('Stats')) {
        return {
            type: 'line',
            data: {
                datasets: [{
                    label: title,
                    data: JSON.parse(data),
                    tension: 0.4
                }]
            },
            config: {
                responsive: true,
            }
        }
    }

    // update via js?
    $(document).on('swchanged', (e) => {
        const page = e.originalEvent.detail.page;

        if (page === 'stats') {
            init();
        }
    });

    // init all charts
    init();
});
