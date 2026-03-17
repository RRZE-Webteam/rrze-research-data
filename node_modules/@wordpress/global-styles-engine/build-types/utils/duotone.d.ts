/**
 * Convert a list of colors to an object of R, G, and B values.
 *
 * @param colors Array of RBG color strings.
 *
 * @return R, G, and B values.
 */
export declare function getValuesFromColors(colors?: string[]): {
    r: number[];
    g: number[];
    b: number[];
    a: number[];
};
/**
 * Stylesheet for disabling a global styles duotone filter.
 *
 * @param selector Selector to disable the filter for.
 *
 * @return Filter none style.
 */
export declare function getDuotoneUnsetStylesheet(selector: string): string;
/**
 * SVG and stylesheet needed for rendering the duotone filter.
 *
 * @param {string} selector Selector to apply the filter to.
 * @param {string} id       Unique id for this duotone filter.
 *
 * @return {string} Duotone filter style.
 */
export declare function getDuotoneStylesheet(selector: string, id: string): string;
/**
 * The SVG part of the duotone filter.
 *
 * @param id     Unique id for this duotone filter.
 * @param colors Color strings from dark to light.
 *
 * @return Duotone SVG.
 */
export declare function getDuotoneFilter(id: string, colors: string[]): string;
//# sourceMappingURL=duotone.d.ts.map