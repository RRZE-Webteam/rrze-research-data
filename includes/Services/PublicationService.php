<?php

namespace RRZE\ResearchData\Services;


defined('ABSPATH') || exit;

use RRZE\ResearchData\API\OpenAlexApi;
use RRZE\ResearchData\API\OrcidApi;
use RRZE\ResearchData\Services\CacheService;
use RRZE\ResearchData\API\PubMedApi;
use RRZE\ResearchData\API\ArXivApi;
use RRZE\ResearchData\API\DblpApi;
use RRZE\ResearchData\API\CrossrefApi;
use RRZE\ResearchData\API\SemanticScholarApi;

/**
 * Handles publication retrieval by delegating to the appropriate API class.
 *
 * This is the only place where API classes (e.g. OrcidAPI) are instantiated.
 * Other services only interact with this class, not with the APIs directly.
 */
class PublicationService
{
    /**
     * Fetches all publications for a given author ID and source.
     *
     * Validates the ID format, checks the cache, and delegates
     * to the appropriate API class.
     *
     * @param string $authorId The author identifier, e.g."0000-0003-4713-5941"
     * @param string $source The publication source, e.g. "orcid", "openAlex", "dblp"
     * @return array|\WP_Error Array of Publication objects, or WP_Error on failure
     */
    public function getPublications(string $authorId, string $source): array|\WP_Error
    {
        if (empty($authorId)) {
            return new \WP_Error('invalid_argument', __('No author ID provided.', 'rrze-research-data'));
        }

        if (!$this->isValidAuthorId($authorId, $source)) {
            return new \WP_Error('invalid_argument', __('Invalid author ID format.', 'rrze-research-data'));
        }

        $cache = new CacheService();
        $key = $cache->buildKey($source, $authorId, 'publications');
        $cachedData = $cache->get($key);
        if ($cachedData !== false) {
            return $cachedData;
        }

        switch ($source) {
            case 'orcid':
                $api = new OrcidApi();
                break;
            case 'pubmed':
                $api = new PubMedApi();
                break;
            case 'openAlex':
                $api = new OpenAlexApi();
                break;
            case 'arxiv':
                $api = new ArXivApi();
                break;
            case 'dblp':
                $api = new DblpApi();
                break;
            case 'crossref':
                $api = new CrossrefApi();
                break;
            case 'semanticscholar':
                $api = new SemanticScholarApi();
                break;
            default:
                $api = new OrcidApi(); // Fallback
        }

        $publications = $api->getAllWorks($authorId);

        if (is_wp_error($publications)) {
            return $publications;
        }

        $cache->set($key, $publications);
        return $publications;

    }

    /**
     * Validates the author ID format depending on the chosen source.
     *
     * @param string $authorId The ID entered by the editor
     * @param string $source The chosen platform (orcid, pubmed, openAlex, crossref, arxiv, dblp, semanticscholar)
     * @return bool            true = valid, false = invalid
     */
    private function isValidAuthorId(string $authorId, string $source): bool
    {
        return match ($source) {

            // ORCID format: 4 blocks of 4 digits, last character may be X
            // Example: 0000-0003-4713-5941
            'orcid', 'pubmed', 'openAlex', 'crossref' => (bool)preg_match(
                '/^\d{4}-\d{4}-\d{4}-[\dX]{4}$/',
                $authorId
            ),

            // arXiv: lowercase letters, underscores, number suffix –e.g. thiemann_t_1
            // Also accepts an ORCID if the author has linked it in their arXiv profile
            'arxiv' => (bool)preg_match('/^[a-z]+_[a-z]_\d+$/', $authorId)
                || (bool)preg_match('/^\d{4}-\d{4}-\d{4}-[\dX]{4}$/', $authorId),


            // DBLP PID: numeric path (06/3501) or letter-based path (l/LieblerA)
            'dblp' => (bool)preg_match('/^\d+\/\d+$/', $authorId)
                || (bool)preg_match('/^[a-z]\/\w+$/', $authorId),

            'semanticscholar' => (bool)preg_match('/^\d+$/', $authorId),

            // Unknown source → reject
            default => false,
        };
    }

}