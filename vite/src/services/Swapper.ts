import t from '@/helpers/translation';
import SiteModule from '@/services/SiteModule';

export function init() {

    console.log(SiteModule);

    return t('Hello World');
}