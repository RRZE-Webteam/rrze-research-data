<?php

namespace RRZE\ResearchData\Models;


defined('ABSPATH') || exit;

/**
 * Represents a single publication from any research platform.
 * Acts as a unified data model so that each API (e.g. ORCID, arXiv, Web of Science)
 * can translate its own field names into a common structure.
 * The PublicationRenderer only needs to know this model, not the
 * individual APIs.
 */
class Publication
{
    //Eigenschaften
    public string $title;
    public string $type;
    public ?int $year;
    public string $url;
    public string $doi;
    public string $source;
    public ?string $journal;
    public array $authors = [];



    /**
     * Initializes the publication with data from a research platform.
     *
     * @param string $title The title of the publication.
     * @param string $type The publication type.
     * @param ?int $year The publication year.
     * @param string $url URL to the publication.
     * @param string $doi The DOI identifier.
     * @param string $source The source platform.
     * @param ?string $journal Book title
     * @param array $authors The authors
     *
     */
    public function __construct(string $title, string $type,?int $year, string $url, string $doi, string $source, ?string $journal = null, array $authors=[])
    {
        $this->title = $title;
        $this->type = $type;
        $this->year = $year;
        $this->url = $url;
        $this->doi = $doi;
        $this->source = $source;
        $this->journal = $journal;
        $this->authors = $authors;

    }
}

