# Datenquellen – Entscheidungsdokument

## Verwendete Quellen 

| Quelle | API-Key notwendig?     | Daten |
|---|------------------------|---|
| **ORCID** | Nein                   | Autorprofil, Publikationsliste |
| **OpenAlex** | Nein                   | Publikationen, alle Fächer |
| **PubMed** | Nein (optional)        | Medizin / Biowissenschaften |
| **Crossref** | Nein                   | DOI-Metadaten als Ergänzung |
| **Semantic Scholar** | Nein                   | Zitationsdaten, MINT-Fokus |
 **arXiv** | Nein                   |zwar starke Überschneidung mit OpenAlex und oft keine ID vorhanden, wenn nicht aktiv angelegt. Es gibt eine extra Option in FAUdir zum Anlegen des Links. Daher wird es mit aufgenommen.
| **DBLP** | Nein - offene REST-API | Nur Informatik |


## Ausgeschlossene Quellen

| Quelle | Grund |
|---|---|
| **Web of Science** | Kostenpflichtig |
| **Scopus** | Lizenzpflichtig, darf laut Lizenzbestimmungen nicht auf Websites dargestellt werden |
| **ResearchGate** | Keine API |
| **Google Scholar** | Keine offizielle API |

## Abgerufene Daten

Nur **Publikationen** mit folgenden Feldern:

- Titel
- Autoren
- Journal / Venue (Zeitschrift, Sammelband oder Konferenz)
- Jahr
- Band, Seiten
- DOI / URL zum Volltext

Weitere Datentypen (Projekte, Peer Reviews, Auszeichnungen) sind evtl. für spätere Versionen vorgesehen.

## Ausgabe

Ausschließlich über **Gutenberg-Block** (kein Shortcode).
Server-Side Rendering via `BlockRender` → `PublicationRenderer`.
Zwei Ansichten: Liste und Tabelle.

## Caching

API-Antworten werden über WordPress Transients zwischengespeichert (Standard: 12 Stunden).

## Integration FAUdir

Wenn das `rrze-faudir` Plugin aktiv ist, können Personen aus einem
Dropdown ausgewählt werden. Die zugehörigen Plattform-IDs (ORCID & arXiv.)
werden automatisch über die FAUdir-Schnittstelle bezogen.