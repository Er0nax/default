import * as ajax from './ajax.js';
import {t} from './translation.js';

$(document).ready(function () {
    const loginFormData = new FormData();

    // define buttons
    const hiddenClickBtn = $('#hidden-click-element');

    // on click
    $(document).on('click', '#btnLogin', async (e) => {
        e.preventDefault();

        // start logic
        await onSubmit();
    });

    // on enter button
    $(document).on('keyup', '#inputLoginUsername, #inputLoginPassword', async (event) => {
        if (event.keyCode === 13) {
            $('#btnLogin').click();
        }
    });

    // when the submit button was pressed / enter key
    async function onSubmit() {
        // get inputs
        const username = await checkUsername($('#inputLoginUsername').val());
        if (!username) {
            return;
        }

        const password = await checkPassword($('#inputLoginPassword').val());
        if (!password) {
            return;
        }

        // set values
        loginFormData.set('url', 'api/request/login')
        loginFormData.set('username', username);
        loginFormData.set('password', password);

        // await response from server
        const response = await ajax.get(loginFormData);

        // has error?
        if (response['error']) {
            let message = response['response'];

            if (message['message']) {
                message = message['message'];
            }

            return await showSwal(t('Login'), message, 'error');
        }

        // show success swal
        await showSwal(t('Login'), response['response']['message'], 'success');

        // redirect to home
        window.location.replace(window['baseUrl'] + 'profile')
        //hiddenClickBtn.attr('data-page', 'profile/' + response['response']['user']['id']);
        //hiddenClickBtn[0].click();
    }

    // check username
    async function checkUsername(username) {
        // username at least 5 characters?
        if (!username || username.length < 3) {
            return await showSwal(t('Error'), t('Your username must contain at least {min} characters!', {min: 3}));
        }

        return username;
    }

    // check password
    async function checkPassword(password) {
        // password at least 5 characters?
        if (!password || password.length < 5) {
            return await showSwal(t('Error'), t('Your password must contain at least {min} characters!', {min: 5}))
        }

        return password;
    }

    // show error swal
    async function showSwal(title, text, icon = 'error') {
        // icons: warning, error, success, info, and question
        await Swal.fire({
            icon: icon,
            title: title,
            text: text,
            background: '#0c0c0c',
            color: '#fff',
        });

        return false;
    }
});