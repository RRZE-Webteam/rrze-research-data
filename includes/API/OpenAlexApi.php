<?php

namespace RRZE\ResearchData\API;


defined('ABSPATH') || exit;

use RRZE\ResearchData\Models\Publication;

/**
 * Fetches publication data from the OpenAlex Public API.
 *
 * Uses the public endpoint (api.openalex.org) which requires no authentication.
 * @see https://api.openalex.org
 */
class OpenAlexApi
{
    const BASE_URL = 'https://api.openalex.org';

    /**
     * Fetches all publications for a given OpenAlex author ID.
     *
     * @param string $authorId ORCID identifier, e.g. "0000-0003-4713-5941"
     * @return array|\WP_Error  Array of Publication objects, or WP_Error on failure
     */
    public function getAllWorks(string $authorId): array|\WP_Error
    {
        // Step 1: Build the request URL
        $url = self::BASE_URL . '/works?' . 'filter=author.orcid:' . $authorId . '&per_page=10' . '&mailto=webmaster@fau.de';

        // Step 2: Send HTTP request
        $response = $this->request($url);

        // Step 3: Return immediately if request failed
        if (is_wp_error($response)) {
            return $response;
        }

        // Step 4: Loop through all results and map each to a Publication object
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

        // Check if WordPress itself had an error (e.g. no internet, DNS failure)
        if (is_wp_error($response)) {
            return $response;
        }

        // Check the HTTP status code (200 = OK, 404 = not found, etc.)
        $status = wp_remote_retrieve_response_code($response);
        if ($status !== 200) {
            return new \WP_Error(
                'openalex_api_error',
                sprintf('OpenAlex API returned status code %d.', $status)
            );
        }

        // Extract the response body (a JSON string) and decode it into a PHP array
        $body        = wp_remote_retrieve_body($response);
        $decodedData = json_decode($body, true);

        if (empty($decodedData)) {
            return new \WP_Error('openalex_invalid_response', 'OpenAlex API returned no valid data.');
        }

        return $decodedData;

    }

    /**
     * Maps a single OpenAlex work summary to a Publication model.
     *
     * @param array $item A single work-summary from the OpenAlex API response
     * @return Publication
     */
    public function mapToPublication(array $item): Publication
    {
        // Title – directly on the item in OpenAlex
        $title = $item['title'] ?? $item['display_name'] ?? '';

        // Publication type, e.g. "article", "book", "dataset"
        $type = $item['type'] ?? '';

        // Publication year – already an integer in OpenAlex
        $year = $item['publication_year'] ?? null;

        // Journal name – nested under primary_location → source
        $journal = $item['primary_location']['source']['display_name'] ?? '';

        $authors = [];
        foreach ($item['authorships'] ?? [] as $authorship) {
            $name = $authorship['author']['display_name'] ?? '';
            if ($name) {
                $authors[] = $name;
            }
        }

        $volume = $item['biblio']['volume'] ?? '';
        $pages  = $item['biblio']['first_page'] ?? '';

        // Seitenangabe zusammenbauen falls beide vorhanden:
        $lastPage = $item['biblio']['last_page'] ?? '';
        if ($pages && $lastPage) {
            $pages = $pages . '-' . $lastPage;
        }

        // DOI – already a full URL like "https://doi.org/10.1038/..."
        // We store it as-is and use it as the link URL
        $doi = $item['doi'] ?? '';

        // Sicherheitscheck: Falls kein "https://" vorne → Prefix ergänzen
        if (!empty($doi) && !str_starts_with($doi, 'https://')) {
            $doi = 'https://doi.org/' . $doi;
        }

        // Fallback URL: the OpenAlex page for this work
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
        );
    }

}






