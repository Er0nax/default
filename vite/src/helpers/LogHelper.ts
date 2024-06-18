import SiteModule from '@/shared/SiteModule';

/**
 * Log something to console and die if wanted.
 * @param value
 * @param type
 * @param die
 */
export default function log(value: string, type: string = 'info', die: boolean = false) {

    // check environment stage
    if (SiteModule.environment !== 'dev') {
        return;
    }

    // add custom text
    const text = 'Swapper - ' + value;

    // which type?
    switch (type) {
        case 'info':
            console.info(text);
            break;
        case 'warn':
            console.warn(text);
            break;
        case 'error':
            console.error(text);
            break;
        case 'throw':
            throw text;
        default:
            console.log(text);
    }

    // exit code after log?
    if (die) {
        throw 'dead bro (╯°□°)╯︵ ┻━┻';
    }

    return 'something'; // as the log may need to end some functions
}