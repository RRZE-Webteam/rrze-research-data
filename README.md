# rrze-research-data

Abruf und Anzeige von Forschungs- und Projektdaten aus öffentlichen Portalen

Das  WordPress‑Plugin „Research Data“ dient dazu, wissenschaftliche Informationen zu einer bestimmten Person aus mehreren Forschungsportalen abzurufen und auf einer Webseite darzustellen. Es unterstützt derzeit arXiv und Publons / Web of Science (weitere Portale können später ergänzt werden) und nutzt für jede Quelle eigene API‑Klassen. Die erforderlichen API‑Schlüssel werden auf einer Einstellungsseite im Backend hinterlegt; mithilfe der FAUdir‑Schnittstelle können die portal­spezifischen IDs einer Person automatisiert ermittelt werden.

Anfragen an die externen APIs werden gecacht (Transients), sodass wiederholte Aufrufe die Performance nicht beeinträchtigen. Über Shortcodes und entsprechende Blöcke im Blockeditor können Inhalte wie Publikationen, Projekte, Peer‑Reviews oder Auszeichnungen eingebunden werden. Parameter im Shortcode/Block erlauben die Auswahl der Datenquelle und der gewünschten Datenart; wird kein Portal angegeben, durchsucht das Plugin alle aktivierten Quellen. Die Ausgabe erfolgt über flexible Templates, die sich leicht an das Design der Website anpassen lassen.
