type ChangeEntry = [string, string];
interface GetGlobalStylesChangesOptions {
    maxResults?: number;
}
/**
 * Returns an array of translated summarized global styles changes.
 * Results are cached using a Map() key of `JSON.stringify( { next, previous } )`.
 *
 * @param next     The changed object to compare.
 * @param previous The original object to compare against.
 * @return A 2-dimensional array of tuples: [ "group", "translated change" ].
 */
export declare function getGlobalStylesChangelist(next: any, previous: any): ChangeEntry[];
/**
 * From a getGlobalStylesChangelist() result, returns an array of translated global styles changes, grouped by type.
 * The types are 'blocks', 'elements', 'settings', and 'styles'.
 *
 * @param next     The changed object to compare.
 * @param previous The original object to compare against.
 * @param options  Options. maxResults: results to return before truncating.
 * @return An array of translated changes.
 */
export default function getGlobalStylesChanges(next: any, previous: any, options?: GetGlobalStylesChangesOptions): string[];
export {};
//# sourceMappingURL=get-global-styles-changes.d.ts.map