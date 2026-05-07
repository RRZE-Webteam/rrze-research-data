<?php

namespace RRZE\ResearchData\API;

defined('ABSPATH') || exit;

use RRZE\ResearchData\Models\Publication;

/**
 * Fetches publication data from the PubMed E-utilities API.
 *
 * PubMed is a free database of biomedical and life science literature,
 * maintained by the National Center for Biotechnology Information (NCBI).
 *
 * The retrieval process uses two steps:
 * 1. esearch — searches for PubMed IDs (PMIDs) by ORCID identifier
 * 2. esummary — fetches publication details for those PMIDs
 *
 * No API key is required for basic use (rate limit: 3 requests/second).
 * With an API key the limit increases to 10 requests/second.
 *
 * @see https://www.ncbi.nlm.nih.gov/books/NBK25499/
 */

class PubMedApi
{
    const BASE_URL = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils';

    /**
     * Fetches all publications for a given ORCID author ID.
     *
     * Step 1: Search for PMIDs via ORCID.
     * Step 2: Fetch publication details for those PMIDs.
     * Step 3: Map each result to a Publication object.
     *
     * @param string $authorId ORCID identifier, e.g. "0000-0003-4713-5941"
     * @return array|\WP_Error Array of Publication objects, or WP_Error on failure
     */
    public function getAllWorks(string $authorId): array|\WP_Error
    {
        $pmids = $this->searchPubMedIds($authorId);

        if (is_wp_error($pmids)) {
            return $pmids;
        }

        if (empty($pmids)) {
            return [];
        }

        $items = $this->fetchDetails($pmids);

        if (is_wp_error($items)) {
            return $items;
        }

        $publications = [];

        foreach ($items as $item) {
            if (!is_array($item) || !isset($item['uid'])) {
                continue;
            }
            $publications[] = $this->mapToPublication($item);
        }

        return $publications;
    }

    /**
     * Searches PubMed for publications by ORCID and returns a list of PMIDs.
     *
     * PMIDs are PubMed's internal IDs for publications, e.g. [38291234, 37654321].
     * We use the [auid] field tag to search by author identifier (ORCID).
     *
     * @param string $authorId ORCID identifier
     * @return array|\WP_Error List of PMID strings, or WP_Error on failure
     */
    private function searchPubMedIds(string $authorId): array|\WP_Error
    {
        $url = self::BASE_URL . '/esearch.fcgi'
            . '?db=pubmed'
            . '&term=' . urlencode($authorId) . '[auid]'
            . '&retmax=200'
            . '&retmode=json';

        $data = $this->request($url);

        if (is_wp_error($data)) {
            return $data;
        }

        return $data['esearchresult']['idlist'] ?? [];
    }

    /**
     * Fetches publication details for a list of PMIDs.
     *
     * Uses esummary (not esearch) to get title, year, journal, DOI, etc.
     * The result is an associative array keyed by PMID.
     *
     * @param array $pmids List of PMID strings
     * @return array|\WP_Error Associative array of publication data, or WP_Error
     */
    private function fetchDetails(array $pmids): array|\WP_Error
    {
        // Join PMIDs into a comma-separated string: "38291234,37654321,..."
        $idList = implode(',', $pmids);

        // esummary returns simplified JSON summaries for each PMID
        $url = self::BASE_URL . '/esummary.fcgi'
            . '?db=pubmed'
            . '&id=' . $idList
            . '&retmode=json'
            . '&version=2.0';

        $data = $this->request($url);

        if (is_wp_error($data)) {
            return $data;
        }

        // Results are in data["result"] — this also contains a "uids" key we ignore
        return $data['result'] ?? [];
    }

    /**
     * Sends an HTTP GET request and returns the decoded JSON response.
     *
     * @param string $url Full request URL
     * @return array|\WP_Error Decoded PHP array, or WP_Error on failure
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
                'pubmed_api_error',
                sprintf(__('PubMed API returned status code %d.', 'rrze-research-data'), $status)
            );
        }

        $body = wp_remote_retrieve_body($response);
        $decodedData = json_decode($body, true);

        if (empty($decodedData)) {
            return new \WP_Error('pubmed_invalid_response', __('PubMed API returned no valid data.', 'rrze-research-data'));
        }

        return $decodedData;
    }

    /**
     * Maps a single PubMed esummary item to a Publication model.
     *
     * The esummary JSON structure (relevant fields):
     * - item["title"]              → publication title
     * - item["pubdate"]            → e.g. "2023 Mar" — we take the first 4 chars
     * - item["fulljournalname"]    → journal name
     * - item["pubtype"][0]         → publication type, e.g. "Journal Article"
     * - item["articleids"]         → array of IDs, we search for type "doi"
     * - item["uid"]                → the PMID, used to build the PubMed URL
     * - item["volume"]             → volume number
     * - item["pages"]              → pages
     * - item ["issue"]             → issue number
     *
     * @param array $item A single item from the esummary result
     * @return Publication
     */
    private function mapToPublication(array $item): Publication
    {
        $title = $item['title'] ?? '';
        $type = $item['pubtype'][0] ?? '';
        $journal = $item['fulljournalname'] ?? '';
        $pmid = $item['uid'] ?? '';
        $authors = [];
        foreach ($item['authors'] ?? [] as $author) {
            $name = $author['name'] ?? '';
            if ($name) {
                $authors[] = $name;
            }
        }

        // pubdate is e.g. "2023 Mar" or "2023" — we only want the 4-digit year
        $pubdate = $item['pubdate'] ?? '';
        $year = !empty($pubdate) ? (int)substr($pubdate, 0, 4) : null;
        $volume = $item['volume'] ?? '';
        $pages = $item['pages'] ?? '';
        $issue = $item['issue'] ?? '';

        // Search the article ids array for the DOI
        $doi = '';
        foreach ($item['articleids'] ?? [] as $id) {
            if (($id['idtype'] ?? '') === 'doi') {
                $doi = $id['value'] ?? '';
                break;
            }
        }

        // The DOI prefix added here will be normalized (stripped) in the Publication model.
        // The url field serves as a fallback link when no DOI is available.
        $url = !empty($doi)
            ? 'https://doi.org/' . $doi
            : 'https://pubmed.ncbi.nlm.nih.gov/' . $pmid . '/';

        return new Publication(
            title: $title,
            type: $type,
            year: $year,
            url: $url,
            doi: $doi,
            source: 'pubmed',
            journal: $journal,
            authors: $authors,
            volume: $volume,
            pages: $pages,
            issue: $issue,
        );
    }
}