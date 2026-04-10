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

import {useState, useEffect} from "@wordpress/element";
import apiFetch from '@wordpress/api-fetch';

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
        isInitialSetup: boolean;
        year: number;
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
        isInitialSetup,
        year
    } = attributes;

    const [personList, setPersonList] = useState([]);
    const [isLoadingPersons, setIsLoadingPersons] = useState(true);
    const [selectedPersonId, setSelectedPersonId] = useState('');

    useEffect(() => {
        apiFetch({path: '/rrze-research-data/v1/faudir/persons'})
            .then((persons: any) => {
                setPersonList(persons);
                setIsLoadingPersons(false);
            })
            .catch(() => {
                setIsLoadingPersons(false);
            });
    }, []);


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
                            {isLoadingPersons ? (
                                <p>{__('Loading…', 'rrze-research-data')}</p>
                            ) : personList.length > 0 ? (
                                <>
                                    <label>{__('Person (FAUdir)', 'rrze-research-data')}</label>
                                    <SelectControl
                                        value={selectedPersonId}
                                        options={[
                                            {
                                                label: __('— Please select —',
                                                    'rrze-research-data'), value: ''
                                            },
                                            ...personList.map((person: any) => ({
                                                label: person.name,
                                                value: person.id
                                            }))
                                        ]}
                                        onChange={(personId: string) => {
                                            setSelectedPersonId(personId);
                                            if (!personId) return;
                                            apiFetch({ path:
                                                    `/rrze-research-data/v1/faudir/person/${personId}` })
                                                .then((platformIds: any) => {
                                                    const orcid = platformIds?.orcid ?? '';
                                                    setAttributes({ authorId: orcid });
                                                });
                                        }}
                                    />
                                    {selectedPersonId && !authorId && (
                                        <>
                                            <p style={{color: '#cc0000'}}>
                                                {__('No ORCID found. Please enter manually.',
                                                    'rrze-research-data')}
                                            </p>
                                            <TextControl
                                                label={__('ORCID', 'rrze-research-data')}
                                                value={authorId}
                                                placeholder="0000-0000-0000-0000"
                                                onChange={(value) => setAttributes({authorId:
                                                    value})}
                                            />
                                        </>
                                    )}

                                </>
                            ) : (
                                <>
                                    <label>{__('Author ID', 'rrze-research-data')}</label>
                                    <TextControl
                                        value={authorId}
                                        placeholder={__('Enter ORCID / Researcher ID', 'rrze-research-data')}
                                        onChange={(value) => setAttributes({authorId: value})}
                                    />
                                </>
                            )}
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
                            <NumberControl
                                label={__('Publications from year', 'rrze-research-data')}
                                value={year === 0 ? '' : year}
                                min={1900}
                                max={2100}
                                onChange={(value) => setAttributes({
                                    year: parseInt(value as
                                        string) || 0
                                })}
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