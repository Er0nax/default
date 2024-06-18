import HtmlHelper from "@/helpers/HtmlHelper";
import log from "@/helpers/LogHelper";
import {SwClickEventDetails} from "@/types";

class EventHelper {

    constructor() {
        log('new event helper');
    }

    /**
     * Add a custom click event for each data-page click.
     * @param element
     */
    addClickEvent(element: HTMLElement) {
        const details: SwClickEventDetails = HtmlHelper.getDetailsFromElement(element);

        element.addEventListener('click', (e) => {
            e.preventDefault();

            document.dispatchEvent(new CustomEvent("swclick", {
                detail: details,
                bubbles: true,
                cancelable: true,
                composed: false,
            }));
        })
    }
}

export default new EventHelper();