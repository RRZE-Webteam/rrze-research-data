<?php

namespace RRZE\ResearchData\API;


defined('ABSPATH') || exit;

use RRZE\ResearchData\Models\Publication;

/** Fetches publication data from the DBLP API.
 *
 * DBLP is a computer science bibliography database that indexes
 * publications from journals and conference proceedings.
 *
 * The API returns an XML file per author, identified by a PID (person identifier) from the DBLP profile URL.
 * No authentication is required.
 *
 * @see https://dblp.org/faq/How+can+I+fetch+all+publication
 * s+of+one+specific+author.html
 */
class DblpApi
{
    const BASE_URL = 'https://dblp.org/pid/';

    /**
     * Fetches all publications for a given DBLP author PID.
     *
     * @param string $authorId DBLP PID, e.g. "06/3501" or "l/LastnameF"
     * @return array|\WP_Error Array of Publication objects, or WP_Error on failure
     */
    public function getAllWorks(string $authorId): array|\WP_Error
    {
        $url = self::BASE_URL . $authorId . '.xml';

        $response = $this->request($url);

        if (is_wp_error($response)) {
            return $response;
        }

        $xml = simplexml_load_string($response);

        if ($xml === false) {
            return new \WP_Error('dblp-api-error', esc_html__('Invalid XML format.', 'rrze-research-data'));
        }

        $publications = [];
        foreach ($xml->r as $r) {
            $item = $r->children()[0];
            if ($item === null) continue;
            $publications[] = $this->mapToPublication($item);
        }

        return $publications;
    }

    /**
     * Sends an HTTP GET request and returns the raw response body.
     *
     * Returns a string (not decoded) because DBLP delivers XML.
     * Parsing happens in getAllWorks().
     *
     * @param string $url Full request URL
     * @return string|\WP_Error Raw response body, or WP_Error on failure
     */
    private function request(string $url): string|\WP_Error
    {
        $response = wp_remote_get($url, [
            'headers' => ['Accept' => 'application/xml'],
            'timeout' => 15,
        ]);

        if (is_wp_error($response)) {
            return $response;
        }

        $statusCode = wp_remote_retrieve_response_code($response);
        if ($statusCode !== 200) {
            return new \WP_Error(
                'dblp-api-error',
                sprintf(__('DBLP API returned status code %d.', 'rrze-research-data'), $statusCode)
            );
        }

        return wp_remote_retrieve_body($response);
    }

    /**
     * Maps a single DBLP XML entry to a Publication model.
     *
     * DBLP wraps each publication inside an <r> element. The actual
     * publication type (article, inproceedings, book, ...) is the
     * name of the child element — retrieved via getName().
     *
     * The <ee> field contains an external URL — either a DOI link
     * or a direct URL. If it contains "doi.org", it is treated as DOI.
     *
     * Journal articles use <journal>, conference papers use <booktitle> as the venue name.
     *
     * @param \SimpleXMLElement $item A single publication element from the DBLP XML
     * @return Publication
     */
    private function mapToPublication(\SimpleXMLElement $item): Publication
    {
        $title = trim((string)$item->title);
        $year = (int)$item->year;
        $journal = (string)($item->journal ?? $item->booktitle ?? '');
        $type = $item->getName();
        $volume = (string)($item->volume ?? '');
        $pages = (string)($item->pages ?? '');
        $ee = (string)($item->ee ?? ''); //external link to publication (doi or url)
        if (str_contains($ee, 'doi.org')) {
            $doi = $ee;
            $url = '';
        } else {
            $doi = '';
            $url = $ee;
        }

        $authors = [];
        foreach ($item->author as $author) {
            $authors[] = (string)$author;
        }

        return new Publication(
            title: $title,
            type: $type,
            year: $year,
            url: $url,
            doi: $doi,
            source: 'dblp',
            journal: $journal,
            authors: $authors,
            volume: $volume,
            pages: $pages,
            issue: '', // DBLP does not provide issue numbers
        );
    }

}







