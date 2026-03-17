/**
 * Internal dependencies
 */
import type { BlockType } from '../types';
/**
 * Determine the CSS selector for the block type and target provided, returning
 * it if available.
 *
 * @param blockType        The block's type.
 * @param target           The desired selector's target e.g. `root`, delimited string, or array path.
 * @param options          Options object.
 * @param options.fallback Whether or not to fallback to broader selector.
 *
 * @return The CSS selector or `null` if no selector available.
 */
export declare function getBlockSelector(blockType: BlockType, target?: string, options?: {
    fallback?: boolean;
}): string | null;
//# sourceMappingURL=selectors.d.ts.map