import {Site} from '@/types';

class SiteModule {
    baseUrl: string;
    title: string;
    lang: string;
    environment: string;
    loggedIn: boolean;

    constructor(config: Site) {
        this.baseUrl = config.baseUrl;
        this.title = config.title;
        this.lang = config.lang;
        this.environment = config.environment;
        this.loggedIn = config.loggedIn;
    }
}

export default new SiteModule(window.Site);