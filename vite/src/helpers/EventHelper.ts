import HtmlHelper from "@/helpers/HtmlHelper";

class EventHelper {
    constructor() {
        console.log('new event helper');
    }

    addClickEvent(element: HTMLElement) {
        const details = HtmlHelper.getDetailsFromElement(element);

        console.log(details);
    }
}

export default new EventHelper();