<?php

namespace RRZE\ResearchData\API;


defined('ABSPATH') || exit;

use RRZE\ResearchData\Models\Publication;

/**
 * Fetches publication data from the ORCID Public API.
 *
 * ORCID (Open Researcher and Contributor ID) is a non-profit registry
 * that provides persistent identifiers for researchers. Authors maintain
 * their own publication lists on their ORCID profile.
 *
 * This class uses the public read endpoint which requires no authentication.
 * It fetches work summaries — lightweight records that include title, type,
 * year and DOI, but no author lists (see mapToPublication() for details).
 *
 * @see https://pub.orcid.org/v3.0
 */

class OrcidApi
{
    const BASE_URL = 'https://pub.orcid.org/v3.0';

    /**
     * Fetches all publications for a given ORCID author ID.
     *
     * @param string $authorId ORCID identifier, e.g. "0000-0003-4713-5941"
     * @return array|\WP_Error  Array of Publication objects, or WP_Error on failure
     */
    public function getAllWorks(string $authorId): array|\WP_Error
    {
        $url = self::BASE_URL . '/' . $authorId . '/works';

        $response = $this->request($url);

        if (is_wp_error($response)) {
            return $response;
        }

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
     * @param string $url Full request URL
     * @return array|\WP_Error  Decoded PHP array, or WP_Error on failure
     */
    private function request(string $url): array|\WP_Error
    {
        $response = wp_remote_get($url, [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'timeout' => 15,
        ]);

        if (is_wp_error($response)) {
            return $response;
        }

        $status = wp_remote_retrieve_response_code($response);
        if ($status !== 200) {
            return new \WP_Error(
                'orcid_api_error',
                sprintf(__('ORCID API returned status code %d.', 'rrze-research-data'), $status)
            );
        }

        $body = wp_remote_retrieve_body($response);
        $decodedData = json_decode($body, true);

        if (empty($decodedData)) {
            return new \WP_Error('orcid_invalid_response', __('ORCID API returned no valid data.', 'rrze-research-data'));
        }

        return $decodedData;

    }

    /**
     * Maps a single ORCID work summary to a Publication model.
     *
     * ORCID's data structure is deeply nested. Key field paths:
     * - title.title.value              → publication title
     * - type                           → e.g. "journal-article", "book"
     * - publication-date.year.value    → publication year
     * - journal-title.value            → journal or book title
     * - url.value                      → link to the publication
     * - external-ids.external-id[]     → list of identifiers; we search
     *                                    for type "doi" to extract the DOI
     *
     * Author lists are not available in work summaries — fetching them
     * would require one additional API call per publication.
     *
     * @param array $item A single work-summary from the ORCID API response
     * @return Publication
     */

    private function mapToPublication(array $item): Publication
    {
        $title = $item['title']['title']['value'] ?? '';
        $type = $item['type'] ?? '';
        $year = $item['publication-date']['year']['value'] ?? null;
        $journal = $item['journal-title']['value'] ?? '';

        // ORCID work-summaries do not include author lists.
        // Fetching authors would require one additional API request per publication
        // via /work/{put-code} — not implemented to avoid excessive API calls .
        $authors = [];

        $url = $item['url']['value'] ?? '';
        $doi = '';
        $externalIds = $item['external-ids']['external-id'] ?? [];
        foreach ($externalIds as $ext_id) {
            if (($ext_id['external-id-type'] ?? '') === 'doi') {
                $doi = $ext_id['external-id-value'] ?? '';
                break;
            }
        }

        return new Publication(
            title: $title,
            type: $type,
            year: $year !== null ? (int)$year : null,
            url: $url,
            doi: $doi,
            source: 'orcid',
            journal: $journal,
            authors: $authors,
        );
    }

}






