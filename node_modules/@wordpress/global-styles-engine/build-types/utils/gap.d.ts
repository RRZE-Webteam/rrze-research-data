/**
 * Returns a BoxControl object value from a given blockGap style value.
 * The string check is for backwards compatibility before Gutenberg supported
 * split gap values (row and column) and the value was a string n + unit.
 *
 * @param blockGapValue A block gap string or axial object value, e.g., '10px' or { top: '10px', left: '10px'}.
 * @return A value to pass to the BoxControl component.
 */
export declare function getGapBoxControlValueFromStyle(blockGapValue?: string | {
    top: string;
    left: string;
}): {
    top: string;
    left: string;
} | null;
/**
 * Returns a CSS value for the `gap` property from a given blockGap style.
 *
 * @param blockGapValue A block gap string or axial object value, e.g., '10px' or { top: '10px', left: '10px'}.
 * @param defaultValue  A default gap value.
 * @return The concatenated gap value (row and column).
 */
export declare function getGapCSSValue(blockGapValue?: string | {
    top: string;
    left: string;
}, defaultValue?: string): string | null;
//# sourceMappingURL=gap.d.ts.map