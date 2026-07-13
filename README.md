# Behrmedia Performance & Security Base

Dieses WordPress-Plugin setzt serverseitige Best-Practice-Regeln für das Browser-Caching von statischen Assets und schützt den WordPress-Login vor fehlerhaftem Caching.

## 🚀 Funktionen

* **Statische Assets Caching:** Fügt automatisch `mod_expires` und `mod_headers` Regeln in die `.htaccess` ein.
* **Langzeit-Cache:** Bilder (inkl. AVIF & WebP), Schriften, Videos, CSS und JS werden für 1 Jahr im Browser zwischengespeichert (`max-age=31536000, immutable`).
* **Security (Login-Schutz):** Schließt die `wp-login.php` explizit vom Caching aus (`no-cache, no-store`), um Security-Bugs und Endlos-Weiterleitungen zu verhindern.
* **Auto-Updates:** Integrierter GitHub-Updater (via Plugin Update Checker). Updates werden direkt im WordPress-Backend angezeigt.

## ⚙️ Funktionsweise

Das Plugin nutzt die WordPress-native Funktion `insert_with_markers()`. Bei der Aktivierung werden die Regeln sicher in die `.htaccess` im Root-Verzeichnis geschrieben (zwischen `# BEGIN Behrmedia Base Settings` und `# END Behrmedia Base Settings`). Bei der Deaktivierung räumt das Plugin diese Einträge restlos wieder auf.

## 📦 Installation

Da dieses Plugin nicht im offiziellen WordPress-Verzeichnis gehostet wird, erfolgt die Erstinstallation manuell:

1. Lade das Repository als `.zip` Datei herunter (Code -> Download ZIP).
2. Gehe im WordPress Backend zu **Plugins > Installieren > Plugin hochladen**.
3. Wähle die `.zip` Datei aus und klicke auf "Jetzt installieren".
4. Aktiviere das Plugin.

*Zukünftige Updates können bequem über das WordPress-Backend per 1-Klick-Update installiert werden.*

## ⚠️ Voraussetzungen

* Apache-Server (Nginx wird über die `.htaccess` nicht unterstützt).
* Die Apache-Module `mod_expires` und `mod_headers` müssen auf dem Server aktiv sein.