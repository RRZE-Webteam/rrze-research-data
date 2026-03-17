/**
 * Internal dependencies
 */
import type { BackgroundStyle } from '../types';
export declare const BACKGROUND_BLOCK_DEFAULT_VALUES: {
    backgroundSize: string;
    backgroundPosition: string;
};
export declare function setBackgroundStyleDefaults(backgroundStyle: BackgroundStyle): {
    backgroundPosition: string;
    backgroundSize?: undefined;
} | {
    backgroundSize: string;
    backgroundPosition?: undefined;
} | undefined;
//# sourceMappingURL=background.d.ts.map