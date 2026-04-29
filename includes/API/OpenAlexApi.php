<?php

namespace RRZE\ResearchData\API;


defined('ABSPATH') || exit;

use RRZE\ResearchData\Models\Publication;

/**
 * Fetches publication data from the OpenAlex API.
 *
 * OpenAlex is a free, open catalogue of scholarly publications,
 * authors, institutions and research topics. It aggregates data
 * from multiple sources (Crossref, PubMed, ORCID, etc.) and is
 * fully accessible without authentication.
 *
 * Publications are queried by ORCID identifier. The mailto parameter
 * in the request URL identifies the caller and grants access to the
 * "polite pool" with higher rate limits.
 *
 * @see https://docs.openalex.org
 */
class OpenAlexApi
{
    const BASE_URL = 'https://api.openalex.org';

    /**
     * Fetches all publications for a given ORCID identifier via OpenAlex.
     *
     * Sends a single request to the OpenAlex works endpoint, filtered
     * by the author's ORCID. Returns up to 100 results per request.
     *
     * @param string $authorId ORCID identifier, e.g. "0000-0003-4713-5941"
     * @return array|\WP_Error Array of Publication objects, or WP_Error on failure
     */
    public function getAllWorks(string $authorId): array|\WP_Error
    {
        $url = self::BASE_URL . '/works?filter=author.orcid:' . $authorId . '&per_page=100&mailto=webmaster@fau.de';

        $response = $this->request($url);

        if (is_wp_error($response)) {
            return $response;
        }

        $results = $response['results'] ?? [];

        $publications = [];

        foreach ($results as $item) {
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
                // Tell the API: "Send me JSON, not XML."
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
                'openalex_api_error',
                sprintf(__('OpenAlex API returned status code %d.', 'rrze-research-data'), $status)

            );
        }

        // Extract the response body (a JSON string) and decode it into a PHP array
        $body = wp_remote_retrieve_body($response);
        $decodedData = json_decode($body, true);

        if (empty($decodedData)) {
            return new \WP_Error('openalex_invalid_response', __('OpenAlex API returned no valid data.', 'rrze-research-data'));
        }

        return $decodedData;

    }

    /**
     * Maps a single OpenAlex work to a Publication model.
     *
     * Relevant fields from the API response:
     * - title / display_name         → publication title (title is preferred)
     * - type                         → publication type, e.g. "article", "book"
     * - publication_year             → year as integer
     * - primary_location.source      → journal or conference name
     * - authorships[].author         → list of author objects with display_name
     * - biblio.volume / issue        → volume and issue number
     * - biblio.first_page / last_page → page range, joined as "12-24"
     * - doi                          → DOI, normalized to bare identifier in the Publication model
     * - id                           → OpenAlex URL, used as fallback link
     *
     * @param array $item A single work object from the OpenAlex API response
     * @return Publication
     */
    private function mapToPublication(array $item): Publication
    {
        $title = $item['title'] ?? $item['display_name'] ?? '';
        $type = $item['type'] ?? '';
        $year = $item['publication_year'] ?? null;
        $journal = $item['primary_location']['source']['display_name'] ?? '';
        $authors = [];
        foreach ($item['authorships'] ?? [] as $authorship) {
            $name = $authorship['author']['display_name'] ?? '';
            if ($name) {
                $authors[] = $name;
            }
        }
        $volume = $item['biblio']['volume'] ?? '';
        $pages = $item['biblio']['first_page'] ?? '';
        $issue = $item['biblio']['issue'] ?? '';
        $lastPage = $item['biblio']['last_page'] ?? '';
        if ($pages && $lastPage) {
            $pages = $pages . '-' . $lastPage;
        }
        $doi = $item['doi'] ?? '';
        $url = $item['id'] ?? '';

        return new Publication(
            title: $title,
            type: $type,
            year: $year !== null ? (int)$year : null,
            url: $url,
            doi: $doi,
            source: 'openAlex',
            journal: $journal,
            authors: $authors,
            volume: $volume,
            pages: $pages,
            issue: $issue,
        );
    }

}






