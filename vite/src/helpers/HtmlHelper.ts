import SiteModule from '@/shared/SiteModule';
import log from "@/helpers/LogHelper";
import {SwClickEventDetails} from "@/types";

class HtmlHelper {
    clickAttribute: string;
    anchors: NodeListOf<Element>;

    constructor() {
        if (!SiteModule.swapper.clickAttribute) {
            log('Could not find any click attribute!', 'throw');
        }

        this.clickAttribute = SiteModule.swapper.clickAttribute;
        this.anchors = this._getAllAnchors();
    }

    /**
     * Returning all anchors with click class as HTMLElement[]
     * @param checkOtherAnchors
     */
    public getAnchors(checkOtherAnchors = true): HTMLElement[] {
        // create arrays
        const withClickAttribute: HTMLElement[] = [];
        const withoutClickAttribute: HTMLElement[] = [];
        const withoutHref: HTMLElement[] = [];

        // loop through all anchors
        this.anchors.forEach((anchor: Element) => {
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
    private _getAllAnchors(): NodeListOf<Element> {
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