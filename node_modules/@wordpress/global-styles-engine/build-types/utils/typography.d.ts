/**
 * Internal dependencies
 */
import type { TypographyPreset, GlobalStylesSettings, FluidTypographySettings } from '../types';
/**
 * Returns fluid typography settings from theme.json setting object.
 *
 * @param settings            Theme.json settings
 * @param settings.typography Theme.json typography settings
 * @param settings.layout     Theme.json layout settings
 * @return Fluid typography settings
 */
export declare function getFluidTypographyOptionsFromSettings(settings: GlobalStylesSettings): {
    fluid?: FluidTypographySettings | boolean | undefined;
};
/**
 * Returns a font-size value based on a given font-size preset.
 * Takes into account fluid typography parameters and attempts to return a css formula depending on available, valid values.
 *
 * The Core PHP equivalent is wp_get_typography_font_size_value().
 *
 * @param preset   A typography preset object containing size and fluid properties.
 * @param settings Global styles settings object containing typography and layout settings.
 *
 * @return A font-size value or the value of preset.size.
 */
export declare function getTypographyFontSizeValue(preset: TypographyPreset, settings: GlobalStylesSettings): string | number | null;
//# sourceMappingURL=typography.d.ts.map