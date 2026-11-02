# ARCHITECTURE.md — WorldGraph

> Dokument planowanej architektury aplikacji. Dostępny jest statyczny szablon strony startowej i lokalne środowisko Docker Compose; kod backendu nie został jeszcze rozpoczęty.

## Zakładany stos
- PHP (backend obiektowy), PostgreSQL, HTML/CSS, JavaScript.
- Lokalne środowisko Docker Compose: Nginx → PHP 8.5-FPM (`pdo_pgsql`) → PostgreSQL 18.
- Biblioteka grafowa JS do wyboru podczas implementacji.

## Planowany przepływ
`Browser → Router → Controller → Service → Repository → PostgreSQL`

- `Controller`: przyjmowanie żądań HTTP i odpowiedzi.
- `Service`: przypadki użycia i autoryzacja operacji.
- `Repository`: pobieranie i zapisywanie danych.
- `Domain`: zasady dotyczące światów, encji, relacji, chronologii i spójności.
- `templates`: widoki HTML.
- `public/assets`: statyczne pliki frontendu.

## Planowany model pojęciowy
- `User` — konto użytkownika.
- `World` — odrębna przestrzeń danych.
- `WorldMember` — rola użytkownika w świecie.
- `Entity` — postać, lokacja, frakcja lub artefakt.
- `Relationship` — powiązanie dwóch encji w tym samym świecie.
- `Event` — wydarzenie i uczestnicy.
- `ConsistencyIssue` — ostrzeżenie o sprzeczności, które można przejrzeć i uzasadnić.

## Zasady projektowe
- Warunek zaliczenia: rzeczywiście obiektowy backend.
- Dostęp do światów sprawdza serwer, a nie tylko interfejs.
- Relacje nie mogą łączyć encji z różnych światów.
- Daty fikcyjnych światów nie powinny być uzależnione od systemowego `timestamp`.
- Na mobile graf powinien mieć alternatywną listę relacji.

## Status
Konfiguracja kontenerów znajduje się w `compose.yaml` i `docker/`; instrukcja uruchomienia jest w `README.md`. `public/index.html` i `public/assets/css/style.css` tworzą responsywny szablon strony startowej z przykładowym grafem SVG. Nie ma jeszcze klas backendu, API, SQL ani schematu bazy aplikacji.
