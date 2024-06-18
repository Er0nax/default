import axios, {AxiosInstance, AxiosRequestConfig} from "axios";
import SiteModule from "@/shared/SiteModule";

class AxiosHelper {
    axios: AxiosInstance;
    isLoading: boolean = false;

    constructor() {
        this.axios = axios.create({
            baseURL: SiteModule.baseUrl + 'api'
        });
    }

    public async get(url: string, config: AxiosRequestConfig) {
        if (this.isLoading) {
            return;
        }

        this.isLoading = true;
        return await this.axios.get(url, config).finally(() => this.isLoading = false);
    }
}

export default new AxiosHelper();