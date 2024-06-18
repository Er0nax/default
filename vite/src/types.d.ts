export interface Site {
    baseUrl: string;
    title: string;
    lang: string;
    environment: string;
    loggedIn: boolean;
    useBootstrap: boolean;
    swapper: Swapper;
}

export interface Swapper {
    clickAttribute: string;
}

interface SwClickEventDetails extends Event {
    detail: {
        [key: string]: any;
    }
}