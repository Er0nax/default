import {Site} from '@/types';

class SiteModule {
    baseUrl: string;

    constructor(config: Site) {
        this.baseUrl = config.baseUrl;
    }
}

export default new SiteModule(window.Site);