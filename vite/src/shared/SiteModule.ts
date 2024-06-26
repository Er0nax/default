import {Entry, RewriteRoutes, Site, Swapper} from '@/types';

class SiteModule {
    baseUrl: string;
    title: string;
    lang: string;
    environment: string;
    loggedIn: boolean;
    swapper: Swapper;
    entry: Entry
    rewriteRoutes: RewriteRoutes

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
        this.swapper = config.swapper;
        this.entry = config.entry;
        this.rewriteRoutes = config.rewriteRoutes;
    }
}

export default new SiteModule(window.Site);