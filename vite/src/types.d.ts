export interface Site {
    baseUrl: string;
    title: string;
    lang: string;
    environment: string;
    loggedIn: boolean;
    useBootstrap: boolean;
    swapper: Swapper;
    entry: Entry
    rewriteRoutes: RewriteRoutes;
}

export interface Swapper {
    attributeName: string;
    containerId: string;
    loadingLineId: string;
}

export interface Entry {
    active: boolean;
    category: string;
    color: string;
    createdAt: string;
    headline: string;
    hideInFooter: boolean;
    hideInHeader: boolean;
    icon: string;
    id: number;
    index: number;
    isRawPage: boolean;
    mustBeLoggedIn: boolean | string;
    name: string;
    params: [];
    showAlways: boolean;
    showPreloader: boolean;
    subline: string;
    title: string;
    updatedAt: string;
}

export interface RewriteRoutes {
    [key: string]: string;
}

export interface SwClickEventDetails extends Event {
    detail: {
        page: string;
        params: []
    }
}

export interface SwapperResponse {
    error: boolean;
    cached: boolean;
    response: {
        entry: Entry;
        content: any;
        msg: string | null;
    }
}