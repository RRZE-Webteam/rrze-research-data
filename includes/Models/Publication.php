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
    public string $title;
    public string $type;
    public ?int $year;
    public string $url;
    public string $doi;
    public string $source;
    public ?string $journal;
    public array $authors = [];
    public string $volume = '';
    public string $pages = '';
    public string $issue = '';

    /**
     * Initializes the publication with data from a research platform.
     *
     * @param string $title The publication title
     * @param string $type The raw publication type (will be normalized)
     * @param ?int $year The publication year, or null if unknown
     * @param string $url URL to the publication (used as fallback if no DOI)
     * @param string $doi DOI identifier – the "https://doi.org/" prefix is stripped automatically
     * @param string $source The source platform, e.g. "orcid", "openAlex"
     * @param ?string $journal Journal or venue name
     * @param array $authors List of author names as strings
     * @param string $volume Volume number
     * @param string $pages Page range, e.g. "123-145"
     * @param string $issue Issue number
     */
    public function __construct(string $title, string $type, ?int $year, string $url, string $doi, string $source, ?string $journal = null, array $authors = [], string $volume = '', string $pages = '', string $issue = '')
    {
        $this->title = $title;
        $this->type = self::normalizeType($type);
        $this->year = $year;
        $this->url = $url;
        $this->doi = preg_replace('#^https?://doi\.org/#i', '', $doi ?? '');
        $this->source = $source;
        $this->journal = $journal;
        $this->authors = $authors;
        $this->volume = $volume;
        $this->pages = $pages;
        $this->issue = $issue;
    }

    private static function normalizeType(string $type): string
    {
        return match (strtolower($type)) {
            'journal-article', 'article', 'journalarticle', 'journal article'
            => 'journal-article',
            'conference-paper', 'inproceedings', 'conference'
            => 'conference',
            'book'
            => 'book',
            'book-chapter', 'incollection'
            => 'book-chapter',
            'preprint'
            => 'preprint',
            'review'
            => 'review',
            'phdthesis', 'mastersthesis'
            => 'thesis',
            'editorship'
            => 'editorship',
            default
            => 'other',
        };
    }

}

