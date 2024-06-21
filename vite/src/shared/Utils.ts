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