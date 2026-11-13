# WorldGraph

Szkielet repozytorium aplikacji do organizacji i analizy spójności fikcyjnych światów.

**Aktualny status: statyczny szablon strony startowej, dokumentacja, szkielet katalogów i lokalne środowisko Docker Compose.** Nie ma jeszcze kodu backendu aplikacji ani schematu bazy danych.

- [Założenia projektu](PROJECT.md)
- [Plan architektury](ARCHITECTURE.md)
- [Instrukcje dla agentów](AGENTS.md)
- [Lista 36 kryteriów zaliczenia](docs/REQUIREMENTS.md)

Katalogi w `src/`, `public/`, `database/`, `tests/` i pozostałych miejscach wskazują **planowaną organizację kodu**.

## Uruchomienie środowiska lokalnego

Wymagania: Docker Desktop uruchomiony w trybie kontenerów Linux, z Docker Compose obsługującym `up --wait` (v2.20 lub nowszy). PHP i PostgreSQL na hoście nie są potrzebne.

1. W katalogu repozytorium skopiuj konfigurację:

   ```powershell
   Copy-Item .env.example .env
   ```

   W Linux/macOS użyj `cp .env.example .env`. Jeśli `.env` już istnieje, zachowaj go zamiast nadpisywać.

2. Ustaw własne, niepuste `POSTGRES_PASSWORD` w `.env`. Plik jest ignorowany przez Git. `APP_PORT` domyślnie wynosi `8080`; zmień go, jeśli port jest zajęty.

3. Sprawdź konfigurację, zbuduj obraz PHP i uruchom kontenery:

   ```sh
   docker compose config --quiet
   docker compose up -d --build --wait
   docker compose ps
   ```

Adres serwera: <http://localhost:8080> (lub port z `.env`). Stronę startową stanowi responsywny szablon `public/index.html`, ze stylami w `public/assets/css/style.css`. Zawiera wprowadzenie do projektu oraz statyczny, przykładowy graf świata Asteria. Linki prowadzą do sekcji na tej samej stronie. Szablon nie wymaga instalowania bibliotek ani przebudowy kontenerów.

Przedstawione postacie i powiązania są danymi demonstracyjnymi. Backend i routing aplikacji pozostają do implementacji.

## Kontenery i dane

| Usługa | Przeznaczenie |
|---|---|
| `nginx` | Serwer HTTP, dostępny wyłącznie lokalnie; udostępnia tylko `public/`. |
| `php` | PHP 8.5-FPM z rozszerzeniem `pdo_pgsql` i ustawieniami deweloperskimi. Kod jest montowany z repozytorium, więc jego zmiany nie wymagają przebudowy obrazu. |
| `db` | PostgreSQL 18; start PHP czeka na gotowość bazy sprawdzaną przez `pg_isready`. |

PHP otrzymuje zmienne `DB_HOST=db`, `DB_PORT=5432`, `DB_NAME`, `DB_USER` i `DB_PASSWORD`. Nazwa bazy, użytkownik i hasło pochodzą z `POSTGRES_*` w `.env`. PostgreSQL i PHP-FPM są dostępne w sieci Compose, bez publikowania ich portów na hoście.

Baza jest inicjalizowana jako pusta, bez tabel, migracji ani danych przykładowych. Dane przechowuje nazwany wolumen `postgres_data`, zamontowany w `/var/lib/postgresql` zgodnie z układem oficjalnego obrazu PostgreSQL 18. `docker compose down` zachowuje wolumen. **Opcja `down --volumes` usuwa także dane bazy.**

Zmienne `POSTGRES_*` inicjalizują bazę tylko przy pierwszym uruchomieniu z pustym wolumenem. Późniejsza zmiana ich wartości w `.env` nie zmienia istniejącego użytkownika, hasła ani bazy — wymaga osobnej operacji w PostgreSQL. Użytkownik tworzony przez obraz jest administratorem lokalnej bazy; konfiguracja służy do pracy deweloperskiej.

## Przydatne polecenia

```sh
docker compose logs --tail=100 nginx php db
docker compose exec nginx nginx -t
docker compose exec php php -v
docker compose exec php php -m
docker compose exec db sh -c 'psql -U "$POSTGRES_USER" -d "$POSTGRES_DB"'
docker compose stop
docker compose down
```

Po zmianie `Dockerfile` uruchom ponownie `docker compose up -d --build --wait`. Po zmianie konfiguracji Nginx wykonaj `docker compose exec nginx nginx -t`, a następnie `docker compose exec nginx nginx -s reload`.

## Weryfikacja konfiguracji

Konfigurację infrastruktury zweryfikowano lokalnie 2 listopada 2026, przed dodaniem strony startowej:

- Walidację Compose oraz konfiguracji Nginx i PHP-FPM; odrzucenie pustego hasła bazy.
- Uruchomienie trzech kontenerów i gotowość PostgreSQL (`healthy`).
- Obsługę plików statycznych oraz żądania HTTP przez Nginx → PHP 8.5 → PDO → PostgreSQL 18.6, z zapytaniem `SELECT 1`.
- Brak tabel aplikacji w schemacie `public`.
- HTTP 403 dla pustego katalogu głównego i ukrytych ścieżek oraz HTTP 404 dla pliku spoza `public/` i nieistniejącego skryptu PHP.
- Zachowanie identyfikatora klastra PostgreSQL i działającego połączenia PHP po odtworzeniu kontenera `db` z tym samym wolumenem.

Pliki użyte do sprawdzenia HTTP zostały usunięte po teście. To weryfikacja środowiska, nie funkcjonalności planowanej aplikacji.

Po dodaniu strony startowej sprawdzono odpowiedzi HTTP 200 dla `/` i arkusza CSS, poprawność celów odnośników do sekcji oraz układ w Chrome przy szerokościach 320, 390, 768, 1024 i 1440 px. Nie stwierdzono poziomego przewijania ani elementów wychodzących poza szerokość strony.

Dokumentacja użytych mechanizmów: [kolejność uruchamiania Compose](https://docs.docker.com/compose/how-tos/startup-order/), [oficjalny obraz PHP](https://hub.docker.com/_/php), [oficjalny obraz PostgreSQL i wolumeny](https://hub.docker.com/_/postgres).
