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
 * Extends the internal dynamic import map with the passed one.
 *
 * @param importMapIn         Import map.
 * @param importMapIn.imports Imports declaration.
 * @param importMapIn.scopes  Scopes declaration.
 */
export declare function addImportMap(importMapIn: {
    imports?: Record<string, string>;
    scopes?: Record<string, any>;
}): void;
/**
 * Resolves the URL of the passed module ID against the current internal
 * dynamic import map.
 *
 * @param id        Module ID.
 * @param parentUrl Parent URL, in case the module ID is relative.
 * @return Resolved module URL.
 */
export declare function resolve(id: string, parentUrl: string): string;
//# sourceMappingURL=resolver.d.ts.map