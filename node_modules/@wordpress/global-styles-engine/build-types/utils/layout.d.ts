export declare const LAYOUT_DEFINITIONS: {
    default: {
        name: string;
        slug: string;
        className: string;
        baseStyles: ({
            selector: string;
            rules: {
                float: string;
                'margin-inline-start': string;
                'margin-inline-end': string;
                'margin-left'?: undefined;
                'margin-right'?: undefined;
            };
        } | {
            selector: string;
            rules: {
                'margin-left': string;
                'margin-right': string;
                float?: undefined;
                'margin-inline-start'?: undefined;
                'margin-inline-end'?: undefined;
            };
        })[];
        spacingStyles: ({
            selector: string;
            rules: {
                'margin-block-start': string;
                'margin-block-end'?: undefined;
            };
        } | {
            selector: string;
            rules: {
                'margin-block-end': string;
                'margin-block-start'?: undefined;
            };
        } | {
            selector: string;
            rules: {
                'margin-block-start': null;
                'margin-block-end': string;
            };
        })[];
    };
    constrained: {
        name: string;
        slug: string;
        className: string;
        baseStyles: ({
            selector: string;
            rules: {
                float: string;
                'margin-inline-start': string;
                'margin-inline-end': string;
                'margin-left'?: undefined;
                'margin-right'?: undefined;
                'max-width'?: undefined;
            };
        } | {
            selector: string;
            rules: {
                'margin-left': string;
                'margin-right': string;
                float?: undefined;
                'margin-inline-start'?: undefined;
                'margin-inline-end'?: undefined;
                'max-width'?: undefined;
            };
        } | {
            selector: string;
            rules: {
                'max-width': string;
                'margin-left': string;
                'margin-right': string;
                float?: undefined;
                'margin-inline-start'?: undefined;
                'margin-inline-end'?: undefined;
            };
        } | {
            selector: string;
            rules: {
                'max-width': string;
                float?: undefined;
                'margin-inline-start'?: undefined;
                'margin-inline-end'?: undefined;
                'margin-left'?: undefined;
                'margin-right'?: undefined;
            };
        })[];
        spacingStyles: ({
            selector: string;
            rules: {
                'margin-block-start': string;
                'margin-block-end'?: undefined;
            };
        } | {
            selector: string;
            rules: {
                'margin-block-end': string;
                'margin-block-start'?: undefined;
            };
        } | {
            selector: string;
            rules: {
                'margin-block-start': null;
                'margin-block-end': string;
            };
        })[];
    };
    flex: {
        name: string;
        slug: string;
        className: string;
        displayMode: string;
        baseStyles: ({
            selector: string;
            rules: {
                'flex-wrap': string;
                'align-items': string;
                margin?: undefined;
            };
        } | {
            selector: string;
            rules: {
                margin: string;
                'flex-wrap'?: undefined;
                'align-items'?: undefined;
            };
        })[];
        spacingStyles: {
            selector: string;
            rules: {
                gap: null;
            };
        }[];
    };
    grid: {
        name: string;
        slug: string;
        className: string;
        displayMode: string;
        baseStyles: {
            selector: string;
            rules: {
                margin: string;
            };
        }[];
        spacingStyles: {
            selector: string;
            rules: {
                gap: null;
            };
        }[];
    };
};
//# sourceMappingURL=layout.d.ts.map