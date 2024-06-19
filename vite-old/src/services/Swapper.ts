import HtmlHelper from "@/helpers/HtmlHelper";
import EventHelper from "@/helpers/EventHelper";
import {SwapperResponse, SwClickEventDetails} from "@/types";
import log from "@/helpers/LogHelper";
import AxiosHelper from "@/helpers/AxiosHelper";
import {AxiosResponse} from "axios";

class Swapper {
    anchors: HTMLElement[];
    container: HTMLElement

    constructor() {
        this.anchors = HtmlHelper.getAnchors();
        this.container = HtmlHelper.getContainer();
    }

    public start() {
        // loop through all anchors
        this.anchors.forEach((anchor: HTMLElement) => {
            // add event listeners
            EventHelper.addClickEvent(anchor);
        })
    }

    public listen() {
        document.addEventListener('swclick', async (event: Event) => {
            // set content
            await this._setContent(event as SwClickEventDetails);
        })
    }

    private async _setContent(e: SwClickEventDetails) {
        // new formdata
        const formData = new FormData();
        formData.set('page', e.detail.page.page ?? 'index');
        formData.set('params', JSON.stringify(e.detail.page.params) ?? null);

        // get the response
        const response: SwapperResponse = await AxiosHelper.post('swapper/get-content', formData);

        // response contains errors?
        if (response.error) {
            log(response.response.content, 'throw');
        }

        this._setContentToContainer(response.response.content);
    }

    private _setContentToContainer(content: any) {
        this.container.innerHTML = content;

        // create new listeners for new buttons
        const anchorsInContainer = HtmlHelper.getAnchors(this.container);

        // loop through all new anchors
        anchorsInContainer.forEach((anchor: HTMLElement) => {
            // add event listeners
            EventHelper.addClickEvent(anchor);
        })
    }
}

export default new Swapper();
