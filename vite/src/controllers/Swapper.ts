import SiteModule from "@/shared/SiteModule";
import FileHelper from "@/helpers/FileHelper";
import {Entry} from "@/types";

class Swapper {
    container: HTMLElement;
    entry: Entry;

    constructor() {
        this.container = FileHelper.getContainer();
        this.entry = SiteModule.entry;
    }

    public start() {
        console.log(SiteModule.entry);
    }
}

export default new Swapper();