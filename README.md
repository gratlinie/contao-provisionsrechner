# Provisionsrechner für Contao

Frontend-Modul für Contao 5.7: Restaurants (und alle, die über Bestellplattformen
verkaufen) sehen auf einen Blick, was sie die Provision der Plattform kostet – und ab
wann sich ein eigener Shop rechnet.

- Regler für Bestellungen pro Woche, durchschnittlichen Bestellwert und Provision
- Ergebnis live im Browser: Provision pro Jahr, Kosten des eigenen Angebots, Ersparnis im
  ersten Jahr und über einen Zeitraum, Balkenvergleich, Amortisation einer Einmalzahlung
- Mehrere Angebote (z. B. „nur Abo“, „mit Einrichtung“) als Auswahl
- Vanilla-JS und -CSS, kein Build, kein Framework; Texte auf Deutsch und Englisch

Live im Einsatz auf [gratorder.de](https://www.gratorder.de/#sparrechner).

## Installation

```bash
composer require gratlinie/contao-provisionsrechner
vendor/bin/contao-console contao:migrate
```

Oder über den Contao Manager.

## Einrichtung

1. Theme → Frontend-Module → neues Modul vom Typ **Provisionsrechner**.
2. Namen des eigenen Angebots eintragen (z. B. „GratOrder“ → „GratOrder-Abo pro Jahr“),
   Startwerte der Regler und den Vergleichszeitraum festlegen.
3. Unter **Angebote** je Zeile Titel, laufende Kosten pro Jahr und einmalige Kosten
   (netto) eintragen.
4. Modul per Inhaltselement „Modul“ oder im Seitenlayout einbinden.

## Gestaltung

Stylesheet und Skript lädt das Modul selbst (nur auf Seiten mit dem Rechner). Die
Variablen stehen unter `:where(.module-provisionsrechner)` und lassen sich mit jeder
eigenen Regel überschreiben:

```css
.module-provisionsrechner {
    --pr-color: #1f2937;          /* Beschriftungen, aktive Auswahl */
    --pr-accent: #e8590c;         /* Werte, Ersparnis, eigener Balken */
    --pr-accent-strong: #c43800;  /* Zahlen an den Reglern, Regler */
    --pr-border: #d6d3d1;
    --pr-radius: 0.75rem;
    --pr-result-bg: #1f2937;      /* Ergebnis-Box */
    --pr-result-color: #b9bdc7;
    --pr-result-strong: #ffffff;
    --pr-result-line: rgb(255 255 255 / 0.1);
    --pr-gap: 4rem;               /* Abstand der Spalten ab 58 rem Breite */
}
```

Mit dem Theme [GratFrame](https://www.gratlinie.de) übernimmt der Rechner automatisch
dessen Farben.

## Angebote aus einer anderen Erweiterung

Statt die Angebote im Modul zu pflegen, kann eine Erweiterung sie liefern, z. B. aus
einer Produkttabelle:

```php
use Gratlinie\ProvisionsrechnerBundle\Event\OffersEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class ShopOffersListener
{
    public function __invoke(OffersEvent $event): void
    {
        if ([] !== $event->getOffers()) {
            return;
        }

        $event->setOffers([
            ['code' => 'abo', 'title' => 'Nur Abo', 'yearly' => 790.0, 'once' => 0.0],
            ['code' => 'setup', 'title' => 'Mit Einrichtung', 'yearly' => 790.0, 'once' => 690.0],
        ]);
    }
}
```

## Rechnung

- Provision pro Jahr = Bestellungen/Woche × 52 × Bestellwert × Provision
- Ersparnis im ersten Jahr = Provision − Kosten pro Jahr − einmalige Kosten
- Ersparnis in X Jahren = X × Provision − (einmalige Kosten + X × Kosten pro Jahr)
- Amortisation = einmalige Kosten ÷ ((Provision − Kosten pro Jahr) ÷ 365) Tage

## Lizenz

MIT – © Gratlinie
