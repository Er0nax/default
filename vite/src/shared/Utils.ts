import SiteModule from "@/shared/SiteModule";

/**
 * log something to console if environment is not production and type is not throw.
 * @param value
 * @param type
 */
export function log(value: any, type: string = 'log'): boolean {

    // check invironment and type
    if (SiteModule.environment === 'production' && type !== 'throw') {
        return false;
    }

    // check if value is string
    if (typeof value === 'string') {
        value = 'Swapper' + ' - ' + value;
    }

    // check type
    switch (type) {
        case 'info':
            console.info(value);
            break;
        case 'warn':
            console.warn(value);
            break;
        case 'error':
            console.error(value);
            break;
        case 'throw':
            throw value;
        default:
            console.log(value);
    }

    // return something as we might need to end any function
    return false;
}

/**
 * Returns a new formdata with default values.
 * @param form
 */
export function getFormData(form: HTMLFormElement | null = null) {
    // create variable
    let formData;

    // html form given?
    if (form) {
        // with form
        formData = new FormData(form);
    } else {
        // wihout form
        formData = new FormData();
    }

    // return the form
    return formData;
}

export async function toast(msg: string | null, type: any = 'error') {
    await alert(msg);

    return false;
}