<?php

namespace RRZE\ResearchData\API;


defined('ABSPATH') || exit;

use RRZE\ResearchData\Models\Publication;

/**
 * Fetches publication data from the ORCID Public API.
 *
 * Uses the public endpoint (pub.orcid.org) which requires no authentication.
 * @see https://pub.orcid.org/v3.0
 */

class OrcidApi
{
    const BASE_URL = 'https://pub.orcid.org/v3.0';

    /**
     * Fetches all publications for a given ORCID author ID.
     *
     * @param string $authorId  ORCID identifier, e.g. "0000-0003-4713-5941"
     * @return array|\WP_Error  Array of Publication objects, or WP_Error on failure
     */
    public function getAllWorks(string $authorId): array|\WP_Error
    {
        // Step 1: Build the request URL
        $url = self::BASE_URL . '/' . $authorId . '/works';

        // Step 2: Send HTTP request
        $response = $this->request($url);

        // Step 3: Return immediately if request failed
        if (is_wp_error($response)) {
            return $response;
        }

        // Step 4: ORCID groups works together (e.g. duplicate entries).
        // We take the first summary per group – it's the preferred version.
        $groups = $response['group'] ?? [];

        $publications = [];

        foreach ($groups as $group) {
            $item = $group['work-summary'][0] ?? null;

            if ($item === null) {
                continue;
            }

            $publications[] = $this->mapToPublication($item);
        }

        return $publications;
    }

    /**
     * Sends an HTTP GET request and returns the decoded JSON response.
     *
     * @param string $url  Full request URL
     * @return array|\WP_Error  Decoded PHP array, or WP_Error on failure
     */
    private function request(string $url): array|\WP_Error
    {
        $response = wp_remote_get($url, [
            'headers' => [
                // Tell the API: "Send me JSON, not XML."
                'Accept' => 'application/json',
            ],
            'timeout' => 15,
        ]);

        // Check if WordPress itself had an error (e.g. no internet, DNS failure)
        if (is_wp_error($response)) {
            return $response;
        }

        // Check the HTTP status code (200 = OK, 404 = not found, etc.)
        $status = wp_remote_retrieve_response_code($response);
        if ($status !== 200) {
            return new \WP_Error(
                'orcid_api_error',
                sprintf('ORCID API returned status code %d.', $status)
            );
        }

        // Extract the response body (a JSON string) and decode it into a PHP array
        $body        = wp_remote_retrieve_body($response);
        $decodedData = json_decode($body, true);

        if (empty($decodedData)) {
            return new \WP_Error('orcid_invalid_response', 'ORCID API returned no valid data.');
        }

        return $decodedData;

    }

    /**
     * Maps a single ORCID work summary to a Publication model.
     *
     * @param array $item A single work-summary from the ORCID API response
     * @return Publication
     */
    public function mapToPublication(array $item): Publication
    {
        // Title – deeply nested in the ORCID structure
        $title = $item['title']['title']['value'] ?? '';

        // Publication type, e.g. "journal-article", "book", "conference-paper"
        $type = $item['type'] ?? '';

        // Publication year (maybe missing, so null as fallback)
        $year = $item['publication-date']['year']['value'] ?? null;

        // Journal name (or book title)
        $journal = $item['journal-title']['value'] ?? '';

        $authors = [];

        // URL to the publication (if provided)
        $url = $item['url']['value'] ?? '';

        // Find the DOI in the external-ids list
        $doi = '';
        $external_ids = $item['external-ids']['external-id'] ?? [];
        foreach ($external_ids as $ext_id) {
            if (($ext_id['external-id-type'] ?? '') === 'doi') {
                $doi = $ext_id['external-id-value'] ?? '';
                break;
            }
        }

        return new Publication(
            title: $title,
            type: $type,
            year: $year !==null ? (int) $year : null,
            url: $url,
            doi: $doi,
            source: 'orcid',
            journal: $journal,
            authors: $authors,
        );
    }

}






