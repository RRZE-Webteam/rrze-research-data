<?php

namespace RRZE\ResearchData\API;


defined('ABSPATH') || exit;

use RRZE\ResearchData\Models\Publication;

/**
 * Fetches publication data from the DBLP API.
 *
 * Uses the person page XML endpoint which requires no authentication.
 * @see https://dblp.org/faq/How+can+I+fetch+all+publications+of+one+specific+author.html
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

        $data = $this->request($url);

        if (is_wp_error($data)) {
            return $data;
        }

        $xml = simplexml_load_string($data);

        if ($xml === false) {
            return new \WP_Error('dblp-api-error', esc_html__('Invalid XML format.', 'rrze-research-data'));
        }

        $publications = [];
        foreach ($xml->r as $r) {
            $entry = $r->children()[0];
            if ($entry === null) continue;
            $publications[] = $this->mapToPublication($entry);
        }

        return $publications;
    }


    /**
     * Maps a single DBLP XML entry to a Publication model.
     *
     * @param \SimpleXMLElement $entry A single publication element
    (article, inproceedings, ...)
     * @return Publication
     */
    private
        function mapToPublication(\SimpleXMLElement $entry): Publication
        {
            $title = trim((string)$entry->title);

            $year = (int)$entry->year;

            $journal = (string)($entry->journal ?? $entry->booktitle ?? '');

            $ee = (string) ($entry->ee ?? '');
            if (str_contains($ee, 'doi.org')) {
                $doi = $ee;
                $url = '';
            }  else {
                $doi = '';
                $url = $ee;
            }

            $authors = [];
            foreach ($entry->author as $author) {
                $authors[] = (string)$author;
            }
            // The tag name tells us the type: article, inproceedings, book, etc.
            $type = $entry->getName();

            $volume = (string) ($entry->volume ?? '');
            $pages  = (string) ($entry->pages ?? '');

            return new Publication(
                title: $title,
                type: $type,
                year: $year,
                url: $url,
                doi: $doi,
                source: 'dblp',
                journal: $journal,
                authors: $authors,
                volume:  $volume,
                pages: $pages,

            );
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

    private
    function request(string $url): string|\WP_Error
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
                sprintf('DBLP API returned status code %d.', $statusCode)
            );
        }

        return wp_remote_retrieve_body($response);
    }

    }







