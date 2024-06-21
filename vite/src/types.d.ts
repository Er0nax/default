export interface Site {
    baseUrl: string;
    title: string;
    lang: string;
    environment: string;
    loggedIn: boolean;
    swapper: Swapper;
    entry: Entry
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

interface SwClickEventDetails extends Event {
    detail: {
        page: string;
        params: []
    }
}

interface SwapperResponse {
    error: boolean;
    cached: boolean;
    response: {
        content: any;
        msg: string | null;
    }
}