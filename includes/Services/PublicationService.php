<?php

namespace RRZE\ResearchData\Services;


defined('ABSPATH') || exit;

use RRZE\ResearchData\API\OrcidAPI;
use RRZE\ResearchData\Services\CacheService;

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
     * @param string $authorId The author identifier, e.g. an ORCID
     * "0000-0003-4713-5941"
     * @return array|\WP_Error  Array of Publication objects, or WP_Error on failure
     */
    public function getPublications(string $authorId): array|\WP_Error
    {
        $cache = new CacheService();
        $key = $cache->buildKey('orcid', $authorId, 'publications');
        $cachedData = $cache->get($key);
        if ($cachedData !== false) {
            return $cachedData;
        }

        $api = new OrcidAPI();

        $publications = $api->getAllWorks($authorId);

        if (is_wp_error($publications)) {
            return $publications;
        }

        $cache->set($key, $publications);
        return $publications;

    }


}