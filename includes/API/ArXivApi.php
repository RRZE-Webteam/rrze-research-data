<?php

namespace RRZE\ResearchData\API;


defined('ABSPATH') || exit;

use RRZE\ResearchData\Models\Publication;

/**
 * Fetches publication data from the arXiv API.
 *
 * arXiv is an open-access repository for preprints in physics,
 * mathematics, computer science and related fields.
 *
 * The API returns an Atom feed (XML) — a standard format for content syndication.
 * No authentication is required.
 *
 * @see https://arxiv.org/help/api
 */
class ArXivApi
{
    const BASE_URL = 'https://arxiv.org/a/';

    /**
     * Fetches all publications for a given arXiv author identifier.
     *
     * @param string $authorId arXiv author identifier, e.g. "warner_s_1"
     * @return array|\WP_Error Array of Publication objects, or WP_Error on failure
     */
    public function getAllWorks(string $authorId): array|\WP_Error
    {
        $url = self::BASE_URL . $authorId . '.atom';

        $body = $this->request($url);

        if (is_wp_error($body)) {
            return $body;
        }

        $xml = simplexml_load_string($body);

        if ($xml === false) {
            return new \WP_Error('arxiv-api-error', esc_html__('Invalid XML format.', 'rrze-research-data'));
        }

        $publications = [];
        foreach ($xml->entry as $item) {
            $publications[] = $this->mapToPublication($item);
        }

        return $publications;
    }

    /**
     * Sends an HTTP GET request and returns the raw response body.
     *
     * Returns the body string (not decoded) because arXiv delivers XML,
     * not JSON. Parsing happens in getAllWorks().
     *
     * @param string $url Full request URL
     * @return string|\WP_Error Raw response body, or WP_Error on failure
     */
    private function request(string $url): string|\WP_Error
    {
        $response = wp_remote_get($url, [
            'headers' => ['Accept' => 'application/atom+xml'],
            'timeout' => 15,
        ]);

        if (is_wp_error($response)) {
            return $response;
        }

        $statusCode = wp_remote_retrieve_response_code($response);
        if ($statusCode !== 200) {
            return new \WP_Error(
                'arxiv-api-error',
                sprintf(__('arXiv API returned status code %d.', 'rrze-research-data'), $statusCode)
            );
        }

        return wp_remote_retrieve_body($response);
    }


    /**
     * Maps a single arXiv feed entry to a Publication model.
     *
     * arXiv delivers its API responses as an Atom feed — a standard XML format for syndication (similar to RSS).
     * Each publication appears as an <entry> element inside this feed.
     *
     * Extracted fields:
     * - title        → publication title
     * - published    → publication date (we extract the 4-digit year)
     * - author/name  → list of author names
     * - link[href]   → if the link contains "doi.org", it is used as the DOI
     * - id           → arXiv URL, used as fallback link
     * - journal_ref  → optional field set by the author (e.g. "Nature 2023")
     *
     * All arXiv entries are treated as preprints because arXiv does not
     * provide a publication type.
     *
     * @param \SimpleXMLElement $item A single <entry> element from the arXiv Atom feed
     * @return Publication
     */

    private function mapToPublication(\SimpleXMLElement $item): Publication
    {
        $title = trim((string)$item->title);

        $year = null;
        $published = (string)$item->published;
        if (!empty($published)) {
            $year = (int)substr($published, 0, 4);
        }
        $url = (string)$item->id;
        $doi = '';
        foreach ($item->link as $link) {
            $href = (string)$link['href'];
            if (str_contains($href, 'doi.org')) {
                $doi = $href;
            }
        }
        $authors = [];
        foreach ($item->author as $author) {
            $name = trim((string)$author->name);
            if ($name) {
                $authors[] = $name;
            }
        }
        $namespaces = $item->getNamespaces(true);
        $arxiv = $item->children($namespaces['arxiv'] ?? '');
        $journal = $arxiv ? trim((string)($arxiv->journal_ref ?? '')) : '';

        return new Publication(
            title: $title,
            // arXiv does not provide a publication type — all entries are treated as preprints.
            type: 'preprint',
            year: $year,
            url: $url,
            doi: $doi,
            source: 'arxiv',
            journal: $journal,
            authors: $authors,
            volume: '',
            pages: '',
        );
    }

}
