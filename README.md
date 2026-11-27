# WorldGraph

Szkielet repozytorium aplikacji do organizacji i analizy spójności fikcyjnych światów.

**Aktualny status: demonstracyjna strona startowa w szablonach PHP ze wspólnym layoutem, kontrolowane odpowiedzi błędów aplikacji, własny router PHP, przekazywanie tras przez Nginx, autoloading Composer i testy w lokalnym środowisku Docker Compose.** Warstwy biznesowe i schemat bazy aplikacji pozostają do implementacji.

- [Założenia projektu](PROJECT.md)
- [Plan architektury](ARCHITECTURE.md)
- [Widoki interfejsu w Figma — desktop i mobile](https://www.figma.com/design/uJj1UDCTmQoMBmMxnDGXqg/WorldGraph-%E2%80%94-All-Stitch-Views?node-id=18-42)
- [Przykładowe repozytorium od prowadzącego](https://github.com/AdrianWii/wdpai-2027)
- [Instrukcje dla agentów](AGENTS.md)
- [Lista 36 kryteriów zaliczenia](docs/REQUIREMENTS.md)

Większość katalogów w `src/`, `database/`, `tests/` i pozostałych miejscach wskazuje **planowaną organizację kodu**; działające fragmenty opisano poniżej.

## Spis treści

- [Uruchomienie środowiska lokalnego](#uruchomienie-środowiska-lokalnego)
- [Routing](#routing)
- [Kontrolery i widoki](#kontrolery-i-widoki)
- [Błędy i logi](#błędy-i-logi)
- [Composer i ładowanie klas PHP](#composer-i-ładowanie-klas-php)
- [Kontenery i dane](#kontenery-i-dane)
- [Przydatne polecenia](#przydatne-polecenia)
- [Weryfikacja konfiguracji](#weryfikacja-konfiguracji)

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

Adres serwera: <http://localhost:8080> (lub port z `.env`). Żądania kierowane do PHP obsługuje `public/index.php`: podłącza autoloader Composera, rejestruje trasy z `config/routes.php` i przekazuje metodę oraz adres do routera. Dla `GET /`, `GET /index.php` i `GET /index.html` wywoływany jest `HomeController::index()`. Kontroler przekazuje tytuł i opis strony oraz składa `templates/home.php` z layoutem `templates/layouts/main.php` i fragmentami z `templates/partials/`. Odpowiedź pozostaje HTML w UTF-8, ze stylami w `public/assets/css/style.css`. Strona zawiera dotychczasowe wprowadzenie do projektu oraz statyczny, przykładowy graf świata Asteria.

Adres `/index.html` jest aliasem tej samej strony obsługiwanym przez PHP, bez przekierowania. Dawny plik `public/index.html` został zastąpiony szablonami; treść ma jedno źródło. Dla `/` Nginx wewnętrznie przekazuje żądanie do `index.php`, również dla metod innych niż GET i HEAD. Odnośniki we wspólnej nawigacji prowadzą do sekcji strony głównej (`/#idea` i `/#world-preview`), a logo do `/`.

Przedstawione postacie i powiązania są danymi demonstracyjnymi. Adresy aplikacji bez odpowiadającego pliku lub katalogu trafiają do PHP; niezarejestrowana trasa otrzymuje 404 z routera. Nie tworzy to nowych stron ani operacji biznesowych. Warstwy biznesowe i połączenie aplikacji z bazą pozostają do implementacji.

## Routing

Routing realizuje własna klasa PHP `WorldGraph\Core\Http\Router`, dodana w zadaniu 1.3. `add($method, $path, $handler)` rejestruje ścieżkę dosłowną, a `addRegex($method, $pattern, $handler)` wzorzec PCRE z delimiterami. Router wymaga dopasowania całej ścieżki; nazwane grupy regex stają się parametrami. Nie obsługuje szablonów `{id}`. W aplikacji zarejestrowano tylko adresy obecnej strony: `/`, `/index.php` i `/index.html`; trasy światów oraz operacje zapisu w testach są izolowanymi przykładami.

Własna klasa zapewnia wymagane dopasowania metod, ścieżek i parametrów bez instalowania biblioteki routingu ani frameworka. Oddziela wybór operacji od wysyłania odpowiedzi HTTP, co pozwala sprawdzać reguły dopasowania samodzielnymi testami CLI opisanymi niżej.

`resolve($method, $requestTarget)` zwraca status, funkcję obsługi lub `null`, `parameters`, `query` i `allowedMethods`. Punkt wejścia ustawia status HTTP oraz nagłówek `Allow` dla 405, a po dopasowaniu wywołuje funkcję z dwoma osobnymi argumentami: parametrami ścieżki i query. Parametry nie wskazują pliku ani klasy do załadowania.

- Ścieżka jest oddzielana od query przed dopasowaniem. W testowym `/worlds/42?id=99&page=2` parametrem ścieżki pozostaje `id = "42"`, a query zawiera osobne `id = "99"` i `page = "2"`.
- Wzorce porównywane są z zakodowaną ścieżką. Nazwane przechwycenia są dekodowane przez `rawurldecode()` jeden raz; query korzysta z reguł `parse_str()`, także dla list. Końcowy `/` ma znaczenie i nie jest automatycznie usuwany.
- Trasy statyczne mają pierwszeństwo przed regex, również przy niedozwolonej metodzie. Wśród pasujących regex wybierany jest pierwszy obsługujący daną metodę. Powtórna rejestracja tej samej pary metoda–ścieżka lub metoda–wzorzec jest odrzucana.
- Metody rozróżniają wielkość liter. `HEAD` korzysta z jawnej trasy `HEAD` albo z `GET`; funkcja obsługi jest wykonywana, a warstwa HTTP pomija ciało odpowiedzi. `OPTIONS` wymaga jawnej rejestracji i obecnie nie ma takiej trasy.
- Nieprawidłowy format żądania daje wynik 400, brak dopasowania 404, a niedozwolona metoda 405 z listą dozwolonych metod, uwzględniającą `HEAD` przy `GET`. Punkt wejścia kieruje te wyniki do `ErrorController`, zachowuje status i `Allow` przy 405 oraz pomija ciało odpowiedzi na `HEAD`. Obsługę awarii opisuje sekcja [Błędy i logi](#błędy-i-logi).

Przekazywanie tras przez Nginx, dodane w zadaniu 1.4, zachowuje bezpośrednią obsługę istniejących plików i kieruje pozostałe adresy do routera PHP. Osobna reguła `location = /` przekazuje `/` do `index.php` przez wewnętrzne `rewrite`, zachowując metodę i query. Dla innych adresów `try_files` obsługuje istniejący plik lub katalog, a przy ich braku przekazuje żądanie do `/index.php$is_args$args`. Oryginalne `REQUEST_URI`, metoda i query są przekazywane przez FastCGI; adres w przeglądarce pozostaje bez zmian. `OPTIONS /` dociera teraz do PHP i otrzymuje 405 z `Allow: GET, HEAD`.

Ten podział pozwala Nginx udostępniać zasoby statyczne bez wykonywania PHP, a routerowi rozstrzygać adresy aplikacji. Nie przekazuje wszystkich plików przez kontroler PHP ani nie wymaga przekierowania przeglądarki. Połączenie obu warstw sprawdzają opisane niżej testy HTTP.

CSS nadal obsługuje bezpośrednio Nginx. Po przeniesieniu HTML do szablonów adres `/index.html` trafia przez istniejące `try_files` do routera PHP. Ukryte ścieżki, również w katalogach zasobów, są blokowane, a nieistniejące skrypty PHP otrzymują 404 przed PHP-FPM. Pliki spoza `public/`, w tym layout i fragmenty widoków, nie są udostępniane. Nie dodano publicznych tras demonstracyjnych; dynamiczne dopasowania routera pozostają objęte testami CLI.

Testy routera nie wymagają nowego frameworka:

```sh
docker compose exec -T php php tests/Unit/RouterTest.php
```

Oczekiwany wynik: `Router tests: 22 scenarios passed.` Skrypt zwraca kod 1 przy błędzie. Testy obejmują wybór operacji, regex, parametry, query, kodowanie, pierwszeństwo tras, `HEAD`, błędną konfigurację i wyniki 400/404/405.

Sprawdzenie połączenia Nginx z PHP wykonuje osobny test HTTP:

```sh
docker compose exec -T php php tests/Integration/RoutingHttpTest.php
```

Domyślnym adresem jest `http://nginx` w sieci Compose; inny adres można przekazać jako pierwszy argument skryptu. Test sprawdza treść i strukturę strony pod trzema adresami, treść i typ CSS, komunikaty błędów, metody, błędne query oraz niedostępność chronionych ścieżek. Nie zmienia danych aplikacji ani nie tworzy endpointów. Wynik podaje liczbę wykonanych scenariuszy; kod 1 oznacza niepowodzenie.

## Kontrolery i widoki

Renderowanie w bazowym kontrolerze, dodane w zadaniach 1.5–1.7, używa `BaseController` ze wspólną chronioną metodą `render(string $view, array $data = [], ?string $layout = null): void` i `HomeController` z akcją `index(array $parameters, array $query): void`. Definicje tras wskazują metodę istniejącego obiektu kontrolera. Router i punkt wejścia zachowują dotychczasowy kontrakt; nie wybierają klasy ani pliku z danych żądania.

Wspólne renderowanie pozostaje w bazowym kontrolerze, dzięki czemu kontrolery korzystają z jednego mechanizmu wyboru i buforowania szablonów. Nie wydzielano osobnej klasy renderera ani nie dodawano silnika szablonów; widoki i layout są plikami PHP. Zachowanie mechanizmu sprawdzają testy kontrolerów opisane niżej.

Renderowanie przyjmuje logiczną nazwę widoku, np. `home` lub `worlds/details`, oraz opcjonalnie layoutu, np. `layouts/main`. Odpowiadające pliki `.php` są wybierane wyłącznie z `templates/`, poza katalogiem publicznym. Nazwy mogą zawierać litery ASCII, cyfry, `_`, `-` oraz `/` rozdzielający segmenty. Nieprawidłowe nazwy są odrzucane; sprawdzana jest też rzeczywista ścieżka pliku, w tym dowiązania symboliczne, jego typ i dostępność do odczytu. Obie ścieżki są sprawdzane przed wykonaniem widoku.

Widok otrzymuje dane jako tablicę `$data` w statycznej funkcji, bez `extract()` i bez dostępu do obiektu kontrolera przez `$this`. Layout otrzymuje `$data` oraz osobną zmienną `$content` z wyrenderowaną treścią widoku; klucz `content` w danych nie zastępuje tej zmiennej. Wartości nie są automatycznie kodowane; szablon koduje niezaufany tekst w miejscu wstawiania do HTML. Wspólny layout koduje tytuł i opis przez `htmlspecialchars()` z UTF-8. `$content` jest HTML-em wygenerowanym przez zaufany widok i trafia do dokumentu jako treść, bez ponownego kodowania.

Widoki PHP są zaufanym kodem projektu i mają zachowywać bufor renderera. Renderer osobno buforuje widok i layout, a wynik emituje dopiero po ich poprawnym wykonaniu. Przy wyjątku lub niezrównoważonych buforach sprząta bufory otwarte podczas tej operacji. Przekazanie `null` jako layoutu zachowuje dotychczasowe renderowanie samego widoku.

Nieprawidłowa nazwa powoduje `InvalidArgumentException`, a brak lub niedostępność widoku albo layoutu `RuntimeException`. Przy żądaniu HTTP nieoczekiwany wyjątek dociera do obsługi awarii w punkcie wejścia; użytkownik otrzymuje ogólną odpowiedź 500, bez szczegółów wyjątku.

Strona główna korzysta z następującego podziału:

| Plik | Odpowiedzialność |
|---|---|
| `templates/layouts/main.php` | Dokument HTML, metadane, CSS, odnośnik „Przejdź do treści” i `main` z treścią widoku. |
| `templates/partials/header.php` | Logo, nagłówek i dołączenie wspólnej nawigacji. |
| `templates/partials/navigation.php` | Odnośniki do istniejących sekcji strony głównej. |
| `templates/partials/footer.php` | Stopka i powrót na początek bieżącej strony. |
| `templates/home.php` | Sekcje strony demonstracyjnej oraz przykładowy graf SVG. |

Fragmenty są dołączane ze stałych ścieżek projektu. Zachowano obecne klasy CSS, treść i graf demonstracyjny; strona nie wymaga bazy. Kolejne strony powstaną przy implementacji ich modułów: konta w etapie 3, biblioteka i światy w etapie 4, karty w 5, grafy w 7, Odkrywaj w 8 oraz wydarzenia w 9. Nawigacja otwartego świata z TBI dla Spójności należy do etapu jego wdrażania, a nie do migracji demonstracyjnej strony.

Testy renderowania uruchamia się bez dodatkowego frameworka:

```sh
docker compose exec -T php php tests/Unit/ControllerTest.php
```

Sprawdzają przekazywanie danych i izolację zmiennych, składanie z layoutem, kodowanie metadanych, odrzucenie nieprawidłowych ścieżek, błędy i buforowanie oraz obsługę strony przez kontroler i trzy istniejące adresy. Tymczasowe szablony powstają w unikalnym podkatalogu `templates/` i są usuwane w `finally`. Test dowiązania jest jawnie pomijany, jeżeli platforma nie umożliwia jego utworzenia; wynik pokazuje liczbę pominiętych scenariuszy.

## Błędy i logi

Obsługa błędów w punkcie wejścia, dodana w zadaniu 1.8, obejmuje wykonanie żądania w `public/index.php`. Router rozpoznaje żądanie, a `ErrorController::show(int $status)` renderuje stałe komunikaty przez `templates/errors/http.php` i istniejący layout. Kontroler obsługuje wyłącznie 400, 404, 405 i 500; nie tworzy publicznych tras do wywoływania błędów.

Umieszczenie obsługi wyjątków w istniejącym punkcie wejścia pozwala objąć nią także uruchomienie autoloadera i konfiguracji tras. Nie wydzielano osobnej klasy wykonującej żądania HTTP; kontroler błędów odpowiada za status i prezentację komunikatu. Odpowiedzi routera sprawdzają testy HTTP, a awarie i odpowiedź zastępczą opisany niżej test izolowany.

| Status | Znaczenie |
|---|---|
| 400 | Nieprawidłowy format żądania. |
| 404 | Brak pasującej trasy aplikacji. |
| 405 | Trasa istnieje, ale nie obsługuje użytej metody; odpowiedź zachowuje `Allow`. |
| 500 | Nieoczekiwany błąd wykonania aplikacji; użytkownik widzi ogólny komunikat. |

Punkt wejścia buforuje wykonanie autoloadera, konfiguracji tras i kontrolera. Przy przechwyconym `Throwable` usuwa częściową treść i nagłówki nieudanej odpowiedzi, ustawia 500 oraz `Cache-Control: no-store` i próbuje wyrenderować stronę błędu. Jeśli zawiedzie również ten renderer lub jego szablon, zwraca stały komunikat awaryjny jako `text/plain` w UTF-8. `HEAD` zachowuje status i nagłówki, ale nie wysyła ciała także w przypadku błędu.

Komunikaty nie zawierają surowego adresu żądania ani wiadomości wyjątku. Techniczny wpis przechwyconej awarii zawiera klasę błędu, nazwę pliku i linię; nie dopisuje wiadomości wyjątku, stosu, argumentów ani danych żądania. Te logi służą diagnostyce serwera; historia danych świata z etapu 4 pozostaje osobnym, planowanym mechanizmem.

Obraz PHP dołącza `docker/php/conf.d/errors.ini` przez Dockerfile. Konfiguracja ustawia `display_errors=Off`, `display_startup_errors=Off`, `log_errors=On`, `error_reporting=E_ALL`, `error_log=/proc/self/fd/2` i `zend.exception_ignore_args=On`. Po zmianie tego pliku lub Dockerfile przebuduj istniejący obraz PHP poleceniem `docker compose up -d --build --wait`. Logi PHP-FPM można przeglądać przez `docker compose logs --tail=100 php`; wyniki sprawdzenia konfiguracji i kanału logowania zapisano w [weryfikacji konfiguracji](#weryfikacja-konfiguracji).

Kontrola obejmuje błędy aplikacji PHP możliwe do przechwycenia podczas wykonania. Kontrolery i szablony pozostają zaufanym kodem projektu, który musi zachowywać bufory odpowiedzi. `exit` i `die` nie uruchamiają tej obsługi wyjątków. Nie zapewnia ona wyrenderowania szablonu przy każdym błędzie uruchamiania PHP, wyczerpaniu pamięci ani awarii PHP-FPM. Odpowiedzi generowane bezpośrednio przez Nginx, w tym blokowanie ukrytych plików i nieistniejących skryptów PHP, pozostają w jego konfiguracji.

Odizolowane sprawdzenie nieoczekiwanej awarii i odpowiedzi awaryjnej:

```sh
docker compose exec -T php php tests/Integration/ErrorHandlingTest.php
```

Skrypt uruchamia kopię rzeczywistego punktu wejścia na osobnym serwerze PHP dostępnym tylko przez loopback. Tymczasowe pliki powstają w unikalnym podkatalogu `storage/cache/`; proces i pliki są sprzątane w `finally`. Test nie zmienia konfiguracji działającej aplikacji ani nie tworzy w niej publicznej trasy awarii. Wynik i zakres wykonanych scenariuszy opisuje sekcja weryfikacji. Dotychczasowe testy routera, kontrolerów i HTTP pozostają wymagane.

## Composer i ładowanie klas PHP

Composer jest dostępny w istniejącym kontenerze `php`; nie wymaga instalacji PHP ani Composera na hoście. Konfiguracja w `composer.json` wymaga PHP `^8.5` i nie dodaje zewnętrznych bibliotek.

Autoloading PSR-4 mapuje przestrzeń nazw `WorldGraph\` na katalog `src/`. Klasa routera znajduje się w `src/Core/Http/Router.php`, a `WorldGraph\Controller\HomeController` w `src/Controller/HomeController.php`. Wielkość liter nazw katalogów, plików i klas musi się zgadzać. Kolejne klasy będą dodawane wraz z odpowiednimi modułami.

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
- POST `/`, PUT `/index.php` i OPTIONS `/index.php` zwróciły 405 z `Allow: GET, HEAD`. Nieznany adres zwrócił 404 z Nginx; odnotowano wówczas ograniczenie OPTIONS `/`: 405 z Nginx bez `Allow`, przed zmianą z 1.4.

17 listopada 2026 zweryfikowano zadanie 1.4:

- Uruchomiono istniejące usługi Compose bez przebudowy obrazów i usuwania wolumenów. `nginx -t` zakończył się powodzeniem, a konfigurację przeładowano przez `nginx -s reload`.
- Kontrola składni `tests/Integration/RoutingHttpTest.php` przeszła. Polecenie testów HTTP z sekcji [Routing](#routing) zakończyło się kodem 0 i wynikiem `Routing HTTP tests: 22 scenarios passed.` Ponownie przeszły 22 scenariusze routera CLI.
- GET strony i CSS zwrócił niezmienione bajty, a HEAD puste ciało. POST, PUT, OPTIONS, DELETE i PATCH dla `/` otrzymały routerowe 405 z `Allow: GET, HEAD`, co usuwa ograniczenie odnotowane w 1.3.
- Nieznany adres z query, także przez POST, otrzymał puste PHP-owe 404. Nieprawidłowe query `/?q=%GG` otrzymało puste PHP-owe 400, potwierdzając przekazanie oryginalnego URI z query do routera. Dekodowanie wartości query i parametrów nadal sprawdzają testy CLI.
- Ukryte ścieżki zwróciły 403, a nieistniejący skrypt PHP oraz odwołania do plików poza `public/` zwróciły 404.

17 listopada 2026 zweryfikowano zadanie 1.5 w kontenerze `php`:

- Kontrola składni przeszła dla `BaseController.php`, `HomeController.php`, `templates/home.php`, `config/routes.php` i `tests/Unit/ControllerTest.php`.
- Testy kontrolerów zakończyły się kodem 0: `Controller tests: 19 scenarios passed; 0 skipped.` Sprawdzono również odrzucenie dowiązania wychodzącego poza `templates/`.
- Przeszły ponownie 22 testy routera CLI i 22 testy integracji HTTP. Treść HTML/CSS oraz statusy GET/HEAD i błędnych żądań pozostały zgodne z wcześniejszymi wynikami.
- Sprawdzenie z hosta potwierdziło HTTP 200 dla strony oraz HTTP 404 pod bezpośrednimi adresami `/templates/home.php` i `/src/Controller/HomeController.php`.

17 listopada 2026 zweryfikowano połączone zadania 1.6–1.7. Powyższe wyniki 1.1–1.5 opisują stan sprzed migracji HTML do layoutu.

- Kontrola składni przeszła dla obu kontrolerów, konfiguracji tras, pięciu szablonów PHP oraz skryptów testów kontrolera i HTTP.
- Testy zakończyły się kodem 0: `Controller tests: 34 scenarios passed; 0 skipped.`, `Router tests: 22 scenarios passed.` i `Routing HTTP tests: 30 scenarios passed.` Sprawdzono m.in. składanie widoku z layoutem, kodowanie tytułu i opisu, izolację danych, odrzucenie niedozwolonych ścieżek i dowiązań oraz brak częściowej odpowiedzi po błędzie szablonu.
- GET dla `/`, `/index.php` i `/index.html` zwrócił tę samą stronę HTML w UTF-8, a CSS pozostał dostępny bezpośrednio z Nginx. Testy objęły również HEAD, niedozwolone metody, błędne query i niedostępność plików szablonów przez HTTP.
- Wnętrze `main` porównano z HTML sprzed migracji: pozostało identyczne po ujednoliceniu zakończeń wierszy. Porównanie całego dokumentu potwierdziło zachowanie znaczników po normalizacji białych znaków między nimi oraz uwzględnieniu czterech zmian adresów: logo w nagłówku i stopce prowadzą do `/`, a dwa linki nawigacji do sekcji strony głównej.
- W Edge sprawdzono szerokości 320, 390, 768, 1024 i 1440 px bez poziomego przewijania. Potwierdzono pojedyncze elementy `html`, `head`, `body`, `main`, `header`, `nav` i `footer`, załadowanie CSS z typem `text/css`, widoczny fokus odnośnika „Przejdź do treści”, przejście Enterem do `main` i kolejnym Tabem do odnośnika treści oraz nawigację z `/index.html` do `/#world-preview`. Obejrzano zrzuty dla 390 i 1440 px.

Weryfikacja 1.6–1.7 dotyczyła jednej strony demonstracyjnej i jej migracji do szablonów. Nie była pełnym audytem WCAG ani potwierdzeniem działania planowanych procesów aplikacji; strony błędów i obsługę nieoczekiwanych wyjątków dodano następnie w 1.8.

27 listopada 2026 zweryfikowano zadanie 1.8 po wznowieniu prac:

- Uruchomiono istniejące usługi przez `docker compose up -d --wait`, korzystając z obrazu PHP przygotowanego w poprzedniej części prac. Nie usuwano danych ani wolumenów. `php --ini` potwierdziło wczytanie `worldgraph-errors.ini`.
- Kontrola składni przeszła dla `public/index.php`, `ErrorController.php`, `templates/errors/http.php` oraz trzech zmienionych skryptów testów. Odczyt ustawień procesu PHP potwierdził wyłączone `display_errors` i `display_startup_errors`, włączone `log_errors`, poziom `E_ALL`, `error_log=/proc/self/fd/2` i włączone `zend.exception_ignore_args`.
- Testy zakończyły się kodem 0: `Router tests: 22 scenarios passed.`, `Controller tests: 40 scenarios passed; 0 skipped.`, `Routing HTTP tests: 34 scenarios passed.` oraz `Error handling HTTP tests: 12 scenarios passed.`
- Sprawdzono komunikaty 400/404/405, zachowanie `Allow`, puste ciało odpowiedzi `HEAD`, działającą stronę główną i CSS oraz ochronę plików. Odizolowane testy awarii objęły 500 po wyjątku i obiekcie `Error`, usunięcie częściowego wyniku i nagłówków, awarie autoloadera, konfiguracji tras, widoku i layoutu oraz stałą odpowiedź awaryjną. Sprawdzono również techniczny log bez wiadomości wyjątku i danych żądania.
- Odczyt konfiguracji rzeczywistego PHP-FPM potwierdził wyłączone wyświetlanie diagnostyki, włączone logowanie na `/proc/self/fd/2` i pomijanie argumentów wyjątków. Jednorazowa kontrola przez FastCGI potwierdziła 500 w UTF-8 z `Cache-Control: no-store`, odrzucenie częściowego wyniku oraz nagłówków nieudanej odpowiedzi i puste ciało `HEAD`. `docker compose logs php` zawierało własny niesekretny znacznik i ogólne wpisy przechwyconej awarii bez syntetycznej wiadomości wyjątku. Pliki pomocnicze usunięto; nie dodano publicznego endpointu ani nie zmieniono tras działającej aplikacji.
- W Edge przez CDP sprawdzono strony 400, 404 i 405 przy szerokościach 320, 390, 768 i 1440 px: HTML w UTF-8, CSS z HTTP 200 i typem `text/css`, brak poziomego przewijania i powielonej struktury dokumentu. Przy 390 i 1440 px Tab zaznaczał widoczny odnośnik „Przejdź do treści”, Enter przechodził do `main`, a kolejny Tab do odnośnika powrotu; jego aktywacja otwierała stronę główną. Obejrzano zrzuty mobilny i desktopowy. Odpowiedzi 500 sprawdzano odizolowanymi testami HTTP, bez publicznej trasy awarii.

Dokumentacja użytych mechanizmów: [kolejność uruchamiania Compose](https://docs.docker.com/compose/how-tos/startup-order/), [oficjalny obraz PHP](https://hub.docker.com/_/php), [oficjalny obraz PostgreSQL i wolumeny](https://hub.docker.com/_/postgres).
