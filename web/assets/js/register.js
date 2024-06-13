import {showSwal} from "./utils.js";
import * as ajax from './ajax.js';
import {t} from './translation.js';

$(document).ready(function () {
    const registerFormData = new FormData();

    // define buttons
    const hiddenClickBtn = $('#hidden-click-element');

    // on click
    $(document).on('click', '#btnRegister', async (e) => {
        e.preventDefault();

        // start logic
        await onSubmit();
    });

    // on enter button
    $(document).on('keyup', '#inputRegisterUsername, #inputRegisterPassword, #inputRegisterPasswordRepeat', async (event) => {
        if (event.keyCode === 13) {
            $('#btnRegister').click();
        }
    });

    // when the submit button was pressed / enter key
    async function onSubmit() {
        // get inputs
        const username = await checkUsername($('#inputRegisterUsername').val());
        if (!username) {
            return;
        }

        const password = await checkPassword($('#inputRegisterPassword').val());
        if (!password) {
            return;
        }

        const passwordRepeat = await checkPasswordRepeat($('#inputRegisterPasswordRepeat').val());
        if (!passwordRepeat) {
            return;
        }

        // password and password repeat same?
        if (password !== passwordRepeat) {
            await showSwal(t('Register'), t('Both passwords must be the same!'));
            return;
        }

        // set values
        registerFormData.set('url', 'api/request/register')
        registerFormData.set('username', username);
        registerFormData.set('password', password);
        registerFormData.set('passwordRepeat', passwordRepeat);

        // await response from server
        const response = await ajax.get(registerFormData);

        // has error?
        if (response['error']) {
            let message = response['response'];

            if (message['message']) {
                message = message['message'];
            }

            return await showSwal(t('Register'), message);
        }

        // show success swal
        await showSwal(t('Register'), response['response']['message'], 'success');

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

    // check repeated password
    async function checkPasswordRepeat(password) {
        // password at least 5 characters?
        if (!password || password.length < 5) {
            return await showSwal(t('Error'), t('Your repeated password must contain at least {min} characters!', {min: 5}))
        }

        return password;
    }
});