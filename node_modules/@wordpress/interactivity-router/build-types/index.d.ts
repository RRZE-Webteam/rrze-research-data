export interface NavigateOptions {
    force?: boolean;
    html?: string;
    replace?: boolean;
    timeout?: number;
    loadingAnimation?: boolean;
    screenReaderAnnouncement?: boolean;
}
export interface PrefetchOptions {
    force?: boolean;
    html?: string;
}
export declare const state: {
    url: string;
    navigation: {
        hasStarted: boolean;
        hasFinished: boolean;
    };
}, actions: {
    navigate: (href: string, options?: NavigateOptions) => Promise<void>;
    prefetch: (url: string, options?: PrefetchOptions) => Promise<void>;
};
//# sourceMappingURL=index.d.ts.map