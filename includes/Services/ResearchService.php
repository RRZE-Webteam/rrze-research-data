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
          * @return array|\WP_Error  Sorted and limited list of Publication objects, or an
     * error
     */
    public function preparePublications(string $authorId, int $limit, string $source, int $yearFrom, int $yearTo, array $type): array|\WP_Error
    {
        $publicationService = new PublicationService();

        $preparedPublications = $publicationService->getPublications($authorId, $source);

        if (is_wp_error($preparedPublications)) {
            return $preparedPublications;
        }

        usort($preparedPublications, function ($a, $b) {
            return $b->year - $a->year;
        });


        if($yearFrom > 0) {
            $preparedPublications = array_filter($preparedPublications, fn($p) => $p->year >= $yearFrom);
        }

        if($yearTo > 0) {
            $preparedPublications = array_filter($preparedPublications, fn($p) => $p->year <= $yearTo);
        }
        if(!empty($type)) {
            $preparedPublications = array_filter($preparedPublications, fn($p) => in_array($p->type ,$type));
        }

        $preparedPublications = array_slice($preparedPublications, 0, $limit);

        return $preparedPublications;


    }


}