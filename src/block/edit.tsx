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
                            {/* Schritt 1: Plattform wählen */}
                            <label>{__('Publication Source',
                                'rrze-research-data')}</label>
                            <SelectControl
                                value={source}
                                options={[
                                    {label: __('ORCID', 'rrze-research-data'),
                                        value: 'orcid'},
                                    {label: __('PubMed', 'rrze-research-data'),
                                        value: 'pubmed'},
                                    {label: __('OpenAlex', 'rrze-research-data'),
                                        value: 'openAlex'},
                                    {label: __('arXiv', 'rrze-research-data'),
                                        value: 'arxiv'},
                                    {label: __('DBLP', 'rrze-research-data'), value:
                                            'dblp'},
                                    {label: __('Crossref', 'rrze-research-data'),
                                        value: 'crossref'},
                                ]}
                                onChange={(value: string) => {
                                    setAttributes({source: value, authorId: ''});
                                    setSelectedPersonId('');
                                }}
                            />

                            {/* Schritt 2: ID-Eingabe je nach Plattform */}

                            {/* ORCID-Plattformen + arXiv: FAUdir wenn verfügbar */}
                            {['orcid', 'pubmed', 'openAlex', 'arxiv'].includes(source) && (
                                <>
                                    {isLoadingPersons ? (
                                        <p>{__('Loading…', 'rrze-research-data')}</p>
                                    ) : personList.length > 0 ? (
                                        <>
                                            <label>{__('Person (FAUdir)', 'rrze-research-data')}</label>
                                            <SelectControl
                                                value={selectedPersonId}
                                                options={[
                                                    {label: __('— Please select —', 'rrze-research-data'), value: ''},
                                                    ...personList.map((person: any) => ({
                                                        label: person.name,
                                                        value: person.id
                                                    }))
                                                ]}
                                                onChange={(personId: string) => {
                                                    setSelectedPersonId(personId);
                                                    if (!personId) return;
                                                    apiFetch({path:
                                                            `/rrze-research-data/v1/faudir/person/${personId}`})
                                                        .then((platformIds: any) =>
                                                        {
                                                            const id = source ===
                                                            'arxiv'
                                                                ? platformIds?.arxiv
                                                                ?? ''
                                                                : platformIds?.orcid
                                                                ?? '';
                                                            setAttributes({authorId:
                                                                id});
                                                        });
                                                }}
                                            />
                                            {selectedPersonId && !authorId && (
                                                <>
                                                    <p style={{color: '#cc0000'}}>
                                                        {source === 'arxiv'
                                                            ? __('No arXiv ID found. Please enter manually.', 'rrze-research-data')
                                                            : __('No ORCID found. Please enter manually.', 'rrze-research-data')
                                                        }
                                                    </p>
                                                    <TextControl
                                                        label={source === 'arxiv' ? __('arXiv Author-ID', 'rrze-research-data') : __('ORCID', 'rrze-research-data')}
                                                        value={authorId}
                                                        placeholder={source ===
                                                        'arxiv' ? 'hep-th/...' : '0000-0000-0000-0000'}
                                                        onChange={(value) =>
                                                            setAttributes({authorId: value})}
                                                    />
                                                </>
                                            )}
                                        </>
                                    ) : (
                                        // FAUdir nicht aktiv → direkt TextControl
                                        <TextControl
                                            label={source === 'arxiv' ? __('arXiv Author-ID', 'rrze-research-data') : __('ORCID',
                                                'rrze-research-data')}
                                            value={authorId}
                                            placeholder={source === 'arxiv' ?
                                                'hep-th/...' : '0000-0000-0000-0000'}
                                            onChange={(value) =>
                                                setAttributes({authorId: value})}
                                        />
                                    )}
                                </>
                            )}

                            {/* DBLP + Crossref: direkt TextControl, kein FAUdir */}
                            {['dblp', 'crossref'].includes(source) && (
                                <>
                                    <label>{source === 'dblp' ? __('DBLP PID',
                                        'rrze-research-data') : __('Crossref Author-ID',
                                        'rrze-research-data')}</label>
                                    <TextControl
                                        value={authorId}
                                        placeholder={source === 'dblp' ?
                                            'pid/l/LastnameF' : ''}
                                        onChange={(value) =>
                                            setAttributes({authorId: value})}
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
                        <PanelBody title={__('Research Data',
                            'rrze-research-data')} initialOpen={true}>
                            <SelectControl
                                label={__('Publication Source',
                                    'rrze-research-data')}
                                value={source}
                                options={[
                                    {label: __('ORCID',
                                            'rrze-research-data'), value: 'orcid'},
                                    {label: __('PubMed',
                                            'rrze-research-data'), value: 'pubmed'},
                                    {label: __('OpenAlex',
                                            'rrze-research-data'), value: 'openAlex'},
                                    {label: __('arXiv',
                                            'rrze-research-data'), value: 'arxiv'},
                                    {label: __('DBLP',
                                            'rrze-research-data'), value: 'dblp'},
                                    {label: __('Crossref',
                                            'rrze-research-data'), value: 'crossref'},
                                ]}
                                onChange={(value: string) =>
                                    setAttributes({source: value})}
                            />
                            <TextControl
                                label={source === 'arxiv'
                                    ? __('arXiv Author-ID',
                                        'rrze-research-data')
                                    : source === 'dblp'
                                        ? __('DBLP PID', 'rrze-research-data')
                                        : source === 'crossref'
                                            ? __('Crossref Author-ID',
                                                'rrze-research-data')
                                            : __('ORCID', 'rrze-research-data')
                                }
                                value={authorId}
                                placeholder={source === 'arxiv'
                                    ? 'hep-th/...'
                                    : source === 'dblp'
                                        ? 'pid/l/LastnameF'
                                        : source === 'crossref'
                                            ? ''
                                            : '0000-0000-0000-0000'
                                }
                                onChange={(value) =>
                                    setAttributes({authorId: value})}
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