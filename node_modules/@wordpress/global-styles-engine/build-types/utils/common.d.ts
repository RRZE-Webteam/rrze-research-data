/**
 * Internal dependencies
 */
import type { GlobalStylesSettings, ThemeFileLink, TypographyPreset, UnresolvedValue, GlobalStylesConfig } from '../types';
export declare const ROOT_BLOCK_SELECTOR = "body";
export declare const ROOT_CSS_PROPERTIES_SELECTOR = ":root";
export declare const PRESET_METADATA: ({
    path: string[];
    valueKey: string;
    cssVarInfix: string;
    classes: {
        classSuffix: string;
        propertyName: string;
    }[];
    valueFunc?: undefined;
} | {
    path: string[];
    valueFunc: (preset: TypographyPreset, settings: GlobalStylesSettings) => string | number | null;
    valueKey: string;
    cssVarInfix: string;
    classes: {
        classSuffix: string;
        propertyName: string;
    }[];
} | {
    path: string[];
    valueKey: string;
    cssVarInfix: string;
    valueFunc: ({ size }: {
        size: string;
    }) => string;
    classes: never[];
})[];
export declare const STYLE_PATH_TO_CSS_VAR_INFIX: Record<string, string>;
/**
 * Function that scopes a selector with another one. This works a bit like
 * SCSS nesting except the `&` operator isn't supported.
 *
 * @example
 * ```js
 * const scope = '.a, .b .c';
 * const selector = '> .x, .y';
 * const merged = scopeSelector( scope, selector );
 * // merged is '.a > .x, .a .y, .b .c > .x, .b .c .y'
 * ```
 *
 * @param scope    Selector to scope to.
 * @param selector Original selector.
 *
 * @return Scoped selector.
 */
export declare function scopeSelector(scope: string | undefined, selector: string): string;
/**
 * Scopes a collection of selectors for features and subfeatures.
 *
 * @example
 * ```js
 * const scope = '.custom-scope';
 * const selectors = {
 *     color: '.wp-my-block p',
 *     typography: { fontSize: '.wp-my-block caption' },
 * };
 * const result = scopeFeatureSelector( scope, selectors );
 * // result is {
 * //     color: '.custom-scope .wp-my-block p',
 * //     typography: { fonSize: '.custom-scope .wp-my-block caption' },
 * // }
 * ```
 *
 * @param scope     Selector to scope collection of selectors with.
 * @param selectors Collection of feature selectors e.g.
 *
 * @return Scoped collection of feature selectors.
 */
export declare function scopeFeatureSelectors(scope: string | undefined, selectors: string | Record<string, string | Record<string, string>>): Record<string, string | Record<string, string>> | undefined;
/**
 * Appends a sub-selector to an existing one.
 *
 * Given the compounded `selector` "h1, h2, h3"
 * and the `toAppend` selector ".some-class" the result will be
 * "h1.some-class, h2.some-class, h3.some-class".
 *
 * @param selector Original selector.
 * @param toAppend Selector to append.
 *
 * @return The new selector.
 */
export declare function appendToSelector(selector: string, toAppend: string): string;
/**
 * Generates the selector for a block style variation by creating the
 * appropriate CSS class and adding it to the ancestor portion of the block's
 * selector.
 *
 * For example, take the Button block which has a compound selector:
 * `.wp-block-button .wp-block-button__link`. With a variation named 'custom',
 * the class `.is-style-custom` should be added to the `.wp-block-button`
 * ancestor only.
 *
 * This function will take into account comma separated and complex selectors.
 *
 * @param variation     Name for the variation.
 * @param blockSelector CSS selector for the block.
 *
 * @return CSS selector for the block style variation.
 */
export declare function getBlockStyleVariationSelector(variation: string, blockSelector: string): string;
/**
 * Resolves ref values in theme JSON.
 *
 * @param ruleValue A block style value that may contain a reference to a theme.json value.
 * @param tree      A theme.json object.
 * @return The resolved value or incoming ruleValue.
 */
export declare function getResolvedRefValue(ruleValue: UnresolvedValue, tree?: GlobalStylesConfig): UnresolvedValue;
/**
 * Looks up a theme file URI based on a relative path.
 *
 * @param file          A relative path.
 * @param themeFileURIs A collection of absolute theme file URIs and their corresponding file paths.
 * @return A resolved theme file URI, if one is found in the themeFileURIs collection.
 */
export declare function getResolvedThemeFilePath(file: string, themeFileURIs?: ThemeFileLink[]): string;
/**
 * Resolves ref and relative path values in theme JSON.
 *
 * @param ruleValue A block style value that may contain a reference to a theme.json value.
 * @param tree      A theme.json object.
 * @return The resolved value or incoming ruleValue.
 */
export declare function getResolvedValue(ruleValue: UnresolvedValue, tree: GlobalStylesConfig | undefined): UnresolvedValue;
/**
 * Attempts to fetch the value of a theme.json CSS variable.
 *
 * This function resolves CSS variable references in two formats:
 * - User format: `var:preset|color|red` or `var:custom|spacing|small`
 * - Theme format: `var(--wp--preset--color--red)` or `var(--wp--custom--spacing--small)`
 *
 * It also handles ref-style variables in the format `{ ref: "path.to.value" }`.
 *
 * @param features  GlobalStylesContext config (user, base, or merged). Represents the theme.json tree.
 * @param blockName The name of a block as represented in the styles property. E.g., 'root' for root-level, and 'core/block-name' for blocks.
 * @param variable  An incoming style value. A CSS var value is expected, but it could be any value.
 * @return The value of the CSS var, if found. If not found, returns the original variable argument.
 */
export declare function getValueFromVariable(features: GlobalStylesConfig, blockName?: string, variable?: string | UnresolvedValue): any;
/**
 * Encodes a value to a preset variable format if it matches a preset.
 * This is the inverse operation of getValueFromVariable().
 *
 * @example
 * ```js
 * const presetVar = getPresetVariableFromValue(
 *     globalStyles.settings,
 *     'core/paragraph',
 *     'color.text',
 *     '#ff0000'
 * );
 * // If #ff0000 is the 'red' preset color, returns 'var:preset|color|red'
 * // Otherwise returns '#ff0000'
 * ```
 *
 * @param features            GlobalStylesContext settings object.
 * @param blockName           The name of a block (e.g., 'core/paragraph').
 * @param variableStylePath   The style path (e.g., 'color.text', 'typography.fontSize').
 * @param presetPropertyValue The value to encode (e.g., '#ff0000').
 * @return The preset variable if found, otherwise the original value.
 */
export declare function getPresetVariableFromValue(features: GlobalStylesSettings, blockName: string | undefined, variableStylePath: string, presetPropertyValue: any): any;
//# sourceMappingURL=common.d.ts.map