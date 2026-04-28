import {__} from '@wordpress/i18n';

import {
    useBlockProps,
    InspectorControls
} from "@wordpress/block-editor";

import {
    Placeholder,
    __experimentalSpacer as Spacer,
    Button,
    CheckboxControl,
    BaseControl,
    TextControl,
    PanelBody,
    SelectControl,
    Tooltip,
    Icon,
    __experimentalNumberControl as NumberControl
} from "@wordpress/components";

import {useState, useEffect} from "@wordpress/element";
import apiFetch from '@wordpress/api-fetch';

import {pages, info} from "@wordpress/icons";
import ServerSideRender from '@wordpress/server-side-render';
import "./editor.scss";

interface EditProps {
    attributes: {
        authorId: string;
        type: string[];
        source: string;
        limit: number;
        isInitialSetup: boolean;
        yearFrom: number;
        yearTo: number;
        groupBy: string;
        citationStyle: string;
    }
    setAttributes: (attributes: Partial<EditProps["attributes"]>) => void;
}

export default function Edit({attributes, setAttributes}: EditProps) {

    const {
        authorId,
        type,
        source,
        limit,
        isInitialSetup,
        yearFrom,
        yearTo,
        groupBy,
        citationStyle
    } = attributes;

    const [personList, setPersonList] = useState([]);
    const [isLoadingPersons, setIsLoadingPersons] = useState(true);
    const [selectedPersonId, setSelectedPersonId] = useState('');
    const [showManualInput, setShowManualInput] = useState(false);


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
                            {/* step 1: Choose platform */}
                            <label>{__('Publication Source', 'rrze-research-data')}</label>
                            <SelectControl
                                value={source}
                                options={[
                                    {label: __('ORCID', 'rrze-research-data'), value: 'orcid'},
                                    {label: __('PubMed', 'rrze-research-data'), value: 'pubmed'},
                                    {label: __('OpenAlex', 'rrze-research-data'), value: 'openAlex'},
                                    {label: __('arXiv', 'rrze-research-data'), value: 'arxiv'},
                                    {label: __('Crossref', 'rrze-research-data'), value: 'crossref'},
                                    {label: __('DBLP', 'rrze-research-data'), value: 'dblp'},
                                    {label: __('Semantic Scholar', 'rrze-research-data'), value: 'semanticscholar'},
                                ]}
                                onChange={(value: string) => {
                                    setAttributes({source: value, authorId: ''});
                                    setSelectedPersonId('');
                                    setShowManualInput(false);
                                }}
                            />

                            {/* step2: ID for platform */}
                            {/* ORCID platforms + arXiv: if FAUdir available */}
                            {['orcid', 'pubmed', 'openAlex', 'arxiv', 'crossref'].includes(source) && (
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
                                                    apiFetch({path: `/rrze-research-data/v1/faudir/person/${personId}`})
                                                        .then((platformIds: any) => {
                                                            const id = source === 'arxiv' ? platformIds?.arxiv ?? '' : platformIds?.orcid ?? '';
                                                            setAttributes({authorId: id});
                                                            if (!id) setShowManualInput(true);
                                                        });
                                                }}
                                            />
                                            {showManualInput && (
                                                <>
                                                    <p style={{color: '#cc0000'}}>
                                                        {source === 'arxiv'
                                                            ? __('No arXiv ID in FAUdir found. Please enter manually.', 'rrze-research-data')
                                                            : __('No ORCID in FAUdir found. Please enter manually.', 'rrze-research-data')
                                                        }
                                                        {' '}
                                                        <Tooltip
                                                            text={source === 'arxiv'
                                                                ? __('Enter your arXiv Author-ID in idm.fau.de under: FAUdir → Entries → Portals and networks', 'rrze-research-data')
                                                                : __('Enter your ORCID in idm.fau.de under: FAUdir → Entries → Portals and networks', 'rrze-research-data')
                                                            }
                                                            placement="right"
                                                        >
                                                            <span className="rrze-research-data-info"><Icon
                                                                icon={info}/></span>
                                                        </Tooltip>
                                                    </p>
                                                    <label>{source === 'arxiv' ? __('arXiv Author-ID', 'rrze-research-data') : __('ORCID', 'rrze-research-data')}</label>
                                                    <TextControl
                                                        value={authorId}
                                                        placeholder={source === 'arxiv' ? 'lastname_f_1' : '0000-0000-0000-0000'}
                                                        onChange={(value) => setAttributes({authorId: value})}
                                                    />
                                                </>
                                            )}

                                        </>
                                    ) : (
                                        // if FAUdir ist not active → TextControl
                                        <>
                                            <label>{source === 'arxiv' ? __('arXiv Author-ID', 'rrze-research-data') : __('ORCID', 'rrze-research-data')}</label>
                                            <TextControl
                                                value={authorId}
                                                placeholder={source === 'arxiv' ? 'lastname_f_1' : '0000-0000-0000-0000'}
                                                onChange={(value) => setAttributes({authorId: value})}
                                            />
                                        </>
                                    )}
                                </>
                            )}

                            {/* DBLP + Crossref: directly in TextControl, no FAUdir */}
                            {['dblp', 'semanticscholar'].includes(source) && (
                                <>
                                    <label>{source === 'dblp' ? __('DBLP PID', 'rrze-research-data') : __('SemanticScholar Author-ID', 'rrze-research-data')}</label>
                                    <TextControl
                                        value={authorId}
                                        placeholder={source === 'dblp' ? 'xx/0000' : '0000000'}
                                        onChange={(value) => setAttributes({authorId: value})}
                                    />
                                    {source === 'semanticscholar' && (
                                        <p className="rrze-research-data-help">
                                            {__('ID from your Semantic Scholar profile URL:', 'rrze-research-data')}
                                            <code>/author/Yourname/<strong>0000000</strong></code>
                                        </p>
                                    )}
                                    {source === 'dblp' && (
                                        <p className="rrze-research-data-help">
                                            {__('ID from your DBLP profile URL:', 'rrze-research-data')}
                                            <code>/pid/<strong>xx/0000</strong></code>
                                        </p>
                                    )}


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
                                label={__('Publication Source', 'rrze-research-data')}
                                value={source}
                                options={[
                                    {label: __('ORCID', 'rrze-research-data'), value: 'orcid'},
                                    {label: __('PubMed', 'rrze-research-data'), value: 'pubmed'},
                                    {label: __('OpenAlex', 'rrze-research-data'), value: 'openAlex'},
                                    {label: __('arXiv', 'rrze-research-data'), value: 'arxiv'},
                                    {label: __('Crossref', 'rrze-research-data'), value: 'crossref'},
                                    {label: __('DBLP', 'rrze-research-data'), value: 'dblp'},
                                    {label: __('Semantic Scholar', 'rrze-research-data'), value: 'semanticscholar'},
                                ]}
                                onChange={(value: string) =>
                                    setAttributes({source: value, authorId: ''})}
                            />
                            <TextControl
                                label={source === 'arxiv' ? __('arXiv Author-ID', 'rrze-research-data')
                                    : source === 'dblp' ? __('DBLP PID', 'rrze-research-data')
                                        : source === 'semanticscholar' ? __('Semantic Scholar Author-ID', 'rrze-research-data')
                                            : __('ORCID', 'rrze-research-data')
                                }
                                value={authorId}
                                placeholder={source === 'arxiv' ? 'lastname_x_x'
                                    : source === 'dblp' ? 'xx/0000'
                                        : source === 'semanticscholar' ? '0000000'
                                            : '0000-0000-0000-0000'
                                }
                                onChange={(value) => setAttributes({authorId: value})}
                            />
                        </PanelBody>
                        <PanelBody title={__('Display Options', 'rrze-research-data')} initialOpen={false}>
                            <BaseControl label={__('Publication Type', 'rrze-research-data')}>
                                <Spacer paddingTop=".2rem"/>
                                {[
                                    {label: __('Journal Article', 'rrze-research-data'), value: 'journal-article'},
                                    {label: __('Conference', 'rrze-research-data'), value: 'conference'},
                                    {label: __('Book', 'rrze-research-data'), value: 'book'},
                                    {label: __('Book Chapter', 'rrze-research-data'), value: 'book-chapter'},
                                    {label: __('Editorship', 'rrze-research-data'), value: 'editorship'},
                                    {label: __('Preprint', 'rrze-research-data'), value: 'preprint'},
                                    {label: __('Review', 'rrze-research-data'), value: 'review'},
                                    {label: __('Thesis', 'rrze-research-data'), value: 'thesis'},
                                    {label: __('Other', 'rrze-research-data'), value: 'other'},
                                ].map(option => (
                                    <CheckboxControl
                                        key={option.value}
                                        label={option.label}
                                        checked={type.includes(option.value)}
                                        onChange={(checked) => {
                                            if (checked) {
                                                setAttributes({
                                                    type: [...type, option.value]
                                                });
                                            } else {
                                                setAttributes({
                                                    type: type.filter(t => t !==
                                                        option.value)
                                                });
                                            }
                                        }}
                                    />
                                ))}
                            </BaseControl>
                            <Spacer paddingTop=".5rem"/>
                            <SelectControl
                                label={__('Group by', 'rrze-research-data')}
                                value={groupBy}
                                options={[
                                    {label: __('No grouping', 'rrze-research-data'), value: ''},
                                    {label: __('Year', 'rrze-research-data'), value: 'year'},
                                    {label: __('Publication Type', 'rrze-research-data'), value: 'type'},
                                ]}
                                onChange={(val: string) => setAttributes({groupBy: val})}
                            />
                            <SelectControl
                                label={__('Citation Style', 'rrze-research-data')}
                                value={citationStyle}
                                options={[
                                    {label: __('Standard', 'rrze-research-data'), value: ''},
                                    {label: __('APA', 'rrze-research-data'), value: 'apa'},
                                    {label: __('MLA', 'rrze-research-data'), value: 'mla'},
                                ]}
                                onChange={(val: string) => setAttributes({citationStyle: val})}
                            />
                            <NumberControl
                                label={__('Year from', 'rrze-research-data')}
                                value={yearFrom === 0 ? '' : yearFrom}
                                min={1900}
                                max={2100}
                                onChange={(value) => setAttributes({
                                    yearFrom: parseInt(value as
                                        string) || 0
                                })}
                            />
                            <NumberControl
                                label={__('Year to', 'rrze-research-data')}
                                value={yearTo === 0 ? '' : yearTo}
                                min={1900}
                                max={2100}
                                onChange={(value) => setAttributes({
                                    yearTo: parseInt(value as
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
                    {/* Serverside output publications */}
                    <ServerSideRender
                        block="rrze/research-data"
                        attributes={attributes}
                    />
                </>
            )}
        </div>
    );
}