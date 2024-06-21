import SiteModule from "@/shared/SiteModule";
import FileHelper from "@/helpers/FileHelper";
import EventHelper from "@/helpers/EventHelper";
import {Entry} from "@/types";
import {log} from "@/shared/Utils";

class Swapper {
    entry: Entry;
    container: HTMLElement;
    loadingLine: HTMLElement;
    buttons: HTMLElement[];

    constructor() {
        this.entry = SiteModule.entry;
        this.container = FileHelper.getContainer() as HTMLElement;
        this.loadingLine = FileHelper.getLoadingLine();
        this.buttons = FileHelper.getButtons();
    }

    /**
     * Start the swapper.
     */
    public start() {
        // entry given?
        if (!this.entry) {
            log('Could not find a valid entry!', 'throw');
        }

        // set events for buttons
        EventHelper.createEventsForButtons(this.buttons);

        // start listening
        this.listen();
    }

    private listen() {
        this.container.addEventListener('swclick', (event) => {
            log(event);
        })
    }
}

export default new Swapper();