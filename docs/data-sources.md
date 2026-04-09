# Datenquellen – Entscheidungsdokument

## Verwendete Quellen (Version 1)

| Quelle | API-Key | Daten |
|---|---|---|
| **ORCID** | Nein | Autorprofil, Publikationsliste |
| **OpenAlex** | Nein | Publikationen, alle Fächer |
| **PubMed** | Nein (optional) | Medizin / Biowissenschaften |

## Geplante Quellen (Version 2)

| Quelle | API-Key | Daten |
|---|---|---|
| **Crossref** | Nein | DOI-Metadaten als Ergänzung |
| **Semantic Scholar** | Nein | Zitationsdaten, MINT-Fokus |

## Ausgeschlossene Quellen

| Quelle | Grund |
|---|---|
| **DBLP** | Nur Informatik, zu fachspezifisch |
| **arXiv** | Nur Preprints, starke Überschneidung mit OpenAlex |
| **Web of Science** | Kostenpflichtig |
| **Scopus** | Lizenzpflichtig, darf laut Lizenzbestimmungen nicht auf Websites dargestellt werden |
| **ResearchGate** | Keine API |
| **Google Scholar** | Keine offizielle API |

## Abgerufene Daten (Version 1)

Nur **Publikationen** mit folgenden Feldern:

- Titel
- Autoren
- Journal / Venue (Zeitschrift, Sammelband oder Konferenz)
- Jahr
- Band, Seiten
- DOI / URL zum Volltext

Weitere Datentypen (Projekte, Peer Reviews, Auszeichnungen) sind für spätere Versionen vorgesehen.

## Ausgabe

Ausschließlich über **Gutenberg-Blöcke** (keine Shortcodes).
Server-Side Rendering via `BlockRender` → `PublicationRenderer`.
Zwei Ansichten: Liste und Tabelle.

## Caching

API-Antworten werden über WordPress Transients zwischengespeichert (Standard: 12 Stunden).

## Integration FAUdir

Wenn das `rrze-faudir` Plugin aktiv ist, können Personen aus einem
Dropdown ausgewählt werden. Die zugehörigen Plattform-IDs (ORCID etc.)
werden automatisch über die FAUdir-Schnittstelle bezogen.