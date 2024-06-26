import FileHelper from "@/helpers/FileHelper";
import RequestHelper from "@/helpers/RequestHelper";
import {SwClickEventDetails} from "@/types";

class EventHelper {
    container: HTMLElement;

    constructor() {
        this.container = FileHelper.getContainer() as HTMLElement;
    }

    /**
     * Create new event listners for an array of buttons.
     * @param buttons
     */
    public createEventsForButtons(buttons: HTMLElement[]) {
        buttons.forEach((button) => {
            this.setClickEventListener(button);
        })
    }

    /**
     * Set custom swclick event when a button was clicked.
     * @param button
     * @private
     */
    private setClickEventListener(button: HTMLElement) {
        button.addEventListener('click', (event) => {
            // prevent defaults
            event.preventDefault();

            if (event.target) {
                // get the buttons attributes
                const attributes = this.getButtonAttributes(event.target as HTMLElement);

                // dispatch a new custom event on the container.
                this.container.dispatchEvent(new CustomEvent("swclick", {
                    detail: attributes,
                    bubbles: true,
                    cancelable: true,
                    composed: false,
                }))
            }
        })
    }

    /**
     * Returns an object of attributes for a button.
     * @param button
     * @private
     */
    private getButtonAttributes(button: HTMLElement) {
        const dataset = button.dataset;
        const dataObject: { [key: string]: any } = {};

        for (const key in dataset) {
            if (dataset.hasOwnProperty(key)) {
                dataObject[key] = dataset[key];
            }
        }

        const pageParts = dataObject.page?.split('/') || [];
        const page = pageParts.shift();
        const params = pageParts;

        dataObject.page = page ?? 'index';
        dataObject.params = params ?? [];

        return dataObject;
    }
}

export default new EventHelper();