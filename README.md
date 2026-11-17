# WorldGraph

Szkielet repozytorium aplikacji do organizacji i analizy spójności fikcyjnych światów.

**Aktualny status: demonstracyjna strona startowa obsługiwana przez punkt wejścia PHP i własny router, autoloading Composer, testy routera oraz lokalne środowisko Docker Compose.** Przekazywanie pozostałych tras przez Nginx, kontrolery, warstwy biznesowe i schemat bazy aplikacji pozostają do implementacji.

- [Założenia projektu](PROJECT.md)
- [Plan architektury](ARCHITECTURE.md)
- [Widoki interfejsu w Figma — desktop i mobile](https://www.figma.com/design/uJj1UDCTmQoMBmMxnDGXqg/WorldGraph-%E2%80%94-All-Stitch-Views?node-id=18-42)
- [Przykładowe repozytorium od prowadzącego](https://github.com/AdrianWii/wdpai-2027)
- [Instrukcje dla agentów](AGENTS.md)
- [Lista 36 kryteriów zaliczenia](docs/REQUIREMENTS.md)

Większość katalogów w `src/`, `database/`, `tests/` i pozostałych miejscach wskazuje **planowaną organizację kodu**; działające fragmenty opisano poniżej.

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

4. Wygeneruj autoloading PHP przy pierwszym uruchomieniu i po zmianie `composer.lock`:

   ```sh
   docker compose exec php composer install --no-interaction
   ```

Adres serwera: <http://localhost:8080> (lub port z `.env`). Żądania kierowane do PHP obsługuje `public/index.php`: podłącza autoloader Composera, rejestruje trasy z `config/routes.php` i przekazuje metodę oraz adres do routera. Dla `GET /` i `GET /index.php` wybrana funkcja wysyła istniejący `public/index.html` jako HTML w UTF-8. Treść jest odczytywana ze stałej ścieżki przez `readfile()`. Style nadal pochodzą z `public/assets/css/style.css`. Strona zawiera wprowadzenie do projektu oraz statyczny, przykładowy graf świata Asteria; linki prowadzą do sekcji na tej samej stronie.

Bezpośredni adres `/index.html` nadal udostępnia ten sam plik statycznie, bez PHP. To rozwiązanie przejściowe do przeniesienia treści do szablonu w 1.7. Obecna konfiguracja Nginx wybiera `index.php` przed `index.html`; dodanie punktu wejścia nie wymaga jej zmiany ani przebudowy obrazu.

Przedstawione postacie i powiązania są danymi demonstracyjnymi. Przekazywanie pozostałych tras aplikacji do PHP należy do 1.4; nieznane ścieżki nadal otrzymują 404 z Nginx. Kontrolery, warstwy biznesowe i połączenie aplikacji z bazą pozostają do implementacji.

## Routing

Wariant A z zadania 1.3 realizuje `WorldGraph\Core\Http\Router`. `add($method, $path, $handler)` rejestruje ścieżkę dosłowną, a `addRegex($method, $pattern, $handler)` wzorzec PCRE z delimiterami. Router wymaga dopasowania całej ścieżki; nazwane grupy regex stają się parametrami. Nie obsługuje szablonów `{id}`. W aplikacji zarejestrowano tylko istniejące `/` i `/index.php`; trasy światów oraz operacje zapisu w testach są izolowanymi przykładami.

`resolve($method, $requestTarget)` zwraca status, funkcję obsługi lub `null`, `parameters`, `query` i `allowedMethods`. Punkt wejścia ustawia status HTTP oraz nagłówek `Allow` dla 405, a po dopasowaniu wywołuje funkcję z dwoma osobnymi argumentami: parametrami ścieżki i query. Parametry nie wskazują pliku ani klasy do załadowania.

- Ścieżka jest oddzielana od query przed dopasowaniem. W testowym `/worlds/42?id=99&page=2` parametrem ścieżki pozostaje `id = "42"`, a query zawiera osobne `id = "99"` i `page = "2"`.
- Wzorce porównywane są z zakodowaną ścieżką. Nazwane przechwycenia są dekodowane przez `rawurldecode()` jeden raz; query korzysta z reguł `parse_str()`, także dla list. Końcowy `/` ma znaczenie i nie jest automatycznie usuwany.
- Trasy statyczne mają pierwszeństwo przed regex, również przy niedozwolonej metodzie. Wśród pasujących regex wybierany jest pierwszy obsługujący daną metodę. Powtórna rejestracja tej samej pary metoda–ścieżka lub metoda–wzorzec jest odrzucana.
- Metody rozróżniają wielkość liter. `HEAD` korzysta z jawnej trasy `HEAD` albo z `GET`; funkcja obsługi jest wykonywana, a warstwa HTTP pomija ciało odpowiedzi. `OPTIONS` wymaga jawnej rejestracji i obecnie nie ma takiej trasy.
- Nieprawidłowy format żądania daje wynik 400, brak dopasowania 404, a niedozwolona metoda 405 z listą dozwolonych metod, uwzględniającą `HEAD` przy `GET`. Punkt wejścia nie generuje jeszcze stron błędów; pełne komunikaty i obsługa nieoczekiwanych wyjątków należą do 1.8.

Przed 1.4 Nginx nadal obsługuje istniejące pliki i odrzuca inne adresy przed PHP. Może też zakończyć niektóre metody dla katalogu: `OPTIONS /` zwraca obecnie 405 z Nginx bez nagłówka `Allow`, podczas gdy `OPTIONS /index.php` dociera do routera i zwraca 405 z `Allow: GET, HEAD`. Dopasowania dynamiczne są na tym etapie sprawdzane w CLI, bez publicznych endpointów demonstracyjnych.

Testy routera nie wymagają nowego frameworka:

```sh
docker compose exec -T php php tests/Unit/RouterTest.php
```

Oczekiwany wynik: `Router tests: 22 scenarios passed.` Skrypt zwraca kod 1 przy błędzie. Testy obejmują wybór operacji, regex, parametry, query, kodowanie, pierwszeństwo tras, `HEAD`, błędną konfigurację i wyniki 400/404/405.

## Composer i ładowanie klas PHP

Composer jest dostępny w istniejącym kontenerze `php`; nie wymaga instalacji PHP ani Composera na hoście. Konfiguracja w `composer.json` wymaga PHP `^8.5` i nie dodaje zewnętrznych bibliotek.

Autoloading PSR-4 mapuje przestrzeń nazw `WorldGraph\` na katalog `src/`. Klasa routera znajduje się w `src/Core/Http/Router.php`; przykładowa przyszła klasa `WorldGraph\Controller\HomeController` będzie odpowiadać plikowi `src/Controller/HomeController.php`. Wielkość liter nazw katalogów, plików i klas musi się zgadzać. Kolejne klasy będą dodawane wraz z odpowiednimi modułami.

Polecenie `composer install` generuje `vendor/autoload.php`, podłączany przez `public/index.php`. Katalog `vendor/` jest ignorowany przez Git, a `composer.json` i wygenerowany `composer.lock` należy wersjonować. Dane robocze Composera pozostają w ignorowanym `storage/cache/composer/`.

```sh
docker compose exec php composer --version
docker compose exec php composer validate --strict
docker compose exec php composer check-platform-reqs
docker compose exec php composer dump-autoload
```

`dump-autoload` odświeża mapowanie po zmianie sekcji `autoload`. Dodawanie klas w już zmapowanym `src/` nie wymaga ponownego generowania przy obecnej konfiguracji. Po pobraniu zmian zależności wykonuj `install`; `update` służy do świadomej zmiany ich wersji.

## Kontenery i dane

| Usługa | Przeznaczenie |
|---|---|
| `nginx` | Serwer HTTP, dostępny wyłącznie lokalnie; udostępnia tylko `public/`. |
| `php` | PHP 8.5-FPM z rozszerzeniem `pdo_pgsql`, Composerem 2 i ustawieniami deweloperskimi. Kod jest montowany z repozytorium, więc jego zmiany nie wymagają przebudowy obrazu. |
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

16 listopada 2026 zweryfikowano zadanie 1.1 w kontenerze PHP 8.5.12 z Composerem 2.11.2:

- Zbudowano końcowy obraz PHP i uruchomiono istniejące usługi Compose.
- Wykonano `composer install --no-interaction`, wygenerowano `composer.lock` bez zewnętrznych pakietów i ponowiono instalację z tego pliku. Przeszły `composer validate --strict`, `composer check-platform-reqs`, `composer dump-autoload` i kontrola składni `vendor/autoload.php`.
- Sprawdzono mapowanie PSR-4 oraz rzeczywiste załadowanie tymczasowej klasy z `src/Core/`; nieistniejąca klasa poprawnie nie została załadowana. Plik testowy usunięto po sprawdzeniu.
- Strona `/` i `/assets/css/style.css` zwróciły HTTP 200; `/composer.json` i `/vendor/autoload.php` zwróciły HTTP 404.

16 listopada 2026 zweryfikowano zadanie 1.2:

- `docker compose exec -T php php -l public/index.php` nie wykazało błędów składni.
- Wywołanie punktu wejścia w CLI potwierdziło rejestrację autoloadera Composera i zgodność wyniku z istniejącym HTML także przy innym katalogu roboczym.
- `/`, `/index.php`, `/index.html` i `/assets/css/style.css` zwróciły HTTP 200. Odpowiedzi miały właściwy typ treści, a ich bajty odpowiadały istniejącym plikom HTML i CSS; odpowiedzi PHP miały kodowanie UTF-8.
- Nieznana ścieżka zwróciła HTTP 404 z dotychczasowej obsługi Nginx.

17 listopada 2026 zweryfikowano zadanie 1.3 w kontenerze `php`:

- Kontrola składni `php -l` przeszła dla `src/Core/Http/Router.php`, `config/routes.php`, `public/index.php` i `tests/Unit/RouterTest.php`.
- Polecenie testów z sekcji [Routing](#routing) zakończyło się kodem 0 i wynikiem `Router tests: 22 scenarios passed.`
- HTTP GET dla `/`, `/index.php`, `/?q=myra&id=99`, `/index.html` i CSS zwrócił 200, właściwy typ treści oraz bajty zgodne z istniejącymi plikami. HEAD dla `/` i `/index.php` zwrócił 200 z pustym ciałem.
- POST `/`, PUT `/index.php` i OPTIONS `/index.php` zwróciły 405 z `Allow: GET, HEAD`. Nieznany adres zwrócił 404 z Nginx; potwierdzono też opisane wyżej ograniczenie OPTIONS `/` przed 1.4.

Dokumentacja użytych mechanizmów: [kolejność uruchamiania Compose](https://docs.docker.com/compose/how-tos/startup-order/), [oficjalny obraz PHP](https://hub.docker.com/_/php), [oficjalny obraz PostgreSQL i wolumeny](https://hub.docker.com/_/postgres).
