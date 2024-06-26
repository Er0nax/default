import FileHelper from "@/helpers/FileHelper";
import {SwapperResponse, SwClickEventDetails} from "@/types";
import {getFormData, log, toast} from "@/shared/Utils";
import AxiosHelper from "@/helpers/AxiosHelper";
import EventHelper from "@/helpers/EventHelper";
import SiteModule from "@/shared/SiteModule";

export default class RequestHelper {
    swapper: any;
    axiosHelper: AxiosHelper;
    loadingLine: HTMLElement;

    constructor() {
        this.loadingLine = FileHelper.getLoadingLine();
        this.axiosHelper = new AxiosHelper();
        this.listenToBrowserBack();
    }

    /**
     * update view (ui, title, url, ...)
     * @param event
     */
    public async setContent(event: SwClickEventDetails) {
        this.loading();

        // is trying to view the same page?
        if (this.swapper.entry.name !== event.detail.page && this.swapper.entry.params !== event.detail.params) {

            // set entry values with event values
            this.swapper.entry.name = event.detail.page;
            this.swapper.entry.params = event.detail.params;

            // try to set content to container
            if (!await this.setContentToContainer()) {
                log('Could not update UI inside container!', 'warn');
            }

            // new event listeners for buttons
            this.setEventListenersForContainer();

            // update title
            document.title = this.getTitle();

            // update url
            this.updateUrl();
        }

        // hide loading line
        this.loading(false);
    }

    /**
     * update the loading line
     * @private
     */
    private updateUrl() {
        let url: string = SiteModule.baseUrl + this.swapper.entry.name;

        if (this.swapper.entry.params.length > 1) {
            url += '/' + this.swapper.entry.params.join('/');
        }

        const data = {
            'title': this.getTitle(),
            'entry': this.swapper.entry,
            'html': this.swapper.container.innerHTML
        }

        window.history.pushState(data, '', url);
    }

    /**
     * update the title
     * @private
     */
    private getTitle() {
        return this.swapper.entry.title + ' | ' + SiteModule.title;
    }

    /**
     * Add the event listners for new buttons inside the container after UI update.
     * @private
     */
    private setEventListenersForContainer() {
        // get buttons
        this.swapper.buttons = FileHelper.getButtons(this.swapper.container);

        // add listeners for buttons
        EventHelper.createEventsForButtons(this.swapper.buttons);
    }

    /**
     * Set the content of the response to the innerhtml of the container
     * @private
     */
    private async setContentToContainer() {
        // new formdata
        const data = getFormData();
        data.set('page', this.getPageFromRewriteRoutes(this.swapper.entry.name));
        data.set('params', JSON.stringify(this.swapper.entry.params));

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
     * checks if a page should be rewritten
     * @param value
     * @private
     */
    private getPageFromRewriteRoutes(value: string = 'index') {
        if (SiteModule.rewriteRoutes[value]) {
            return SiteModule.rewriteRoutes[value];
        }

        return value;
    }

    /**
     * listen to browsers back/forwars events
     * @private
     */
    private listenToBrowserBack() {
        window.addEventListener('popstate', async (e) => {
            if (e.state) {
                this.loading();

                document.title = e.state.title;
                this.swapper.entry = e.state.entry;
                this.swapper.container.innerHTML = e.state.html;

                this.loading(false);
            }
        });
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