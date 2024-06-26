import FileHelper from "@/helpers/FileHelper";
import {SwapperResponse, SwClickEventDetails} from "@/types";
import {getFormData, log, toast} from "@/shared/Utils";
import AxiosHelper from "@/helpers/AxiosHelper";
import EventHelper from "@/helpers/EventHelper";

export default class RequestHelper {
    swapper: any;
    axiosHelper: AxiosHelper;
    loadingLine: HTMLElement;

    constructor() {
        this.loadingLine = FileHelper.getLoadingLine();
        this.axiosHelper = new AxiosHelper();
    }

    /**
     * update view (ui, title, url, ...)
     * @param event
     */
    public async setContent(event: SwClickEventDetails) {
        this.loading();

        // try to set content to container
        if (!await this.setContentToContainer(event)) {
            log('Could not update UI inside container!', 'warn');
        }

        // new event listeners for buttons
        this.setEventListenersForContainer();

        console.log(this.swapper.entry);

        this.loading(false);
    }

    /**
     * Add the event listners for new buttons inside the container after UI update.
     * @private
     */
    private setEventListenersForContainer(){
        // get buttons
        this.swapper.buttons = FileHelper.getButtons(this.swapper.container);

        // add listeners for buttons
        EventHelper.createEventsForButtons(this.swapper.buttons);
    }

    /**
     * Set the content of the response to the innerhtml of the container
     * @param event
     * @private
     */
    private async setContentToContainer(event: SwClickEventDetails) {

        // new formdata
        const data = getFormData();
        data.set('page', event.detail.page);
        data.set('params', JSON.stringify(event.detail.params));

        // get the response
        const response = await this.axiosHelper.post('api/swapper/get-content', data) as SwapperResponse;
        this.swapper.entry = response.response.entry;

        // has error?
        if (response.error) {
            return await toast(response.response.msg);
        }

        // set content
        this.swapper.container.innerHTML = response.response.content;

        // return true
        return true;
    }

    /**
     * Set the display mode of the loading line.
     * @param set
     * @private
     */
    private loading(set: boolean = true) {
        if (set) {
            this.loadingLine.style.display = 'block';
        } else {
            this.loadingLine.style.display = 'none';
        }
    }
}