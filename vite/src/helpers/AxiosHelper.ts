import axios, {AxiosInstance} from "axios";
import SiteModule from "@/shared/SiteModule";
import {log} from "@/shared/Utils";
import {SwapperResponse} from "@/types";

export default class AxiosHelper {
    isLoading: boolean;
    axios: AxiosInstance;

    constructor() {
        this.isLoading = false;
        this.axios = axios.create({baseURL: SiteModule.baseUrl});
    }

    async post(url: string, data: FormData, fallBack: any = null) {
        // post request already loading?
        if (this.isLoading) {
            log('Another axios request is still loading. Returning.', 'warn');
            return fallBack;
        }

        // set true
        this.isLoading = true;

        // return the response and set isLoading to false once it's done
        log(`Trying to fetch response from: ${url}`, 'info');
        const resposne = await this.axios.post(url, data, {
            validateStatus: () => true,
        }).finally(() => {
            log('Finished fetching response.', 'info');
            this.isLoading = false;
        });

        return resposne.data;
    }
}