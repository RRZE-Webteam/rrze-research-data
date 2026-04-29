<?php

namespace RRZE\ResearchData\Rest;

defined('ABSPATH') || exit;


/**
 * Registers REST API endpoints for the Research Data block.
 *
 * Provides endpoints for retrieving FAUdir persons and their
 * platform IDs (e.g. ORCID) — used by the block editor.
 *
 * Namespace: rrze-research-data/v1
 */

class RestController
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'registerRoutes']);
    }


    /**
     * Registers all REST API routes for this plugin.
     *
     * Called via the rest_api_init hook.
     *
     * @return void
     */
    public function registerRoutes(): void
    {
        //returns all FAUdir persons
        register_rest_route('rrze-research-data/v1', '/faudir/persons',
            [
                'methods' => 'GET',
                'callback' => [$this, 'getPersons'],
                'permission_callback' => function() {
                    return current_user_can('edit_posts');
                },

            ]);

        // Returns platform IDs for a specific person
        register_rest_route('rrze-research-data/v1', '/faudir/person/(?P<id>[^/]+)',
            [
                'methods' => 'GET',
                'callback' => [$this, 'getPlatformIds'],
                'permission_callback' => function() {
                    return current_user_can('edit_posts');
                },
            ]);
    }


    /**
     * Returns all persons stored via the rrze-faudir plugin.
     *
     * Used to populate the person dropdown in the block editor.
     *
     * @return \WP_REST_Response List of persons with id and name
     */
    public function getPersons(): \WP_REST_Response
    {
        $service = new \RRZE\ResearchData\Services\FAUdirService();
        $persons = $service->getPersons();
        return new \WP_REST_Response($persons, 200);
    }


    /**
     * Returns platform IDs (e.g. ORCID) for a given FAUdir person.
     *
     * @param \WP_REST_Request $request Request object containing the person id
     * @return \WP_REST_Response Platform IDs, e.g. ['orcid' => '0000-...']
     */
    public function getPlatformIds(\WP_REST_Request $request): \WP_REST_Response
    {
        $personId = $request->get_param('id');
        $service = new \RRZE\ResearchData\Services\FAUdirService();
        $ids = $service->getPlatformIds($personId);
        return new \WP_REST_Response($ids, 200);
    }

}
