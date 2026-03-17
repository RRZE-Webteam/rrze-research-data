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
    CheckboxControl,
    Button,
    TextControl,
    PanelBody,
    SelectControl,
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
        order: string;
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
        order,

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
                            <label>Data Source</label>
                            <SelectControl
                                value={source}
                                options={[
                                    {label: 'arXiv', value: 'arxiv'},
                                    {label: 'Web of Science', value: 'wos'},
                                    {label: 'ORCID', value: 'orcid'}
                                ]}
                                onChange={(value) => setAttributes({source: value})}
                            />

                            <label>Author ID</label>
                            <TextControl
                                value={authorId}
                                placeholder="Enter ORCID / Researcher ID"
                                onChange={(value) => setAttributes({authorId: value})}
                            />

                            <label>Data Type</label>
                            <SelectControl
                                value={type}
                                options={[
                                    {label: 'Publications', value: 'publications'},
                                    {label: 'Reviews', value: 'reviews'}
                                ]}
                                onChange={(value) => setAttributes({type: value})}
                            />
                            <Spacer paddingTop=".5rem"/>


                            <Spacer paddingBottom={"0.5rem"}/>
                            <Button
                                variant="primary"
                                onClick={() => setAttributes({isInitialSetup: false})}
                            >
                                {__('Save', 'rrze-research-data')}
                            </Button>
                            <Spacer paddingBottom={"0.5rem"}/>
                        </div>

                    </div>
                </Placeholder>
            ) : (
                <>
                    <InspectorControls>
                        <PanelBody title={__('Data Choice', 'rrze-research-data')} initialOpen={false}>
                            <Spacer paddingTop={"0.5rem"}/>


                            <Spacer paddingTop={"0.5rem"}/>
                            <Heading color="#03316a">{__('Select Folders', 'rrze-research-data')}</Heading>

                        </PanelBody>

                        <PanelBody title={__('Display Options', 'rrze-research-data')} initialOpen={false}>
                            <Spacer paddingTop={"0.5rem"}/>
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

                            <Divider margin="3"/>
                            <Heading color="#03316a">{__('Displayed file information', 'rrze-research-data')}</Heading>
                            {/*<CheckboxControl
                                label={__('File Type', 'rrze-research-data')}
                                checked={show.includes('type')}
                                onChange={() => toggleShow('type')}
                            />*/}

                            <Divider margin="2"/>


                            <Divider margin="2"/>
                            <SelectControl
                                label={__('Sorting', 'rrze-research-data')}
                                value={sort}
                                options={[
                                    {label: __('Ascending', 'rrze-research-data'), value: 'asc'},
                                    {label: __('Descending', 'rrze-research-data'), value: 'desc'},
                                ]}
                                onChange={(val: 'asc' | 'desc') =>
                                    setAttributes({sort: val as 'asc' | 'desc'})
                                }
                            />
                        </PanelBody>

                    </InspectorControls>
                    /* Ausgabe nach Setup *!/

                    <ServerSideRender
                        block="rrze/research-data"
                        attributes={attributes}
                    />

                </>
            )}
        </div>
    )
        ;
}