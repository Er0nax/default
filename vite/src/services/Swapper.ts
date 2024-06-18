import HtmlHelper from "@/helpers/HtmlHelper";
import EventHelper from "@/helpers/EventHelper";

class Swapper {
    anchors: HTMLElement[];

    /**
     * Constructor
     */
    constructor() {
        this.anchors = HtmlHelper.getAnchors(true);
    }

    /**
     * Start the swapper.
     */
    start() {
        // loop through all anchors
        this.anchors.forEach((anchor: HTMLElement) => {
            // add event listeners
            EventHelper.addClickEvent(anchor);
        })
    }
}

export default new Swapper();
