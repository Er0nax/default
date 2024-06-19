import SiteModule from '@/shared/SiteModule';

/**
 * Log something to console and die if wanted.
 * @param value
 * @param type
 * @param style
 */
export default function log(value: any, type: string = 'log', style: string = '') {

    // check environment stage
    if (SiteModule.environment !== 'dev' && type !== 'custom' && type != 'throw') {
        return;
    }

    let text = null;
    if (typeof value === 'string' || value instanceof String) {
        // add custom text
        text = 'Swapper - ' + value;
    }

    // which type?
    switch (type) {
        case 'info':
            console.info(text ?? value);
            break;

        case 'warn':
            console.warn(text ?? value);
            break;

        case 'error':
            console.error(text ?? value);
            break;

        case 'log':
            console.log(text ?? value);
            break;

        case 'throw':
            console.error(text ?? value);
            throw '(╯°□°)╯︵ ┻━┻';

        case 'success':
            console.info(`%c${text ?? value}`, 'color: lightgreen;');
            break;

        case 'custom':
            console.log(`%c${value}`, style);
            break;

    }

    return 'something'; // as the log may need to end some functions
}