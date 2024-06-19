import SiteModule from '@/shared/SiteModule';
import log from "@/helpers/LogHelper";
import {SwClickEventDetails} from "@/types";

class HtmlHelper {
    clickAttribute: string;
    anchors: NodeListOf<Element> | [];

    constructor() {
        if (!SiteModule.swapper.clickAttribute) {
            log('Could not find any click attribute!', 'throw');
        }

        this.clickAttribute = SiteModule.swapper.clickAttribute;
        this.anchors = this._getAllAnchors();
    }

    /**
     * Returning all anchors with click class as HTMLElement[]
     * @param container
     */
    public getAnchors(container: HTMLElement | null = null): HTMLElement[] {
        const anchors = this._getAllAnchors(container);
        return this._getFilteredAnchors(anchors);
    }

    private _getFilteredAnchors(anchors: NodeListOf<Element>, checkOtherAnchors: boolean = false) {
        // create arrays
        const withClickAttribute: HTMLElement[] = [];
        const withoutClickAttribute: HTMLElement[] = [];
        const withoutHref: HTMLElement[] = [];

        // loop through all anchors
        anchors.forEach((anchor: Element) => {
            if (anchor.hasAttribute(`data-${this.clickAttribute}`)) {
                // anchor has click class
                withClickAttribute.push(anchor as HTMLElement);
            } else {
                // anchor does not have click class
                withoutClickAttribute.push(anchor as HTMLElement);
            }

            // does have href?
            if (anchor.getAttribute('href') === null) {
                withoutHref.push(anchor as HTMLElement);
            }
        })

        // should check other anchors?
        if (checkOtherAnchors) {
            this._checkOtherAnchors(withoutClickAttribute, withoutHref);
        }

        // return only anchors with click
        return withClickAttribute;
    }

    /**
     * Returns object of all data-attributes from an element.
     * @param element
     */
    public getDetailsFromElement(element: HTMLElement): SwClickEventDetails {
        const dataAttributes: { [key: string]: any } = {};

        // loop through data-attributes
        for (const key in element.dataset) {

            // its own property?
            if (element.dataset.hasOwnProperty(key)) {

                // is page?
                if (key === 'page') {
                    dataAttributes[key] = this._parsePageAttribute(element.dataset[key]!);
                } else {
                    dataAttributes[key] = element.dataset[key]!;
                }
            }
        }

        return dataAttributes as SwClickEventDetails;
    }

    /**
     * Returns the container as a htmlelement.
     */
    public getContainer(): HTMLElement {
        const containerID = SiteModule.swapper.containerID;
        if (!containerID) {
            log('Could not find any containerID', 'throw');
        }

        const container = document.getElementById(containerID);
        if (!container) {
            log(`Could not find any container with ID: "${containerID}"`, 'throw');
        }

        return container as HTMLElement;
    }

    /**
     * parses a string by /
     * @param attribute
     * @private
     */
    private _parsePageAttribute(attribute: string): { page: string, params: string[] } {
        const parts = attribute.split('/');
        const page = parts[0];
        const params = parts.slice(1);

        return {
            page,
            params
        };
    }

    /**
     * Returning all anchors as NodeListOf<Element>
     * @private
     */
    private _getAllAnchors(container: HTMLElement | null = null): NodeListOf<Element> {
        if (container) {
            return container.querySelectorAll('a');
        }

        return document.querySelectorAll('a');
    }

    /**
     * Logs anchors without clickclass and without href if existed.
     * @param withoutClickAttribute
     * @param withoutHref
     * @private
     */
    private _checkOtherAnchors(withoutClickAttribute: HTMLElement[], withoutHref: HTMLElement[]) {

        // without click class found?
        if (withoutClickAttribute.length !== 0) {
            log('Found anchors without click attribute!', 'warn');
            log(withoutClickAttribute, 'warn');
        }

        // without href?
        if (withoutHref.length !== 0) {
            log('Found anchors without href!', 'warn');
            log(withoutHref, 'warn');
        }
    }
}

export default new HtmlHelper();