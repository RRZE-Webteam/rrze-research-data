/**
 * The fluid utilities must match the backend equivalent.
 * See: gutenberg_get_typography_font_size_value() in lib/block-supports/typography.php
 * ---------------------------------------------------------------
 */
/**
 * Computes a fluid font-size value that uses clamp(). A minimum and maximum
 * font size OR a single font size can be specified.
 *
 * If a single font size is specified, it is scaled up and down using a logarithmic scale.
 *
 * @example
 * ```js
 * // Calculate fluid font-size value from a minimum and maximum value.
 * const fontSize = getComputedFluidTypographyValue( {
 *     minimumFontSize: '20px',
 *     maximumFontSize: '45px'
 * } );
 * // Calculate fluid font-size value from a single font size.
 * const fontSize = getComputedFluidTypographyValue( {
 *     fontSize: '30px',
 * } );
 * ```
 *
 * @param {Object}        args
 * @param {?string}       args.minimumViewportWidth Minimum viewport size from which type will have fluidity. Optional if fontSize is specified.
 * @param {?string}       args.maximumViewportWidth Maximum size up to which type will have fluidity. Optional if fontSize is specified.
 * @param {string|number} [args.fontSize]           Size to derive maximumFontSize and minimumFontSize from, if necessary. Optional if minimumFontSize and maximumFontSize are specified.
 * @param {?string}       args.maximumFontSize      Maximum font size for any clamp() calculation. Optional.
 * @param {?string}       args.minimumFontSize      Minimum font size for any clamp() calculation. Optional.
 * @param {?number}       args.scaleFactor          A scale factor to determine how fast a font scales within boundaries. Optional.
 * @param {?string}       args.minimumFontSizeLimit The smallest a calculated font size may be. Optional.
 *
 * @return {string|null} A font-size value using clamp().
 */
export declare function getComputedFluidTypographyValue({ minimumFontSize, maximumFontSize, fontSize, minimumViewportWidth, maximumViewportWidth, scaleFactor, minimumFontSizeLimit, }: {
    minimumFontSize?: string;
    maximumFontSize?: string;
    fontSize?: string | number;
    minimumViewportWidth?: string;
    maximumViewportWidth?: string;
    scaleFactor?: number;
    minimumFontSizeLimit?: string;
}): string | null;
/**
 * Internal method that checks a string for a unit and value and returns an array consisting of `'value'` and `'unit'`, e.g., [ '42', 'rem' ].
 * A raw font size of `value + unit` is expected. If the value is an integer, it will convert to `value + 'px'`.
 *
 * @param rawValue Raw size value from theme.json.
 * @param options  Calculation options.
 *
 * @return An object consisting of `'value'` and `'unit'` properties.
 */
export declare function getTypographyValueAndUnit(rawValue?: string | number, options?: {}): {
    value: number;
    unit: string;
} | null;
/**
 * Returns a value rounded to defined precision.
 * Returns `undefined` if the value is not a valid finite number.
 *
 * @param value  Raw value.
 * @param digits The number of digits to appear after the decimal point
 *
 * @return Value rounded to standard precision.
 */
export declare function roundToPrecision(value: number, digits?: number): number;
//# sourceMappingURL=fluid.d.ts.map