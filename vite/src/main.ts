import * as swapper from '@/services/Swapper';
//import '../dist/scss/styles.scss'
//import '../dist/css/style.css'

function init() {
    // start swapper
    swapper.init();

    // init all aos
    console.log('vite initialized.');
}

// noinspection JSIgnoredPromiseFromCall
init();