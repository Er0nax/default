import HtmlHelper from "@/helpers/HtmlHelper";
import EventHelper from "@/helpers/EventHelper";
import {SwClickEventDetails} from "@/types";
import log from "@/helpers/LogHelper";
import AxiosHelper from "@/helpers/AxiosHelper";
import {AxiosResponse} from "axios";

class Swapper {
    anchors: HTMLElement[];

    constructor() {
        this.anchors = HtmlHelper.getAnchors(true);
    }

    /**
     * Start the swapper.
     */
    public start() {
        // loop through all anchors
        this.anchors.forEach((anchor: HTMLElement) => {
            // add event listeners
            EventHelper.addClickEvent(anchor);
        })
    }

    public listen() {
        document.addEventListener('swclick', async (event: Event) => {
            await this._setContent(event as SwClickEventDetails);
        })
    }

    private async _setContent(e: SwClickEventDetails) {
        console.log(e.detail);

        const config = {
            data: new FormData()
        }

        const response = await AxiosHelper.get('swapper/get-content', config);

        if (response?.data) {
            console.log(response.data);
        }
    }
}

export default new Swapper();
