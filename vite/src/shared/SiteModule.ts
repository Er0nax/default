import {Site, Swapper} from '@/types';

class SiteModule {
    baseUrl: string;
    title: string;
    lang: string;
    environment: string;
    loggedIn: boolean;
    useBootstrap: boolean;
    swapper: Swapper;

    /**
     * get variables from siteassetbundle and make them global.
     * @param config
     */
    constructor(config: Site) {
        this.baseUrl = config.baseUrl;
        this.title = config.title;
        this.lang = config.lang;
        this.environment = config.environment;
        this.loggedIn = config.loggedIn;
        this.useBootstrap = config.useBootstrap;
        this.swapper = config.swapper;
    }
}

export default new SiteModule(window.Site);