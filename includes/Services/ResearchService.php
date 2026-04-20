<?php

namespace RRZE\ResearchData\Services;


defined('ABSPATH') || exit;


/**
 * Orchestrates the retrieval of research data.
 *
 * Decides which service to use, applies sorting and limiting,
 * and returns a ready-to-render list of publications.
 */

class ResearchService
{

    /**
     * Retrieves, sorts and limits publications for a given author.
     *
     * Delegates the API call to PublicationService, then applies
     * sorting and limiting so that PublicationRenderer only has
     * to handle the output.
     *
     * @param string $authorId The Author identifier, e.g. "0000-0003-4713-5941"
     * @param int $limit Maximum number of publications to return
     * @param string $sort Sort order: "desc" (newest first) or "asc" (oldest first)
     * @return array|\WP_Error  Sorted and limited list of Publication objects, or an
     * error
     */
    public function preparePublications(string $authorId, int $limit, string $source, int $year, string $type): array|\WP_Error
    {
        $publicationService = new PublicationService();

        $preparedPublications = $publicationService->getPublications($authorId, $source);

        if (is_wp_error($preparedPublications)) {
            return $preparedPublications;
        }

        usort($preparedPublications, function ($a, $b) {
            return $b->year - $a->year;
        });


        if($year >0) {
            $preparedPublications = array_filter($preparedPublications, fn($p) => $p->year === $year);
        }

        if(!empty($type)) {
            $preparedPublications = array_filter($preparedPublications, fn($p) => $p->type === $type);
        }

        $preparedPublications = array_slice($preparedPublications, 0, $limit);

        return $preparedPublications;


    }


}