# rrze-research-data

Abruf und Anzeige von Forschungs- und Projektdaten aus öffentlichen Portalen

Das  WordPress‑Plugin „Research Data“ dient dazu, wissenschaftliche Informationen zu einer bestimmten Person aus mehreren Forschungsportalen abzurufen und auf einer Webseite darzustellen. Es unterstützt derzeit arXiv und Publons / Web of Science (weitere Portale können später ergänzt werden) und nutzt für jede Quelle eigene API‑Klassen. Die erforderlichen API‑Schlüssel werden auf einer Einstellungsseite im Backend hinterlegt; mithilfe der FAUdir‑Schnittstelle können die portal­spezifischen IDs einer Person automatisiert ermittelt werden.

Anfragen an die externen APIs werden gecacht (Transients), sodass wiederholte Aufrufe die Performance nicht beeinträchtigen. Über Shortcodes und entsprechende Blöcke im Blockeditor können Inhalte wie Publikationen, Projekte, Peer‑Reviews oder Auszeichnungen eingebunden werden. Parameter im Shortcode/Block erlauben die Auswahl der Datenquelle und der gewünschten Datenart; wird kein Portal angegeben, durchsucht das Plugin alle aktivierten Quellen. Die Ausgabe erfolgt über flexible Templates, die sich leicht an das Design der Website anpassen lassen.

# Vorgehensweise für das WordPress‑Plugin

Die folgende Schritt‑für‑Schritt‑Anleitung beschreibt das Vorgehen zur Entwicklung des Plugins und fasst die wichtigsten Arbeiten zusammen.

## 1. Anforderungsanalyse und Planung

- Festlegen, welche Daten konkret benötigt werden (z. B. Publikationen, Projekte, Peer‑Reviews, Auszeichnungen).
- Entscheiden, welche Quellen genutzt werden sollen (z. B. arXiv, Publons/Web of Science); weitere Portale wie ORCID oder Crossref können optional ergänzt werden.
- Prüfen, ob für kostenpflichtige Dienste (etwa Web of Science) gültige Lizenzen und API‑Schlüssel vorliegen.

## 2. Plugin‑Grundgerüst erstellen

- WordPress‑konforme Ordnerstruktur anlegen, etwa `wp-content/plugins/rrze-research-data/`.
- Autoloader oder `includes/`‑Verzeichnis für Klassen vorbereiten.
- `templates/`‑Ordner für die Ausgabe der Daten anlegen.
- Aktivierungs‑/Deaktivierungshooks registrieren, um bei der Installation Standardoptionen zu setzen.

## 3. API‑Integration

- **ArXiv:** Eine PHP‑Funktion implementieren, die mittels `wp_remote_get` Anfragen an `https://export.arxiv.org/api/query` stellt und das XML‑Antwortformat (Atom‑Feed) in ein Array überführt. Parameter wie `search_query`, `id_list`, `start` und `max_results` werden unterstützt; Paginierung und optionale Sortierung werden berücksichtigt. Zwischen mehreren Aufrufen sollten Pausen eingehalten werden, um die Nutzungsrichtlinien zu respektieren.
- **Publons Developer API:** Methoden schreiben, um JSON‑Daten von Endpunkten wie `academic/{id}/` oder `academic/publication/?academic={id}` abzurufen. Ein API‑Token wird als HTTP‑Header übergeben; aufgrund des täglichen Limits wird ein Cache benötigt.
- **Web of Science Researcher API (optional):** Sofern eine Lizenz und ein API‑Schlüssel vorhanden sind, kann eine Klasse entwickelt werden, die die REST‑Endpunkte dieser API nutzt, um Dokumente, Peer‑Reviews und Kennzahlen abzurufen. Die Rate‑Limits (5 Anfragen/s, 5 000/Tag) sind zu beachten.

## 4. Daten‑Caching mit Transients

- Ergebnisse externer API‑Anfragen werden mit `set_transient()` für eine definierte Dauer (z. B. 12 oder 24 Stunden) gespeichert.
- Der Transient‑Key sollte aus Portalname, Personen‑ID und Datentyp zusammengesetzt sein.
- Beim Aufruf eines Shortcodes wird zunächst geprüft, ob ein Cache‑Eintrag vorhanden ist; andernfalls erfolgt die API‑Abfrage.

## 5. Blöcke implementieren

- Ausgabe und EInbindung soll über Blöcke erfolgen.
- Shortcodes sind nur anzubieten, wenn hierdurch auf technischer Mehrwert entsteht.

## 6. Templates für die Ausgabe

- Für jede Datenart eine Template‑Datei im `templates/`‑Verzeichnis anlegen (z. B. `publications.php`, `reviews.php`).
- Die Templates übernehmen ein strukturiertes Array und erzeugen semantisches HTML; das Styling kann via CSS angepasst werden.

## 7. Einstellungsseite im Backend

- Unter `Einstellungen → Research Data` eine Seite hinzufügen, auf der API‑Schlüssel/Tokens für jedes angebundene Portal hinterlegt werden können.
- Optionen zum Aktivieren/Deaktivieren einzelner Portale sowie zur Festlegung der Cache‑Dauer bereitstellen.



