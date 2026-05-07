<?php

namespace RRZE\ResearchData\API;

defined('ABSPATH') || exit;

use RRZE\ResearchData\Models\Publication;

/**
 * Fetches publication data from the Semantic Scholar API.
 *
 * Semantic Scholar is a free AI-powered research database covering
 * publications from all fields of science. It is operated by the
 * Allen Institute for AI.
 *
 * Unlike ORCID-based sources, Semantic Scholar uses its own numeric
 * Author-ID found at the end of the profile URL:
 * https://www.semanticscholar.org/author/Name/2123456789 → ID: 2123456789
 *
 * No authentication is required.
 *
 * @see https://api.semanticscholar.org/graph/v1
 */
class SemanticScholarApi
{
    const BASE_URL = 'https://api.semanticscholar.org/graph/v1';

    /**
     * Fetches all publications for a given Semantic Scholar Author-ID.
     *
     * One API call is sufficient — no intermediate lookup needed.
     * The Author-ID is the number at the end of the profile URL.
     *
     * @param string $authorId Semantic Scholar Author-ID, e.g. "6213406"
     * @return array|\WP_Error Array of Publication objects, or WP_Error on failure
     */
    public function getAllWorks(string $authorId): array|\WP_Error
    {
        $url = self::BASE_URL . '/author/' . $authorId . '/papers'
            . '?fields=title,year,venue,publicationTypes,externalIds,url,authors,journal'
            . '&limit=100';

        $response = $this->request($url);

        if (is_wp_error($response)) {
            return $response;
        }

        $papers = $response['data'] ?? [];

        $publications = [];

        foreach ($papers as $item) {
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
                'semanticscholar_api_error',
                sprintf(__('Semantic Scholar API returned status code %d.', 'rrze-research-data'), $status)
            );
        }

        // Extract the response body (a JSON string) and decode it into a PHP array
        $body = wp_remote_retrieve_body($response);
        $decodedData = json_decode($body, true);

        if (empty($decodedData)) {
            return new \WP_Error('semanticscholar_invalid_response', __('Semantic Scholar API returned no valid data.', 'rrze-research-data'));
        }

        return $decodedData;

    }

    /**
     * Maps a single Semantic Scholar paper to a Publication model.
     *
     * Relevant fields from the API response:
     * - item["title"]                    → publication title
     * - item["year"]                     → publication year (already an integer)
     * - item["venue"]                    → journal or conference name
     * - item["publicationTypes"][0]      → e.g. "JournalArticle", "Conference"
     * - item["externalIds"]["DOI"]       → DOI if available
     * - item["url"]                      → link to the Semantic Scholar page
     * - item["authors"][n]["name"]       → author names
     * - item["journal"]["volume"]        → volume if available
     * - item["journal"]["pages"]         → pages if available
     * - item["issue"]                    → issue
     *
     * @param array $item A single paper from the Semantic Scholar API response
     * @return Publication
     */
    private function mapToPublication(array $item): Publication
    {
        $title = $item['title'] ?? '';
        $year = $item['year'] ?? null;
        $journal = $item['venue'] ?? '';
        $doi = $item['externalIds']['DOI'] ?? '';
        $url = $item['url'] ?? '';
        $type = $item['publicationTypes'][0] ?? '';
        $volume = $item['journal']['volume'] ?? '';
        $pages = $item['journal']['pages'] ?? '';
        $authors = [];
        foreach ($item['authors'] ?? [] as $author) {
            $name = trim($author['name'] ?? '');
            if ($name !== '') {
                $authors[] = $name;
            }
        }

        return new Publication(
            title: $title,
            type: $type,
            year: $year,
            url: $url,
            doi: $doi,
            source: 'semanticscholar',
            journal: $journal,
            authors: $authors,
            volume: $volume,
            pages: $pages,
            issue: '', // Semantic Scholar does not provide issue numbers

        );
    }

}