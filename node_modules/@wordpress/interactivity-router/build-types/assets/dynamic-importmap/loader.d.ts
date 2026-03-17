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
 * External dependencies
 */
import * as lexer from 'es-module-lexer';
export interface ModuleLoad {
    url?: string;
    responseUrl?: string;
    fetchPromise?: Promise<ModuleLoad>;
    source?: string;
    linkPromise?: Promise<void>;
    analysis?: ReturnType<typeof lexer.parse>;
    deps?: ModuleLoad[];
    blobUrl?: string;
    shellUrl?: string;
}
export declare const initPromise: Promise<void>;
/**
 * Data from the initial page's import map.
 *
 * Pages containing any of the imports present on the original page
 * in their import maps should ignore them, as those imports would
 * be handled natively.
 */
export declare const initialImportMap: any;
export declare const registry: {};
/**
 * Resolves the passed module URL and fetches the corresponding module
 * and their dependencies, returning a `ModuleLoad` object once all
 * of them have been fetched.
 *
 * @param url       Module URL.
 * @param fetchOpts Fetch options.
 * @return A promise with a `ModuleLoad` instance.
 */
export declare function preloadModule(url: string, fetchOpts?: RequestInit): Promise<ModuleLoad>;
/**
 * Imports the module represented by the passed `ModuleLoad` instance.
 *
 * @param load The `ModuleLoad` instance representing the module.
 * @return A promise with the imported module.
 */
export declare function importPreloadedModule<Module = unknown>(load: ModuleLoad): Promise<Module>;
/**
 * Imports the module represented by the passed module URL.
 *
 * The module URL and all its dependencies are resolved using the
 * current status of the internal dynamic import map.
 *
 * @param url       Module URL.
 * @param fetchOpts Fetch options.
 * @return A promise with the imported module.
 */
export declare function topLevelLoad<Module = unknown>(url: string, fetchOpts?: RequestInit): Promise<Module>;
//# sourceMappingURL=loader.d.ts.map