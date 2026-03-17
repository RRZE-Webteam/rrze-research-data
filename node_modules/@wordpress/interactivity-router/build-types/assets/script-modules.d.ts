/**
 * Internal dependencies
 */
import { type ModuleLoad } from './dynamic-importmap';
/**
 * Marks the specified module as natively resolved.
 * @param url Script module URL.
 */
export declare const markScriptModuleAsResolved: (url: string) => void;
/**
 * Resolves and fetches modules present in the passed document, using the
 * document's import map to resolve them.
 *
 * @param doc Document containing the modules to preload.
 * @return Array of promises that resolve to a `ScriptModuleLoad` instance.
 */
export declare const preloadScriptModules: (doc: Document) => Promise<ModuleLoad>[];
/**
 * Imports modules respresented by the passed `ScriptModuleLoad` instances.
 *
 * @param modules Array of `MoudleLoad` instances.
 * @return Promise that resolves once all modules are imported.
 */
export declare const importScriptModules: (modules: ScriptModuleLoad[]) => Promise<unknown[]>;
export type ScriptModuleLoad = ModuleLoad;
//# sourceMappingURL=script-modules.d.ts.map