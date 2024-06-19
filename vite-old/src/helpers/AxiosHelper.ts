import axios, {AxiosInstance, AxiosRequestConfig} from "axios";
import SiteModule from "@/shared/SiteModule";
import {SwapperResponse} from "@/types";

class AxiosHelper {
    axios: AxiosInstance;
    isLoading: boolean = false;

    constructor() {
        this.axios = axios.create({
            baseURL: SiteModule.baseUrl + 'api'
        });
    }

    public async post(url: string, data: FormData): Promise<SwapperResponse> {
        if (this.isLoading) {
            return {
                error: true,
                cached: false,
                response: {
                    content: null,
                    msg: 'There is already a axios request!'
                }
            };
        }

        this.isLoading = true;
        const response = await this.axios.post(url, data).finally(() => this.isLoading = false);

        // return as swapper response
        return response.data as unknown as SwapperResponse;
    }
}

export default new AxiosHelper();