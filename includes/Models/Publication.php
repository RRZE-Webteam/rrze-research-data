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
    //Properties
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



    /**
     * Initializes the publication with data from a research platform.
     *
     * @param string $title The title of the publication.
     * @param string $type The publication type.
     * @param ?int $year The publication year.
     * @param string $url URL to the publication.
     * @param string $doi The DOI identifier.
     * @param string $source The source platform.
     * @param ?string $journal source title
     * @param array $authors The authors
     *
     */
    public function __construct(string $title, string $type,?int $year, string $url, string $doi, string $source, ?string $journal = null, array $authors=[], string $volume='', string $pages='')
    {
        $this->title = $title;
        $this->type = self::normalizeType($type);
        $this->year = $year;
        $this->url = $url;
        $this->doi = $doi;
        $this->source = $source;
        $this->journal = $journal;
        $this->authors = $authors;
        $this->volume = $volume;
        $this->pages  = $pages;
    }

    private static function normalizeType($type): string
    {
        return match(strtolower($type)){
            'journal-article', 'article', 'journalarticle', 'journal article' => 'journal-article',
            'conference-paper', 'inproceedings', 'conference' => 'conference',
            'book' => 'book',
            'book-chapter', 'incollection' => 'book-chapter',
            'preprint' => 'preprint',
            'review' => 'review',
            'phdthesis', 'mastersthesis' => 'thesis',
            'editorship'=>'editorship',
            default
            => 'other',
        };
    }

}

