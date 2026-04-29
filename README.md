# rrze-research-data

WordPress-Plugin zum Abruf und zur Anzeige wissenschaftlicher Publikationen aus öffentlichen Forschungsportalen.

## Beschreibung

Das Plugin ruft Publikationsdaten einer Person aus mehreren externen APIs ab und zeigt sie über einen Gutenberg-Block auf der Website an. Alle genutzten APIs sind öffentlich zugänglich — es sind keine API-Schlüssel erforderlich.

Publikationen werden serverseitig dedupliziert (anhand der DOI), sodass ein Paper, das auf mehreren Plattformen gelistet ist, nur einmal erscheint.

## Unterstützte Quellen

| Quelle | ID-Format |
|---|---|
| ORCID | `0000-0003-4713-5941` |
| OpenAlex | ORCID |
| PubMed | ORCID |
| arXiv | z. B. `warner_s_1` |
| Crossref | ORCID |
| DBLP | z. B. `l/LieblerA` oder `06/3501` |
| Semantic Scholar | z. B. `6213406` |

## Block

Der Block wird im Gutenberg-Editor unter „Research Data" gefunden. Einstellungen:

- **Quelle** — Auswahl der API
- **Autoren-ID** — manuell eingeben oder via FAUdir-Dropdown (falls FAUdir-Plugin aktiv)
- **Anzahl** — maximale Anzahl angezeigter Publikationen
- **Jahr von/bis** — Zeitraum filtern
- **Publikationstypen** — Mehrfachauswahl
- **Gruppierung** — nach Jahr oder Publikationstyp
- **Zitierstil** — Standard, APA oder MLA

## Zitierstile

- **Standard** — Autoren | Jahr | Titel (verlinkt) | Journal | Band, Seiten
- **APA** — Nachname, V. (Jahr). Titel. *Journal*, Band(Heft), Seiten. DOI
- **MLA** — Nachname, Vorname, et al. „Titel." *Journal*, Jahr, S. Seiten. DOI

## Caching

API-Anfragen werden als WordPress Transients für 12 Stunden gecacht. Der Cache kann manuell unter **Einstellungen → Research Data** geleert werden.

## FAUdir-Integration

Wenn das FAUdir-Plugin aktiv ist, können Personen über ein Dropdown ausgewählt werden. Die ORCID-ID wird automatisch befüllt, sofern in FAUdir hinterlegt.

## REST API

```
GET /wp-json/rrze-research-data/v1/faudir/persons
GET /wp-json/rrze-research-data/v1/faudir/person/{id}
```

Berechtigung: `edit_posts`

## Technische Hinweise

- Kein Shortcode — ausschließlich Block-Ausgabe
- JSON-LD (schema.org `ScholarlyArticle`) wird automatisch mitausgegeben
- DOI wird normalisiert gespeichert (immer als reiner Identifier, nie als URL)
- PHP 8.1+, WordPress 6.0+