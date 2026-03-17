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
type ImportMap = {
    imports?: Record<string, string>;
    scopes?: Record<string, Record<string, string>>;
};
/**
 * Imports the module with the passed ID.
 *
 * The module is resolved against the internal dynamic import map,
 * extended with the passed import map.
 *
 * @param id          Module ID.
 * @param importMapIn Import map.
 * @return Resolved module.
 */
export declare function importWithMap<Module = unknown>(id: string, importMapIn: ImportMap): Promise<Module>;
/**
 * Preloads the module with the passed ID along with its dependencies.
 *
 * The module is resolved against the internal dynamic import map,
 * extended with the passed import map.
 *
 * @param id          Module ID.
 * @param importMapIn Import map.
 * @return Resolved `ModuleLoad` instance.
 */
export declare function preloadWithMap(id: string, importMapIn: ImportMap): Promise<import("./loader").ModuleLoad>;
export { initialImportMap, importPreloadedModule, type ModuleLoad, } from './loader';
//# sourceMappingURL=index.d.ts.map