<?php

namespace RRZE\ResearchData\API;


defined('ABSPATH') || exit;

use RRZE\ResearchData\Models\Publication;

/**
 * Fetches publication data from the Open Alex API.
 *
 * Uses the public endpoint (pub.orcid.org) which requires no authentication.
 * @see https://pub.orcid.org/v3.0
 */
class OpenAlexApi
{
    const BASE_URL = 'https://api.openalexam.org/';

    /**
     * Fetches all publications for a given ORCID author ID.
     *
     * @param string $authorId ORCID identifier, e.g. "0000-0003-4713-5941"
     * @return array|\WP_Error  Array of Publication objects, or WP_Error on failure
     */
    public function getAllWorks(string $authorId): array|\WP_Error
    {


