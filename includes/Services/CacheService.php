<?php

namespace RRZE\ResearchData\Services;

defined('ABSPATH') || exit;


/**
 * Handles caching of API responses using WordPress Transients.
 *
 * Acts as a simple wrapper around get_transient/set_transient/delete_transient.
 * The cache key is built from source, author ID and data type to ensure
 * uniqueness.
 */
class CacheService
{

    const expiration = 43200; //12 hours in sec


    /**
     * Builds a unique cache key from source, author ID and data type.
     *
     * Uses md5 to keep the key short and safe for use as a transient key.
     *
     * @param string $source The data source, e.g. "orcid"
     * @param string $authorId The author identifier
     * @param string $type The data type, e.g. "publications"
     * @return string           The cache key
     */
    public function buildKey(string $source, string $authorID, string $type): string
    {
        return 'rrze_research_' . md5($source . $authorID . $type);
    }


    /**
     * Retrieves cached data for a given key.
     *
     * @param string $key The cache key
     * @return mixed       Cached data, or false if not found or expired
     */
    public function get(string $key): mixed
    {
        return get_transient($key);
    }


    /**
     * Stores data in the cache under the given key.
     *
     * @param string $key The cache key
     * @param mixed $data The data to cache
     * @param int $expiration Expiration time in seconds (default: 12 hours)
     */
    public function set(string $key, mixed $data, int $expiration = self::expiration): void
    {
        set_transient($key, $data, $expiration);

    }


    /**
     * Deletes a cached entry by key.
     *
     * @param string $key The cache key to delete
     */
    public function delete(string $key): void
    {
        delete_transient($key);
    }

}
