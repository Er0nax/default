import SiteModule from '@/shared/SiteModule';
import log from "@/helpers/LogHelper";

class HtmlHelper {
    clickAttribute: string;
    anchors: NodeListOf<Element>;

    constructor() {
        if (!SiteModule.swapper.clickAttribute) {
            log('Could not find any attribute for the swapper!', 'throw');
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
        const withAttribute: HTMLElement[] = [];
        const withoutAttribute: HTMLElement[] = [];
        const withoutHref: HTMLElement[] = [];

        // loop through all anchors
        this.anchors.forEach((anchor: Element) => {
            if (anchor.hasAttribute(this.clickAttribute)) {
                // anchor has click class
                withAttribute.push(anchor as HTMLElement);
            } else {
                // anchor does not have click class
                withoutAttribute.push(anchor as HTMLElement);
            }

            // does have href?
            if (anchor.getAttribute('href') === null) {
                withoutHref.push(anchor as HTMLElement);
            }
        })

        // should check other anchors?
        if (checkOtherAnchors) {
            this._checkOtherAnchors(withoutAttribute, withoutHref);
        }

        // return only anchors with click
        return withAttribute;
    }

    /**
     * Returns object of all data-attributes from an element.
     * @param element
     */
    public getDetailsFromElement(element: HTMLElement): { [key: string]: any } {
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

        return dataAttributes;
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
     * @param withoutAttribute
     * @param withoutHref
     * @private
     */
    private _checkOtherAnchors(withoutAttribute: HTMLElement[], withoutHref: HTMLElement[]) {

        // without click class found?
        if (withoutAttribute.length !== 0) {
            log('Found anchors without click attribute!', 'warn');
            console.log(withoutAttribute);
        }

        // without href?
        if (withoutHref.length !== 0) {
            log('Found anchors without href!', 'warn');
            console.log(withoutHref);
        }
    }
}

export default new HtmlHelper();