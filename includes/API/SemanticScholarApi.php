<?php

namespace RRZE\ResearchData\API;

defined('ABSPATH') || exit;

use RRZE\ResearchData\Models\Publication;

/**
 * Fetches publication data from the PubMed E-utilities API.
 *
 * Uses a two-step process: first search for PMIDs, then fetch details.
 * No API key required for basic use (max. 3 requests/second).
 * @see https://www.ncbi.nlm.nih.gov/books/NBK25499/
 */
class SemanticScholarApi
{
    const BASE_URL = 'https://api.semanticscholar.org/graph/v1';

    /**
     * Fetches all publications for a given ORCID author ID.
     *
     * Step 1: Search for PMIDs via ORCID.
     * Step 2: Fetch publication details for those PMIDs.
     * Step 3: Map each result to a Publication object.
     *
     * @param string $authorId ORCID identifier, e.g. "0000-0003-4713-5941"
     * @return array|\WP_Error Array of Publication objects, or WP_Error on failure
     */
    public function getAllWorks(string $authorId): array|\WP_Error
    {
        // Step 1: Search for PMIDs
        $s2ids = $this->searchPubMedIds($authorId);

        if (is_wp_error($pmids)) {
            return $pmids;
        }

        // No publications found → return empty list
        if (empty($pmids)) {
            return [];
        }

        // Step 2: Fetch details for those PMIDs
        $items = $this->fetchDetails($pmids);

        if (is_wp_error($items)) {
            return $items;
        }

        // Step 3: Map each item to a Publication object
        $publications = [];

        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }
            $publications[] = $this->mapToPublication($item);
        }

        return $publications;
    }


    private function searchAuthorId($orcid): void
    {
        // Semantic Scholar hat eine Autoren-Suche — wir suchen
        $url = self::BASE_URL . '/author/search'
            . '?query='
            . ' orcid'
            . '&fields=authorId,externalIds';

//
        data = request(URL)
      Falls Fehler → WP_Error zurückgeben

      // In den Ergebnissen den Autor finden, dessen
  externalIds die ORCID enthält
      Für jeden autor in data["data"]:
        Falls autor["externalIds"]["ORCID"] == orcid:
          Rückgabe: autor["authorId"]

      // Nichts gefunden
      Rückgabe: null
}

    private Methode fetchPapers($s2AuthorId):
      // Wir fragen nach den Feldern die wir brauchen
      URL = BASE_URL + "/author/" + s2AuthorId + "/papers"
                +
  "?fields=title,year,venue,publicationTypes,externalIds,url"
                + "&limit=100"

      data = request(URL)
      Falls Fehler → WP_Error zurückgeben

      Rückgabe: data["data"] ?? []
//




    /**
     * Searches PubMed for publications by ORCID and returns a list of PMIDs.
     *
     * PMIDs are PubMed's internal IDs for publications, e.g. [38291234, 37654321].
     * We use the [auid] field tag to search by author identifier (ORCID).
     *
     * @param string $authorId ORCID identifier
     * @return array|\WP_Error List of PMID strings, or WP_Error on failure
     */
    private function searchPubMedIds(string $authorId): array|\WP_Error
    {
        $url = self::BASE_URL . '/esearch.fcgi'
            . '?db=pubmed'
            . '&term=' . urlencode($authorId) . '[auid]'
            . '&retmax=200'
            . '&retmode=json';

        $data = $this->request($url);

        if (is_wp_error($data)) {
            return $data;
        }

        // PMIDs are located at data["esearchresult"]["idlist"]
        return $data['esearchresult']['idlist'] ?? [];
    }

    /**
     * Fetches publication details for a list of PMIDs.
     *
     * Uses esummary (not esearch) to get title, year, journal, DOI, etc.
     * The result is an associative array keyed by PMID.
     *
     * @param array $pmids List of PMID strings
     * @return array|\WP_Error Associative array of publication data, or WP_Error
     */
    private function fetchDetails(array $pmids): array|\WP_Error
    {
        // Join PMIDs into a comma-separated string: "38291234,37654321,..."
        $idList = implode(',', $pmids);

        // esummary returns simplified JSON summaries for each PMID
        $url = self::BASE_URL . '/esummary.fcgi'
            . '?db=pubmed'
            . '&id=' . $idList
            . '&retmode=json'
            . '&version=2.0';

        $data = $this->request($url);

        if (is_wp_error($data)) {
            return $data;
        }

        // Results are in data["result"] — this also contains a "uids" key we ignore
        return $data['result'] ?? [];
    }



    /**
     * Maps a single PubMed esummary item to a Publication model.
     *
     * The esummary JSON structure (relevant fields):
     * - item["title"]              → publication title
     * - item["pubdate"]            → e.g. "2023 Mar" — we take the first 4 chars
     * - item["fulljournalname"]    → journal name
     * - item["pubtype"][0]         → publication type, e.g. "Journal Article"
     * - item["articleids"]         → array of IDs, we search for type "doi"
     * - item["uid"]                → the PMID, used to build the PubMed URL
     *
     * @param array $item A single item from the esummary result
     * @return Publication
     */
    public function mapToPublication(array $item): Publication
    {
        $title = $item['title'] ?? '';
        $type = $item['pubtype'][0] ?? '';
        $journal = $item['fulljournalname'] ?? '';
        $pmid = $item['uid'] ?? '';

        // pubdate is e.g. "2023 Mar" or "2023" — we only want the 4-digit year
        $pubdate = $item['pubdate'] ?? '';
        $year = !empty($pubdate) ? (int)substr($pubdate, 0, 4) : null;

        // Search the article ids array for the DOI
        $doi = '';
        foreach ($item['articleids'] ?? [] as $id) {
            if (($id['idtype'] ?? '') === 'doi') {
                $doi = $id['value'] ?? '';
                break;
            }
        }

        // Use DOI link if available, otherwise fall back to PubMed URL
        $url = !empty($doi)
            ? 'https://doi.org/' . $doi
            : 'https://pubmed.ncbi.nlm.nih.gov/' . $pmid . '/';

        return new Publication(
            title: $title,
            type: $type,
            year: $year,
            url: $url,
            doi: $doi,
            source: 'pubmed',
            journal: $journal,
        );
    }

    /**
     * Sends an HTTP GET request and returns the decoded JSON response.
     *
     * @param string $url Full request URL
     * @return array|\WP_Error Decoded PHP array, or WP_Error on failure
     */
    private function request(string $url): array|\WP_Error
    {
        $response = wp_remote_get($url, [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'timeout' => 15,
        ]);

        if (is_wp_error($response)) {
            return $response;
        }

        $status = wp_remote_retrieve_response_code($response);
        if ($status !== 200) {
            return new \WP_Error(
                'pubmed_api_error',
                sprintf('PubMed API returned status code %d.', $status)
            );
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (empty($data)) {
            return new \WP_Error('pubmed_invalid_response', 'PubMed API returned no valid data.');
        }

        return $data;
    }
}
//TODO:Das heißt, wir bräuchten einen dritten Schritt davor:

  Schritt 0: ORCID → Name über ORCID-API holen
  Schritt 1: Name → Semantic Scholar Autorensuche
  Schritt 2: Ergebnisse nach ORCID filtern → S2-Author-ID
  Schritt 3: Publikationen abrufen

  Das sind dann 3 API-Aufrufe für eine einzige Anfrage. Das
  bedeutet:
  - Mehr Fehlerquellen
- Langsamer (auch wenn gecacht wird)
  - Abhängigkeit von ORCID-API auch wenn der Nutzer "Semantic
  Scholar" wählt

---
Alternativer Ansatz

  Den Nutzer direkt nach der Semantic Scholar Author-ID fragen
   — genau wie bei DBLP.

Die ID sieht so aus: 2123456789 — sie steht in der URL:
  https://www.semanticscholar.org/author/J.-Libuda/2123456789

  Vorteil: 1 API-Aufruf, kein Umweg über ORCID, kein
  Namens-Matching-Problem.

  Nachteil: Nutzer muss eine zweite ID kennen und eintragen.

---
  Was bevorzugst du — den Umweg über den Namen, oder direkt
  die S2-Author-ID als eigenes Block-Attribut?