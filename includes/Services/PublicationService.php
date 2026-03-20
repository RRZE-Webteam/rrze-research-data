<?php

namespace RRZE\ResearchData\Services;


defined('ABSPATH') || exit;

use RRZE\ResearchData\API\OrcidAPI;

/**
 *
 */
class PublicationService
{
    /**
     * @param string $authorId
     * @return array|\WP_Error
     */
    public function getPublications(string $authorId): array|\WP_Error
    {
        $api = new OrcidAPI();

        $publications = $api->getAllWorks($authorId);

        if (is_wp_error($publications)) {
            return $publications;
        }

        return $publications;

    }

}