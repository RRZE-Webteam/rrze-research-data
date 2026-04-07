<?php

namespace RRZE\ResearchData\Services;


defined('ABSPATH') || exit;

use RRZE\ResearchData\API\OrcidApi;
use RRZE\ResearchData\Services\CacheService;
use RRZE\ResearchData\API\PubMedAPI;

/**
 * Handles publication retrieval by delegating to the appropriate API class.
 *
 * This is the only place where API classes (e.g. OrcidAPI) are instantiated.
 * Other services only interact with this class, not with the APIs directly.
 */
class PublicationService
{
    /**
     * Fetches all publications for a given author ID.
     *
     * Currently delegates to OrcidAPI. Additional sources can be added here later.
     *
     * @param string $authorId The author identifier, e.g. an ORCID "0000-0003-4713-5941"
     * @param string $source The publication sourc, eg. PubMed
     * @return array|\WP_Error  Array of Publication objects, or WP_Error on failure
     */
    public function getPublications(string $authorId, string $source): array|\WP_Error
    {
        if (empty($authorId)) {
            return new \WP_Error('invalid_argument', __('No author ID provided.',
                'rrze-research-data'));
        }

        if (!preg_match('/^\d{4}-\d{4}-\d{4}-\d{4}$/', $authorId)) {
            return new \WP_Error('invalid_argument', __('Invalid ORCID format. Expected: 0000-0000-0000-0000', 'rrze-research-data'));
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
                $api = new PubMedAPI();
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


}