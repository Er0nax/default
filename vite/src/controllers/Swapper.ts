import SiteModule from "@/shared/SiteModule";
import FileHelper from "@/helpers/FileHelper";
import EventHelper from "@/helpers/EventHelper";
import RequestHelper from "@/helpers/RequestHelper";
import {Entry, SwClickEventDetails} from "@/types";
import {log} from "@/shared/Utils";

export default class Swapper {
    entry: Entry;
    container: HTMLElement;
    buttons: HTMLElement[];
    requestHelper: RequestHelper;

    constructor() {
        this.entry = SiteModule.entry;
        this.container = FileHelper.getContainer() as HTMLElement;
        this.buttons = FileHelper.getButtons();
        this.requestHelper = new RequestHelper();
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

    /**
     * Listen to swclick events on the container.
     * @private
     */
    private listen() {
        // set this instance
        this.requestHelper.swapper = this;

        // listen to swclicks
        this.container.addEventListener('swclick', (async (event: SwClickEventDetails) => {
            await this.requestHelper.setContent(event);
        }) as unknown as EventListener);
    }
}