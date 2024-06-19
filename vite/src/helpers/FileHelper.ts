import SiteModule from "@/shared/SiteModule";

class FileHelper {
    containerId: string;

    constructor() {
        this.containerId = SiteModule.swapper.containerId;
    }

    public getContainer(): HTMLElement {
        return document.getElementById(this.containerId) as HTMLElement;
    }
}

export default new FileHelper();