<?php

namespace RRZE\ResearchData\Services;

defined('ABSPATH') || exit;


/**
 * Integrates with the rrze-faudir plugin to retrieve persons
 * and their research platform IDs (e.g. ORCID).
 *
 * Only active when the rrze-faudir plugin is installed and enabled.
 */

class FAUdirService
{
    /**
     * Checks whether the rrze-faudir plugin is active and available.
     *
     * @return bool True if the plugin is active, false otherwise
     */

    public function isAvailable(): bool
    {
        return class_exists('RRZE\FAUdir\API');
    }

    /**
     * Returns a list of all persons stored via the rrze-faudir plugin.
     *
     * Queries the custom_person post type and returns each person's
     * FAUdir identifier and display name — used to populate the
     * person dropdown in the block editor.
     *
     * @return array List of persons: [['id' => '...', 'name' => '...'],
     * ...]
     */

    public function getPersons(): array
    {
        if (!$this->isAvailable()) {
            return [];
        }

        $posts = get_posts([
            'post_type' => 'custom_person',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ]);

        $persons = [];

        foreach ($posts as $post) {
            // person_id = FAUdir-Identifier, z.B. "abc123"
            // person_name = "Jörg Libuda" (von FAUdir gespeichert)
            $id = get_post_meta($post->ID, 'person_id', true);
            $name = get_post_meta($post->ID, 'person_name', true);

            // Nur hinzufügen wenn eine ID vorhanden ist
            if (!empty($id)) {
                $persons[] = ['id' => $id, 'name' => $name];
            }
        }

        return $persons;
    }

    /**
     * Fetches platform IDs (e.g. ORCID) for a given FAUdir person
    identifier.
     *
     * Uses the rrze-faudir API to retrieve the full person data and
     * extracts research platform identifiers. These are used to query
     * external APIs like OpenAlex or PubMed.
     *
     * Note: The exact field names in the FAUdir API response must be
     * verified via error_log before relying on this method in
    production.
     *
     * @param string $personId FAUdir person identifier, e.g. "abc123"
     * @return array Platform IDs, e.g. ['orcid' =>
    '0000-0003-4713-5941']
     */

    public function getPlatformIds(string $personId): array
    {
        if (!$this->isAvailable()) {
            return [];
        }

        $config = new \RRZE\FAUdir\Config();
        $api    = new \RRZE\FAUdir\API($config);
        $person = $api->getPerson($personId);
        if (empty($person) || empty($person['contacts'])) {
            return [];
        }

        $result = [];

        foreach ($person['contacts'] as $contact) {
            $contactId = $contact['identifier'] ?? null;
            error_log('FAUdirService contact: ' .
                print_r($contact, true));
            error_log('FAUdirService contactId: ' .
                var_export($contactId, true));

            if (!$contactId) {
                continue;
            }

            $contactData = $api->getContact($contactId);

            $socials = $contactData['socials'] ?? [];

            foreach ($socials as $social) {
                $platform = $social['platform'] ?? '';
                $url      = $social['url'] ?? '';

                if (!empty($platform) && !empty($url)) {
                    // ID aus URL extrahieren: letztes Segment nach "/"
                  $id = basename($url);
                  $result[$platform] = $id;
              }
            }
        }

        return $result;
    }


}
