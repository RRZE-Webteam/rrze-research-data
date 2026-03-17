/**
 * This code is derived from the following projects:
 *
 * 1. dynamic-importmap (https://github.com/keller-mark/dynamic-importmap)
 * 2. es-module-shims (https://github.com/guybedford/es-module-shims)
 *
 * The original implementation was created by Guy Bedford in es-module-shims,
 * then adapted by Mark Keller in dynamic-importmap, and further modified
 * for use in this project.
 *
 * Both projects are licensed under the MIT license.
 *
 * MIT License: https://opensource.org/licenses/MIT
 */
/**
 * Internal dependencies
 */
import { type ModuleLoad } from './loader';
/**
 * Fetches the passed module URL and return the corresponding `ModuleLoad`
 * instance. If the passed URL does not point to a JS file, the function
 * throws and error.
 *
 * @param url       Module URL.
 * @param fetchOpts Fetch init options.
 * @param parent    Parent module URL referencing this URL (if any).
 * @return Promise with a `ModuleLoad` instance.
 */
export declare function fetchModule(url: string, fetchOpts: RequestInit, parent: string): Promise<ModuleLoad>;
//# sourceMappingURL=fetch.d.ts.map