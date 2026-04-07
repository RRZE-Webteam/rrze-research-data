import {__} from '@wordpress/i18n';

import {
    useBlockProps,
    InspectorControls
} from "@wordpress/block-editor";

import {
    Placeholder,
    __experimentalHeading as Heading,
    __experimentalSpacer as Spacer,
    __experimentalDivider as Divider,
    Button,
    TextControl,
    PanelBody,
    SelectControl,
    __experimentalNumberControl as NumberControl
} from "@wordpress/components";

import {pages} from "@wordpress/icons";
import ServerSideRender from '@wordpress/server-side-render';
import "./editor.scss";

interface EditProps {
    attributes: {
        view: 'list' | 'table';
        authorId: string;
        type: string;
        source: string;
        limit: number;
        sort: 'asc' | 'desc';
        isInitialSetup: boolean;
    }
    setAttributes: (attributes: Partial<EditProps["attributes"]>) => void;
}

export default function Edit({attributes, setAttributes}: EditProps) {

    const {
        view,
        authorId,
        type,
        source,
        limit,
        sort,
        isInitialSetup,

    } = attributes;

    const blockProps = useBlockProps();

    return (
        <div {...blockProps}>

            {isInitialSetup ? (
                <Placeholder
                    label={__('Research Data', 'rrze-research-data')}
                    instructions={__('Display research publications from external sources.', 'rrze-research-data')}
                    icon={pages}
                    isColumnLayout={true}
                >

                    <div>
                        <hr/>
                        <Spacer paddingBottom={"1rem"}/>
                        <div className="rrze-research-data-form">
                            <label>{__('Author ID', 'rrze-research-data')}</label>
                            <TextControl
                                value={authorId}
                                placeholder={__('Enter ORCID / Researcher ID', 'rrze-research-data')}
                                onChange={(value) => setAttributes({authorId: value})}
                            />
                            <label>{__('Publication Source', 'rrze-research-data')}</label>
                            <SelectControl
                                value={source}
                                options={[
                                    {label: __('ORCID', 'rrze-research-data'), value: 'orcid'},
                                    {label: __('PubMed', 'rrze-research-data'), value: 'pubmed'},
                                    {label: __('OpenAlex', 'rrze-research-data'), value: 'openAlex'}
                                ]}
                                onChange={(value: string) => setAttributes({source: value})}
                            />


                            <label>{__('Data Type', 'rrze-research-data')}</label>
                            <SelectControl
                                value={type}
                                options={[
                                    {label: __('Publications', 'rrze-research-data'), value: 'publications'},
                                    {label: __('Reviews', 'rrze-research-data'), value: 'reviews'},
                                ]}
                                onChange={(value: string) => setAttributes({type: value})}
                            />
                            <Spacer paddingTop=".5rem"/>

                            <Spacer paddingBottom={"0.5rem"}/>
                            <div>
                                <Button
                                    variant="primary"
                                    onClick={() => setAttributes({isInitialSetup: false})}
                                >
                                    {__('Save', 'rrze-research-data')}
                                </Button>
                            </div>
                            <Spacer paddingBottom={"0.5rem"}/>
                        </div>

                    </div>
                </Placeholder>
            ) : (
                <>
                    <InspectorControls>
                        <PanelBody title={__('Research Data', 'rrze-research-data')} initialOpen={true}>
                            <TextControl
                                label={__('Author ID', 'rrze-research-data')}
                                value={authorId}
                                placeholder={__('Enter ORCID / Researcher ID', 'rrze-research-data')}
                                onChange={(value) => setAttributes({authorId: value})}
                            />
                            <SelectControl
                                label={__('Publication Source', 'rrze-research-data')}
                                value={source}
                                options={[
                                    {label: __('ORCID', 'rrze-research-data'), value: 'orcid'},
                                    {label: __('PubMed', 'rrze-research-data'), value: 'pubmed'},
                                    {label: __('OpenAlex', 'rrze-research-data'), value: 'openAlex'}
                                ]}
                                onChange={(value: string) => setAttributes({source: value})}
                            />
                            <SelectControl
                                label={__('Data Type', 'rrze-research-data')}
                                value={type}
                                options={[
                                    {label: __('Publications', 'rrze-research-data'), value: 'publications'},
                                    {label: __('Reviews', 'rrze-research-data'), value: 'reviews'},
                                ]}
                                onChange={(value: string) => setAttributes({type: value})}
                            />
                        </PanelBody>

                        <PanelBody title={__('Display Options', 'rrze-research-data')} initialOpen={false}>
                            <SelectControl
                                label={__('View', 'rrze-research-data')}
                                value={view}
                                options={[
                                    {disabled: true, label: __('Select an Option', 'rrze-research-data'), value: ''},
                                    {label: __('List', 'rrze-research-data'), value: 'list'},
                                    {label: __('Table', 'rrze-research-data'), value: 'table'},
                                ]}
                                onChange={(val: 'list' | 'table') =>
                                    setAttributes({view: val as 'list' | 'table'})
                                }
                            />
                            <SelectControl
                                label={__('Sorting', 'rrze-research-data')}
                                value={sort}
                                options={[
                                    {label: __('Newest first', 'rrze-research-data'), value: 'desc'},
                                    {label: __('Oldest first', 'rrze-research-data'), value: 'asc'},
                                ]}
                                onChange={(val: 'asc' | 'desc') =>
                                    setAttributes({sort: val as 'asc' | 'desc'})
                                }
                            />
                            <NumberControl
                                __next40pxDefaultSize
                                label={__('Limit', 'rrze-research-data')}
                                value={limit}
                                onChange={(value) => setAttributes({limit: Number(value)})}
                            />
                        </PanelBody>
                    </InspectorControls>
                    {/* Serverseitige Ausgabe der Publikationen */}
                    <ServerSideRender
                        block="rrze/research-data"
                        attributes={attributes}
                    />
                </>
            )}
        </div>
    );
}