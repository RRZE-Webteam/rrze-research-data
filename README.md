# rrze-research-data

WordPress-Plugin zum Abruf und zur Anzeige wissenschaftlicher Publikationen aus öffentlichen Forschungsportalen.

## Beschreibung

Das Plugin ruft Publikationsdaten einer Person aus mehreren externen APIs ab und zeigt sie über einen Gutenberg-Block auf der Website an. 
Alle genutzten APIs sind öffentlich zugänglich — es sind keine API-Schlüssel erforderlich.



## Unterstützte Quellen

| Quelle | ID-Format                                        |
|---|--------------------------------------------------|
| ORCID | `0000-0003-4713-5941`                            |
| OpenAlex | ORCID                                            |
| PubMed | ORCID                                            |
| arXiv | `Format: nachname_v_1`                           |
| DBLP | `Format: /pid/xx/000`                            |
| Semantic Scholar | ID am Ende der URL:`.../author/Nachname/0000000` |

## Block

Der Block wird im Gutenberg-Editor unter „Research Data" gefunden. Einstellungen:

- **Quelle** — Auswahl der Plattform
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

Wenn das FAUdir-Plugin aktiv ist, können Personen über ein Dropdown ausgewählt werden. Die ORCID-ID oder arXiv-ID wird automatisch befüllt, sofern in FAUdir hinterlegt.


## Technische Hinweise

- Kein Shortcode — ausschließlich Block-Ausgabe
- JSON-LD (schema.org `ScholarlyArticle`) wird automatisch mitausgegeben
- DOI wird normalisiert gespeichert (immer als reiner Identifier, nie als URL)
- PHP 8.1+, WordPress 6.0+