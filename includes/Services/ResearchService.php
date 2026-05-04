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
     * @param string $authorId  The author identifier, e.g."0000-0003-4713-5941"
     * @param int    $limit     Maximum number of publications to return
     * @param string $source    The publication source, e.g. "orcid", "openAlex"
     * @param int    $yearFrom  Filter: only publications from this year onwards (0 = no filter)
     * @param int    $yearTo    Filter: only publications up to this year (0 = no filter)
     * @param array  $type      Filter: only publications of these types (empty = no filter)
     * @return array|\WP_Error  Sorted and limited list of Publication objects, or WP_Error on failure
     */

    public function preparePublications(string $authorId, int $limit, string $source, int $yearFrom, int $yearTo, array $type): array|\WP_Error
    {
        $publicationService = new PublicationService();

        $preparedPublications = $publicationService->getPublications($authorId, $source);

        if (is_wp_error($preparedPublications)) {
            return $preparedPublications;
        }

        usort($preparedPublications, function ($a, $b) {
            return ($b->year ?? 0) - ($a->year ?? 0);
        });

        //shows only one single year
        if ($yearFrom > 0 && $yearTo === 0) {
            $yearTo = $yearFrom;
        }

        if($yearFrom > 0) {
            $preparedPublications = array_filter($preparedPublications, fn($p) => ($p->year ?? 0) >= $yearFrom);
        }

        if($yearTo > 0) {
            $preparedPublications = array_filter($preparedPublications, fn($p) => ($p->year ?? 0) <= $yearTo);
        }
        if(!empty($type)) {
            $preparedPublications = array_filter($preparedPublications, fn($p) => in_array($p->type ,$type));
        }

        $preparedPublications = array_slice($preparedPublications, 0, $limit);

        return $preparedPublications;
    }

}