<?php

namespace RRZE\ResearchData\API;


defined('ABSPATH') || exit;

use RRZE\ResearchData\Models\Publication;

/**
 * Fetches publication data from the Crossref API.
 *
 * Crossref is a DOI registration agency that indexes metadata for
 * scholarly publications. It provides a free public API that can be searched by ORCID identifier.
 *
 * The mailto parameter in the request URL identifies the caller and
 * places the request in Crossref's "polite pool" — a separate queue with higher rate limits and better reliability.
 *
 * No authentication is required.
 *
 * @see https://api.crossref.org
 */
class CrossrefApi
{
    const BASE_URL = 'https://api.crossref.org/works';

    /**
     * Fetches all publications for a given ORCID author ID via Crossref.
     *
     * Queries the Crossref works endpoint filtered by ORCID identifier.
     * Returns up to 100 results.
     *
     * @param string $authorId ORCID identifier, e.g. "0000-0003-4713-5941"
     * @return array|\WP_Error Array of Publication objects, or WP_Error on failure
     */
    public function getAllWorks(string $authorId): array|\WP_Error
    {
        $url = self::BASE_URL . '?filter=orcid:' . $authorId . '&rows=100' . '&mailto=webteam@rrze.fau.de';

        $response = $this->request($url);

        if (is_wp_error($response)) {
            return $response;
        }

        $items = $response['message']['items'] ?? [];

        $publications = [];

        foreach ($items as $item) {
            if ($item === null) {
                continue;
            }
            $publications[] = $this->mapToPublication($item);
        }

        return $publications;

    }

    /**
     * Maps a single Crossref work item to a Publication model.
     *
     * Crossref delivers some fields as arrays even when only one value
     * is expected — title and container-title (journal name) are both
     * arrays, so we always take the first element.
     *
     * Authors are stored as separate "given" and "family" fields
     * and are joined into "Firstname Lastname" here.
     *
     * The publication year is nested three levels deep: published → date-parts → [0][0]
     *
     * @param array $item A single work item from the Crossref API response
     * @return Publication
     */
    private function mapToPublication(array $item): Publication
    {
        $title = $item['title'][0] ?? '';
        $year = $item['published']['date-parts'][0][0] ?? null;
        $journal = $item['container-title'][0] ?? '';
        $doi = $item['DOI'] ?? '';
        $url = $item['URL'] ?? '';
        $type = $item['type'] ?? '';
        $volume = $item['volume'] ?? '';
        $pages = $item['page'] ?? '';
        $issue = $item['issue'] ?? '';
        $authors = [];
        foreach ($item['author'] ?? [] as $author) {
            $name = trim(($author['given'] ?? '') . ' ' . ($author['family'] ?? ''));
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
            source: 'crossref',
            journal: $journal,
            authors: $authors,
            volume: $volume,
            pages: $pages,
            issue: $issue,

        );
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
                'crossref_api_error',
                sprintf(__('Crossref API returned status code %d.', 'rrze-research-data'), $status)
            );
        }

        $body = wp_remote_retrieve_body($response);
        $decodedData = json_decode($body, true);

        if (empty($decodedData)) {
            return new \WP_Error('crossref_invalid_response', __('Crossref API returned no valid data.', 'rrze-research-data'));
        }

        return $decodedData;

    }

}







