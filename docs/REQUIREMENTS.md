# REQUIREMENTS — kryteria WDPAI

**Warunek krytyczny:** obiektowy backend PHP. Punktacja według otrzymanej listy: maksymalnie 36 pkt; 5.0 od 34 pkt. Status w tej checkliście nie jest przyznaniem punktów ani oceną.

## Spis treści

- [Zasady statusów i dowodów](#zasady-statusów-i-dowodów)
- [Tabela zbiorcza](#tabela-zbiorcza)
- [Szczegóły kryteriów](#szczegóły-kryteriów)
  - [1. Dokumentacja w README.md](#1-dokumentacja-w-readmemd)
  - [2. Docker](#2-docker)
  - [3. Architektura aplikacji](#3-architektura-aplikacji)
  - [4. Routing](#4-routing)
  - [5. Obiektowy backend](#5-obiektowy-backend)
  - [6. Diagram ERD w Mermaid](#6-diagram-erd-w-mermaid)
  - [7. Git](#7-git)
  - [8. Realizacja tematu](#8-realizacja-tematu)
  - [9. HTML](#9-html)
  - [10. PostgreSQL](#10-postgresql)
  - [11. Złożoność bazy danych](#11-złożoność-bazy-danych)
  - [12. Eksport bazy do pliku .sql](#12-eksport-bazy-do-pliku-sql)
  - [13. PHP](#13-php)
  - [14. JavaScript](#14-javascript)
  - [15. Fetch API (AJAX)](#15-fetch-api-ajax)
  - [16. Design](#16-design)
  - [17. Responsywność](#17-responsywność)
  - [18. Tryb dark/light mode](#18-tryb-darklight-mode)
  - [19. Zgodność z WCAG](#19-zgodność-z-wcag)
  - [20. Logowanie](#20-logowanie)
  - [21. Sesja użytkownika](#21-sesja-użytkownika)
  - [22. Uprawnienia użytkowników](#22-uprawnienia-użytkowników)
  - [23. Role użytkowników](#23-role-użytkowników)
  - [24. Wylogowywanie](#24-wylogowywanie)
  - [25. Widoki, wyzwalacze, funkcje lub transakcje](#25-widoki-wyzwalacze-funkcje-lub-transakcje)
  - [26. Akcje na referencjach](#26-akcje-na-referencjach)
  - [27. Security Bingo](#27-security-bingo)
  - [28. Brak replikacji kodu](#28-brak-replikacji-kodu)
  - [29. Czystość i przejrzystość kodu](#29-czystość-i-przejrzystość-kodu)
  - [30. Tłumaczenia](#30-tłumaczenia)
  - [31. Testy jednostkowe](#31-testy-jednostkowe)
  - [32. CRON](#32-cron)
  - [33. PHP Mailer](#33-php-mailer)
  - [34. Upload zdjęcia](#34-upload-zdjęcia)
  - [35. Zmienne w .env lub Key Vault](#35-zmienne-w-env-lub-key-vault)
  - [36. Deploy](#36-deploy)
- [Źródła i kwestie do doprecyzowania](#źródła-i-kwestie-do-doprecyzowania)

## Zasady statusów i dowodów

Przegląd dokumentacyjny: **2026-11-13**.

Aktualizacja opisów implementacji dotyczących TODO 1.3–1.9: **2026-11-27**. Datowane wyniki 1.3–1.5 dotyczą stanu sprzed migracji do layoutu; wyniki kolejnych weryfikacji opisuje [README](../README.md#weryfikacja-konfiguracji).

| Status | Znaczenie |
|---|---|
| TODO | Brak implementacji potrzebnej do sprawdzenia warunku. |
| Częściowo | Istnieje konkretny fragment lub artefakt, ale nie cały wymagany rezultat. |
| Do weryfikacji | Istnieje przygotowana implementacja lub konfiguracja wymagająca aktualnego sprawdzenia. Sam plan nie wystarcza. |
| Zweryfikowane | Pełny warunek został sprawdzony, z dowodem, datą, zakresem i rewizją. |
| Do doprecyzowania | Brakuje dokładnej rubryki lub zakresu potrzebnego do oceny; brak nie oznacza zwolnienia z kryterium. |

- Dowód istniejący jest wskazany odnośnikiem lub opisem rzeczywistego odczytu. „Docelowo” i „Do uzupełnienia” opisują brakujący dowód, nie wykonaną pracę; nie tworzymy linków do nieistniejących testów lub raportów.
- [README — Weryfikacja konfiguracji](../README.md#weryfikacja-konfiguracji) zawiera historyczne sprawdzenia infrastruktury z 2 listopada 2026 oraz późniejsze sprawdzenia jednej strony HTML/CSS. Nie zastępują one aktualnych testów całej aplikacji.
- Zmieniając status na „Zweryfikowane”, zapisz datę, rewizję, środowisko, scenariusz lub polecenie, oczekiwany i rzeczywisty wynik oraz ograniczenia. Wynik powinien pozwalać powtórzyć sprawdzenie, bez ujawniania haseł, tokenów i danych prywatnych.
- Folder, plan, sam plik albo deklaracja w dokumentacji nie dowodzą wykonania całego kryterium. Zmiana kodu dotyczącego warunku wymaga ponownego sprawdzenia aktualności dowodu.
- Podstawowe MVP obejmuje etapy 1–9, a kontrola spójności etap 10. Kryteria CRON, PHP Mailer i deploy nadal należą do planu końcowej wersji, odpowiednio w etapach 11–12; nie są pomijane jako „nie dotyczy”.
- Przygotowanie i odhaczenie TODO 0.10 oznacza rozszerzenie tej checklisty. Nie oznacza ukończenia 36 kryteriów ani uruchomienia opisanych scenariuszy.

## Tabela zbiorcza

| # | Kryterium | Status | Istniejący dowód lub brak |
|---:|---|---|---|
| 1 | [Dokumentacja w README.md](#1-dokumentacja-w-readmemd) | Częściowo | [README](../README.md) opisuje środowisko i szablon. |
| 2 | [Docker](#2-docker) | Do weryfikacji | [Compose](../compose.yaml) i historyczne sprawdzenia w README. |
| 3 | [Architektura aplikacji](#3-architektura-aplikacji) | Częściowo | Istnieją [plan architektury](../ARCHITECTURE.md), [router](../src/Core/Http/Router.php), [kontroler strony głównej](../src/Controller/HomeController.php) i [wspólne renderowanie](../src/Controller/BaseController.php); warstwy biznesowe pozostają do implementacji. |
| 4 | [Routing](#4-routing) | Częściowo | [Przegląd istniejących testów](../README.md#sprawdzenie-routingu) potwierdza routing szkieletu PHP, błędny format ID w izolowanej trasie oraz metody HTTP. Chronione przepływy i rzeczywiste adresy modułów pozostają do wdrożenia i sprawdzenia. |
| 5 | [Obiektowy backend](#5-obiektowy-backend) | TODO | Współpracują [Router](../src/Core/Http/Router.php), [HomeController](../src/Controller/HomeController.php) i [BaseController](../src/Controller/BaseController.php); brak warstw Service, Repository i Domain oraz przypadków biznesowych. |
| 6 | [Diagram ERD w Mermaid](#6-diagram-erd-w-mermaid) | Częściowo | [Diagram pojęciowy](../ARCHITECTURE.md#powiązania-i-liczności), bez schematu bazy. |
| 7 | [Git](#7-git) | Częściowo | Repozytorium i historia istnieją; [zasady Git](../AGENTS.md#git-i-ochrona-zmian). |
| 8 | [Realizacja tematu](#8-realizacja-tematu) | Częściowo | [Zakres produktu](../PROJECT.md) i demonstracja, bez funkcji aplikacji. |
| 9 | [HTML](#9-html) | Częściowo | [Layout PHP](../templates/layouts/main.php) i [treść strony](../templates/home.php) z semantyczną strukturą; brak formularzy aplikacji. |
| 10 | [PostgreSQL](#10-postgresql) | Częściowo | [Kontener bazy](../compose.yaml); brak tabel aplikacji. |
| 11 | [Złożoność bazy danych](#11-złożoność-bazy-danych) | Do doprecyzowania | Brak szczegółowego progu prowadzącego i schematu aplikacji. |
| 12 | [Eksport bazy do pliku .sql](#12-eksport-bazy-do-pliku-sql) | TODO | Brak eksportu bazy aplikacji. |
| 13 | [PHP](#13-php) | Częściowo | Istnieją [punkt wejścia PHP](../public/index.php), [router](../src/Core/Http/Router.php), [kontroler](../src/Controller/HomeController.php), [renderowanie widoków](../src/Controller/BaseController.php) i testy CLI; operacje biznesowe pozostają do implementacji. |
| 14 | [JavaScript](#14-javascript) | TODO | Brak skryptów aplikacji; kryterium 14 oznacza JavaScript. |
| 15 | [Fetch API (AJAX)](#15-fetch-api-ajax) | TODO | Brak działającego Fetch i endpointów aplikacji. |
| 16 | [Design](#16-design) | Częściowo | [Szablon strony](../templates/home.php) i [CSS](../public/assets/css/style.css); status prototypu w TODO 0.5 to `[?]`. |
| 17 | [Responsywność](#17-responsywność) | Częściowo | [Media queries](../public/assets/css/style.css) i sprawdzenia strony startowej oraz stron błędów przy 320–1440 px; [wyniki](../README.md#weryfikacja-konfiguracji). |
| 18 | [Tryb dark/light mode](#18-tryb-darklight-mode) | TODO | [CSS](../public/assets/css/style.css) definiuje jeden ciemny wariant. |
| 19 | [Zgodność z WCAG](#19-zgodność-z-wcag) | Do doprecyzowania | Są elementy dostępności; brak określonej wersji i poziomu oceny. |
| 20 | [Logowanie](#20-logowanie) | TODO | Brak formularza i backendu logowania. |
| 21 | [Sesja użytkownika](#21-sesja-użytkownika) | TODO | Brak mechanizmu sesji aplikacji. |
| 22 | [Uprawnienia użytkowników](#22-uprawnienia-użytkowników) | TODO | [Macierz uprawnień](../PROJECT.md#role-prywatność-i-uprawnienia--ustalenia-07) jest planem. |
| 23 | [Role użytkowników](#23-role-użytkowników) | TODO | Role są zatwierdzone, lecz niezaimplementowane. |
| 24 | [Wylogowywanie](#24-wylogowywanie) | TODO | Brak operacji wylogowania. |
| 25 | [Widoki, wyzwalacze, funkcje lub transakcje](#25-widoki-wyzwalacze-funkcje-lub-transakcje) | TODO | Brak mechanizmu użytego przez aplikację. |
| 26 | [Akcje na referencjach](#26-akcje-na-referencjach) | TODO | Brak kluczy obcych i migracji aplikacji. |
| 27 | [Security Bingo](#27-security-bingo) | Do doprecyzowania | Brak dokładnej karty Bingo od prowadzącego. |
| 28 | [Brak replikacji kodu](#28-brak-replikacji-kodu) | Częściowo | [BaseController](../src/Controller/BaseController.php) składa widok ze [wspólnym layoutem](../templates/layouts/main.php) i fragmentami; brak pozostałych modułów aplikacji. |
| 29 | [Czystość i przejrzystość kodu](#29-czystość-i-przejrzystość-kodu) | Częściowo | Istnieją typowane [router](../src/Core/Http/Router.php) i [kontrolery](../src/Controller/BaseController.php), osobna konfiguracja tras, pliki statyczne i [zasady warstw](../AGENTS.md#granice-warstw). |
| 30 | [Tłumaczenia](#30-tłumaczenia) | TODO | Strona demonstracyjna jest po polsku; brak przełączania PL/EN. |
| 31 | [Testy jednostkowe](#31-testy-jednostkowe) | TODO | Istnieją skrypty testów [routera](../tests/Unit/RouterTest.php) i [kontrolera/renderowania](../tests/Unit/ControllerTest.php); brak wymaganych testów reguł domenowych. |
| 32 | [CRON](#32-cron) | TODO | Brak zadania i potwierdzonego harmonogramu. |
| 33 | [PHP Mailer](#33-php-mailer) | TODO | Brak wysyłki e-mail i konfiguracji SMTP aplikacji. |
| 34 | [Upload zdjęcia](#34-upload-zdjęcia) | TODO | Brak formularza i obsługi uploadu ilustracji. |
| 35 | [Zmienne w .env lub Key Vault](#35-zmienne-w-env-lub-key-vault) | Częściowo | [Szablon środowiska](../.env.example), Compose i ignorowanie .env. |
| 36 | [Deploy](#36-deploy) | TODO | Brak wskazanego wdrożenia aplikacji. |

## Szczegóły kryteriów

### 1. Dokumentacja w README.md

- **Warunek akceptacji:** README pozwala uruchomić aktualny projekt z czystego klonu i opisuje funkcje, ograniczenia, konfigurację, architekturę, routing, weryfikację i bezpieczne dane demonstracyjne; docelowo zawiera ERD oraz wymagane zrzuty widoków.
- **Weryfikacja:** Przejść instrukcję z czystego klonu i porównać opis z rzeczywistym zachowaniem oraz zawartością repozytorium.
- **Dowód:** Istnieje [README](../README.md) z uruchomieniem oraz opisem routingu, kontrolerów, layoutu i szablonów strony demonstracyjnej. Brak kompletnej dokumentacji działającej aplikacji. Docelowo: uzupełniony README oraz zapis wyniku odtworzenia środowiska z datą i rewizją.
- **TODO:** 12.6, 12.8, 12.9, 12.12; aktualizacje na bieżąco.

### 2. Docker

- **Warunek akceptacji:** Środowisko Nginx, PHP i PostgreSQL uruchamia się według README, aplikacja jest osiągalna przez HTTP, usługi mają właściwą gotowość, a odtworzenie kontenera nie niszczy danych wolumenu.
- **Weryfikacja:** Wykonać polecenia uruchomienia i sprawdzenia z README, ocenić stan usług i połączenie aplikacji z bazą. Nie usuwać danych ani wolumenów w celu testu.
- **Dowód:** Istnieją [compose.yaml](../compose.yaml), [Dockerfile PHP](../docker/php/Dockerfile), [konfiguracja Nginx](../docker/nginx/default.conf) i [historyczny opis weryfikacji](../README.md#weryfikacja-konfiguracji). Nie uruchamiano środowiska ponownie w 0.10. Docelowo: aktualne wyniki uruchomienia, HTTP i trwałości danych z datą i rewizją.
- **TODO:** 12.10, 12.12; polecenia bieżące w README.

### 3. Architektura aplikacji

- **Warunek akceptacji:** Kod realizuje podział Controller, Service, Repository i Domain zgodnie z AGENTS oraz ARCHITECTURE; HTTP, reguły domenowe, autoryzacja i SQL mają przypisane odpowiedzialności.
- **Weryfikacja:** Prześledzić rzeczywiste żądanie od routera do bazy i odpowiedzi oraz sprawdzić granice warstw w zmienionych modułach.
- **Dowód:** Istnieją [plan architektury](../ARCHITECTURE.md#planowany-przepływ), [instrukcje warstw](../AGENTS.md#granice-warstw), [router](../src/Core/Http/Router.php) i [punkt wejścia PHP](../public/index.php). [Konfiguracja tras](../config/routes.php) wskazuje metodę `HomeController::index()`, która korzysta z `BaseController::render()` i składa [widok strony](../templates/home.php) ze [wspólnym layoutem](../templates/layouts/main.php). Nagłówek, nawigacja i stopka są osobnymi fragmentami. Przepływ opisuje [README — Kontrolery i widoki](../README.md#kontrolery-i-widoki). Brak współpracujących warstw Service, Repository i Domain oraz operacji na danych aplikacji. Docelowo: implementacja warstw i opis prześledzonej operacji biznesowej.
- **TODO:** 1.1–1.7, 0.9, 12.13; implementacja modułów 3–11.

### 4. Routing

- **Warunek akceptacji:** Poprawne trasy i metody uruchamiają właściwe operacje; parametry są walidowane, a nieznana trasa i niedozwolona metoda zwracają odpowiednio 404 i 405. Przekierowanie po logowaniu pozostaje wewnątrz aplikacji.
- **Weryfikacja:** Sprawdzić poprawne adresy, błędne parametry, nieistniejące trasy i metody oraz chronione strony przez bezpośrednie żądania HTTP.
- **Dowód:** Istnieją [router](../src/Core/Http/Router.php), [definicje tras](../config/routes.php) oraz integracja w [punkcie wejścia](../public/index.php). [Nginx](../docker/nginx/default.conf) przekazuje trasy aplikacji do PHP z metodą i query; `/`, `/index.php` i `/index.html` wskazują `HomeController`. Wyniki 400/404/405 otrzymują treść z [kontrolera błędów](../src/Controller/ErrorController.php) i [wspólnego widoku](../templates/errors/http.php); punkt wejścia obsługuje przechwycone awarie jako 500, zachowuje `Allow` przy 405 i pomija ciało przy `HEAD`. 27 listopada 2026 przegląd 1.9 potwierdził istniejące pokrycie poprawnych tras, błędnego formatu ID w izolowanej trasie regex i niedozwolonych metod. Przeszły 22 scenariusze routera, 40 kontrolerów bez pominięć, 34 HTTP i 12 odizolowanych awarii. [Mapa scenariuszy](../README.md#sprawdzenie-routingu) i [wyniki z rewizją](../README.md#weryfikacja-konfiguracji) rozróżniają testowy format ID od walidacji rekordów bazy. Chronione przepływy z 3.6 oraz rzeczywiste adresy, dane i uprawnienia modułów pozostają do implementacji i sprawdzenia; całe kryterium nie jest jeszcze spełnione.
- **TODO:** 1.2–1.7, 1.8–1.9, 3.6.

### 5. Obiektowy backend

- **Warunek akceptacji:** Działające operacje aplikacji są realizowane przez współpracujące obiekty odpowiednich warstw; kod domenowy i przypadki użycia nie są zbiorem skryptów proceduralnych w szablonach.
- **Weryfikacja:** Przejść rzeczywisty przypadek użycia, wskazać odpowiedzialności obiektów oraz testy jego reguł.
- **Dowód:** [Router](../src/Core/Http/Router.php) dopasowuje trasę, a [punkt wejścia](../public/index.php) wywołuje metodę obiektu [HomeController](../src/Controller/HomeController.php), który dziedziczy wspólne renderowanie z [BaseController](../src/Controller/BaseController.php). To rzeczywisty przepływ strony głównej, opisany w [README — Kontrolery i widoki](../README.md#kontrolery-i-widoki). Nie ma jeszcze współpracujących obiektów warstw Service, Repository i Domain realizujących przypadek biznesowy zgodny z [ARCHITECTURE](../ARCHITECTURE.md). Samo renderowanie strony nie spełnia całego kryterium; pozostaje ono warunkiem krytycznym.
- **TODO:** 1.1–1.7, moduły 3–11, 12.13.

### 6. Diagram ERD w Mermaid

- **Warunek akceptacji:** ERD w Mermaid odzwierciedla rzeczywiście zaimplementowane tabele, klucze i liczności powiązań; aktualna wersja znajduje się bezpośrednio w README.
- **Weryfikacja:** Porównać diagram z migracjami i schematem działającej bazy oraz sprawdzić jego renderowanie.
- **Dowód:** Istnieje [diagram pojęciowy](../ARCHITECTURE.md#powiązania-i-liczności). Nie dowodzi zgodności z nieistniejącym jeszcze schematem bazy. Docelowo: ERD w README zgodny z migracjami i wynik porównania.
- **TODO:** 4.1, 12.6; aktualizacja przy zmianach modelu.

### 7. Git

- **Warunek akceptacji:** Historia przedstawia małe, logiczne zmiany, zachowuje pracę użytkownika i kończy się uzgodnionym połączeniem z main przez merge commit bez squashowania.
- **Weryfikacja:** Przejrzeć historię i diffy oraz sprawdzić końcowy sposób połączenia gałęzi; status repozytorium sam nie potwierdza całego kryterium.
- **Dowód:** Odczyt repozytorium potwierdza istniejącą historię; [AGENTS](../AGENTS.md#git-i-ochrona-zmian) opisuje sposób pracy. Docelowo: przegląd historii i identyfikator końcowego merge commita. Aktualny zakaz commitów i push pozostaje w mocy; 0.10 nie upoważnia do zmiany historii.
- **TODO:** 0.9, 12.11; praca zgodnie z autoryzacją użytkownika.

### 8. Realizacja tematu

- **Warunek akceptacji:** Działa zatwierdzony proces WorldGraph: konto, świat, współpraca i komentarze, karty, relacje i wpływy, grafy, prywatność, wydarzenia i historia zmian, zgodnie z PROJECT. Dalsze etapy pozostają w planie końcowej wersji.
- **Weryfikacja:** Przejść scenariusze MVP na różnych rolach i trzech poziomach prywatności, z rzeczywistym zapisem danych, historią i odrzuceniem nieaktualnej rewizji.
- **Dowód:** Istnieją [zatwierdzone ustalenia](../PROJECT.md) i [demonstracyjna treść strony](../templates/home.php) renderowana przez PHP. Nie jest to działający proces aplikacji. Docelowo: wyniki scenariuszy oraz odnośniki do zaimplementowanych modułów.
- **TODO:** 0.2–0.8, etapy 1–9, 12.1; rozszerzenia w etapach 10–11.

### 9. HTML

- **Warunek akceptacji:** Strony aplikacji mają poprawną semantyczną strukturę, nawigację i formularze z etykietami; dane dynamiczne są bezpiecznie umieszczane w HTML.
- **Weryfikacja:** Sprawdzić wygenerowany HTML, powiązania etykiet i pól, strukturę nagłówków oraz scenariusze z danymi użytkownika.
- **Dowód:** [Layout](../templates/layouts/main.php) zawiera strukturę dokumentu i `main`, a [nagłówek](../templates/partials/header.php), [nawigacja](../templates/partials/navigation.php) i [stopka](../templates/partials/footer.php) są współdzielonymi fragmentami. [Widok home.php](../templates/home.php) zawiera dotychczasowe sekcje i graf SVG. Tytuł oraz opis są kodowane w layoucie przy wstawianiu do HTML. Brak formularzy i danych użytkowników; wyniki weryfikacji migracji opisuje [README](../README.md#weryfikacja-konfiguracji). Docelowo: pozostałe widoki oraz wyniki walidacji wygenerowanych stron.
- **TODO:** 1.5–1.7, 2.2–2.3, 3.4–3.8, 12.3.

### 10. PostgreSQL

- **Warunek akceptacji:** Aplikacja zapisuje i odczytuje rzeczywiste dane PostgreSQL, uruchamia migracje, korzysta z parametrów zapytań i zachowuje dane po odtworzeniu kontenera.
- **Weryfikacja:** Sprawdzić połączenie przez aplikację, migracje i scenariusze zapisu/odczytu oraz trwałość bez usuwania wolumenu.
- **Dowód:** Istnieje [konfiguracja PostgreSQL](../compose.yaml) i [historyczny test SELECT 1](../README.md#weryfikacja-konfiguracji). Nie ma tabel i repozytoriów aplikacji. Docelowo: migracje, kod dostępu i wyniki testów na rzeczywistych danych.
- **TODO:** 3.1–3.3, 4.1–4.2, 5.1, 6.2, 12.12.

### 11. Złożoność bazy danych

- **Warunek akceptacji:** Roboczo dla WorldGraph: model obejmuje powiązane dane świata, członkostwo, karty, relacje, wpływy, wydarzenia i historię, z właściwymi kluczami i ograniczeniami. Oficjalne minimum złożoności trzeba potwierdzić; nie ustalamy własnej wymaganej liczby tabel.
- **Weryfikacja:** Po uzyskaniu dokładnej rubryki porównać ją z ERD, migracjami i zapytaniami; sprawdzić ograniczenia izolacji i przypadki niepoprawnych powiązań.
- **Dowód:** Istnieje [model pojęciowy](../ARCHITECTURE.md#planowany-model-pojęciowy), bez fizycznego schematu. Do uzupełnienia: dokładny warunek prowadzącego, schemat oraz wyniki sprawdzeń.
- **TODO:** 0.6–0.8, 4.1, 6.6, 9.3, 12.6.

### 12. Eksport bazy do pliku .sql

- **Warunek akceptacji:** Eksport SQL zawiera aktualny schemat i bezpieczne dane demonstracyjne; można go zaimportować do pustej bazy i uruchomić uzgodnione scenariusze.
- **Weryfikacja:** Wykonać eksport i próbny import w odizolowanej bazie testowej, bez nadpisania danych użytkownika; sprawdzić zgodność z migracjami i brak sekretów.
- **Dowód:** Brak pliku eksportu i wyniku importu. Docelowo: eksport SQL, instrukcja oraz wynik odtworzenia z datą i rewizją; sam pusty plik nie jest dowodem.
- **TODO:** 12.7.

### 13. PHP

- **Warunek akceptacji:** PHP obsługuje rzeczywiste żądania, walidację, operacje aplikacji i odpowiedzi, zgodnie z obiektową architekturą.
- **Weryfikacja:** Sprawdzić składnię oraz rzeczywiste żądania i testy zmienionych przypadków użycia; wykazać wykonanie kodu PHP aplikacji.
- **Dowód:** Istnieją [obraz PHP z pdo_pgsql](../docker/php/Dockerfile), [punkt wejścia](../public/index.php), [router](../src/Core/Http/Router.php) i [konfiguracja tras](../config/routes.php). [HomeController](../src/Controller/HomeController.php) składa [widok strony](../templates/home.php) z [layoutem](../templates/layouts/main.php) przez [wspólne renderowanie](../src/Controller/BaseController.php). [ErrorController](../src/Controller/ErrorController.php) obsługuje 400/404/405/500, a punkt wejścia przechwytuje nieoczekiwane wyjątki i zapewnia tekstową odpowiedź awaryjną. [Konfiguracja błędów PHP](../docker/php/conf.d/errors.ini) ukrywa diagnostykę przed użytkownikiem. Polecenia, datowane wyniki i ograniczenia opisują [README — Routing](../README.md#routing), [Błędy i logi](../README.md#błędy-i-logi) i [Weryfikacja konfiguracji](../README.md#weryfikacja-konfiguracji). Walidacja i operacje biznesowe we współpracujących warstwach pozostają do implementacji.
- **TODO:** 1.1–1.9, 3.1–3.10, kolejne moduły.

### 14. JavaScript

- **Warunek akceptacji:** JavaScript realizuje rzeczywiste interakcje aplikacji, np. motyw, aktualizację danych lub obsługę grafów, z czytelną obsługą błędów i stanów interfejsu.
- **Weryfikacja:** Sprawdzić interakcje w przeglądarce, błędy konsoli i scenariusze niepowodzenia; nie traktować samej obecności pliku JS jako dowodu.
- **Dowód:** Obecna [strona demonstracyjna](../templates/home.php) i [layout](../templates/layouts/main.php) nie zawierają skryptów aplikacji. Docelowo: rzeczywiste pliki JS i wyniki scenariuszy. Zachowano uzgodnioną interpretację Python → JavaScript.
- **TODO:** 0.1, 2.4, 6.7, 7.3–7.8.

### 15. Fetch API (AJAX)

- **Warunek akceptacji:** Co najmniej uzgodniony przepływ pobierania i zapisu relacji korzysta z Fetch GET i POST oraz JSON bez przeładowania strony, z obsługą statusów, ładowania, powodzenia i błędu.
- **Weryfikacja:** Sprawdzić żądania w narzędziach przeglądarki: poprawny zapis, walidację, odmowę dostępu i błąd połączenia; wynik w interfejsie musi odpowiadać stanowi serwera.
- **Dowód:** Brak implementacji Fetch. Docelowo: skrypt, odpowiadające mu trasy/kontrolery i wyniki scenariuszy; statyczny graf SVG nie jest dowodem AJAX.
- **TODO:** 6.7, 7.2, 4.10.

### 16. Design

- **Warunek akceptacji:** Zatwierdzony prototyp obejmuje minimum pięć istotnych ekranów w wariancie mobilnym i desktopowym, a wdrożony interfejs stosuje spójne elementy i stany zgodnie z PROJECT i TODO.
- **Weryfikacja:** Ocenić prototyp z użytkownikiem i porównać z działającymi widokami, w tym stanami pustymi, błędami oraz uprawnieniami.
- **Dowód:** Istnieje [szablon demonstracyjny](../templates/home.php), [wspólny layout](../templates/layouts/main.php) i [arkusz CSS](../public/assets/css/style.css). Migracja zachowuje dotychczasowy wygląd. TODO 0.5 ma status `[?]`, więc nie uznajemy kompletnego prototypu za zatwierdzony. Docelowo: odnośnik do zaakceptowanych widoków i porównanie z implementacją; PDF nie jest obecnie wymagany przez 0.5.
- **TODO:** 0.5, 2.1–2.3, 2.6, 12.3, 12.8.

### 17. Responsywność

- **Warunek akceptacji:** Istotne widoki aplikacji działają na telefonie, tablecie i desktopie bez utraty treści i funkcji; grafy mają użyteczną alternatywę listową.
- **Weryfikacja:** Sprawdzić minimum pięć istotnych widoków na małych i dużych ekranach, długie treści, formularze, nawigację oraz graf/listę.
- **Dowód:** Istnieją [reguły responsywne CSS](../public/assets/css/style.css). 17 listopada 2026 po migracji do layoutu sprawdzono stronę startową w Edge przy 320, 390, 768, 1024 i 1440 px. 27 listopada 2026 dodatkowo sprawdzono strony błędów 400, 404 i 405 przy 320, 390, 768 i 1440 px bez poziomego przewijania oraz obejrzano zrzuty 390 i 1440 px; [wyniki](../README.md#weryfikacja-konfiguracji). Nie dowodzi to responsywności przyszłej aplikacji. Docelowo: wyniki i zrzuty istotnych widoków jej procesów.
- **TODO:** 2.3, 2.7, 7.7, 12.3, 12.8.

### 18. Tryb dark/light mode

- **Warunek akceptacji:** Działają oba motywy, przełączanie przez data-theme, zapamiętanie wyboru i uwzględnienie preferencji systemowej; wybrany motyw jest ustawiany przed renderowaniem.
- **Weryfikacja:** Sprawdzić oba motywy, odświeżenie, przejścia między stronami, pierwszy odczyt preferencji systemowej i czytelność stanów interfejsu.
- **Dowód:** Istnieje tylko ciemna paleta w [CSS](../public/assets/css/style.css). Brak przełącznika i mechanizmu zapamiętywania. Docelowo: kod motywów/JS i wyniki scenariuszy.
- **TODO:** 2.4, 2.7, 12.4.

### 19. Zgodność z WCAG

- **Warunek akceptacji:** Po potwierdzeniu wymaganej wersji, poziomu i zakresu WCAG istotne przepływy spełniają uzgodnione kryteria. Robocze kontrole projektu obejmują klawiaturę, fokus, etykiety, kontrast, powiększenie i alternatywę dla grafu; nie są same w sobie deklaracją pełnej zgodności.
- **Weryfikacja:** Wykonać audyt według potwierdzonego zakresu, łącząc sprawdzenia ręczne z odpowiednimi narzędziami; zapisać wyniki, ograniczenia i poprawki.
- **Dowód:** [Layout](../templates/layouts/main.php) zawiera skip-link, [widok](../templates/home.php) opisy SVG, a [CSS](../public/assets/css/style.css) fokus i reduced-motion. Po migracji sprawdzono w Edge widoczny fokus oraz przejście klawiaturą do treści. 27 listopada 2026 sprawdzono też fokus, skip-link i aktywację odnośnika powrotu na stronach błędów przy szerokościach 390 i 1440 px; [wyniki](../README.md#weryfikacja-konfiguracji). Nie przeprowadzono pełnego audytu WCAG. Do uzupełnienia: wymagany poziom/wersja oraz raport z datą, zakresem i wynikami.
- **TODO:** 2.7, 7.7, 12.4.

### 20. Logowanie

- **Warunek akceptacji:** Poprawne dane logują użytkownika, błędne są odrzucane z bezpiecznym komunikatem, a hasła są weryfikowane na podstawie bezpiecznych skrótów. Powrót po logowaniu prowadzi tylko do dozwolonego adresu wewnętrznego.
- **Weryfikacja:** Sprawdzić poprawne i błędne dane, nieistniejące konto, powrót do chronionej strony oraz bezpieczne komunikaty bez ujawniania haseł i szczegółów serwera.
- **Dowód:** Brak kodu i testów logowania. Docelowo: formularz, klasy obsługi i wyniki scenariuszy bez sekretów.
- **TODO:** 3.4–3.6, 3.10.

### 21. Sesja użytkownika

- **Warunek akceptacji:** Sesja utrzymuje tożsamość po logowaniu, identyfikator jest regenerowany, cookie ma odpowiednie ustawienia, a aktualne prawa do świata nie są zastępowane nieaktualną rolą z sesji.
- **Weryfikacja:** Sprawdzić zmianę identyfikatora po logowaniu, zachowanie między żądaniami, ustawienia cookie i utratę uprawnień po zmianie roli lub wylogowaniu, bez publikowania wartości cookie.
- **Dowód:** Brak implementacji sesji. Docelowo: kod, opis ustawień i zanonimizowane wyniki sprawdzeń; sama obecność PHP-FPM nie jest dowodem.
- **TODO:** 3.5–3.7, 3.10, 4.8, 4.15.

### 22. Uprawnienia użytkowników

- **Warunek akceptacji:** Backend egzekwuje aktualne prawa obszar–operacja, prywatność, dostęp do kontekstu i izolację świata przy każdym żądaniu, także dla grafów, komentarzy, ilustracji i historii; brak zezwolenia oznacza odmowę.
- **Weryfikacja:** Wykonać scenariusze dozwolonych i zabronionych operacji, bezpośrednie żądania oraz zmianę/odebranie praw, w tym Custom i trzy poziomy prywatności. Sprawdzić brak dostępu do danych innego świata.
- **Dowód:** Istnieją [zatwierdzone zasady](../PROJECT.md#role-prywatność-i-uprawnienia--ustalenia-07), lecz brak autoryzacji aplikacji. Docelowo: implementacja i wyniki scenariuszy dla danych bieżących oraz historycznych.
- **TODO:** 4.6, 4.8, 4.11–4.12, 4.15, 5.8, 6.6, 8.4–8.6, 9.10.

### 23. Role użytkowników

- **Warunek akceptacji:** W świecie działa dokładnie jeden Twórca, zero lub więcej Właścicieli, Redaktor, Czytelnik i Custom. Tylko Twórca nadaje owner, usuwa świat i przekazuje rolę Twórcy; transfer jest atomowy. Gość nie jest członkostwem.
- **Weryfikacja:** Sprawdzić oddzielne konta i role w różnych światach, ochronę Twórcy, nadanie owner, transfer do istniejącego Właściciela oraz próby samodzielnego zwiększania praw.
- **Dowód:** Istnieje [model ról](../PROJECT.md#role-w-świecie), bez zapisu członkostwa i działających operacji. Docelowo: kod i wyniki scenariuszy zgodne z aktualną macierzą.
- **TODO:** 4.1–4.2, 4.6, 4.8, 4.12–4.13, 12.1.

### 24. Wylogowywanie

- **Warunek akceptacji:** Wylogowanie unieważnia sesję i cookie, prowadzi do Odkrywaj oraz odbiera dostęp wymagający zalogowania; publiczny odczyt nadal wynika z prywatności świata.
- **Weryfikacja:** Po wylogowaniu ponowić chronione żądanie i próbę zapisu, także z wcześniej otwartą stroną; sprawdzić zakończenie sesji bez ujawniania identyfikatorów.
- **Dowód:** Brak implementacji i wyników. Docelowo: kod operacji i scenariusz potwierdzający utratę chronionego dostępu.
- **TODO:** 3.7, 3.10, 12.1.

### 25. Widoki, wyzwalacze, funkcje lub transakcje

- **Warunek akceptacji:** Mechanizm z wymienionego w kryterium zestawu jest używany w rzeczywistym przypadku użycia. W WorldGraph planowane transakcje obejmują m.in. utworzenie świata z Twórcą i historią oraz zapis kompletu zmian wydarzenia.
- **Weryfikacja:** Sprawdzić pomyślny zapis i błąd w trakcie operacji: wynik jest kompletny albo zmiana jest wycofana, a historia nie gubi części operacji. Dla plików ocenić również kontrolę publikacji, poza samą transakcją SQL.
- **Dowód:** Brak implementacji transakcyjnej operacji aplikacji. Docelowo: kod i wyniki scenariuszy powodzenia/awarii. Nie dodajemy wszystkich czterech mechanizmów na zapas wyłącznie z powodu nazwy kryterium.
- **TODO:** 4.2, 4.9–4.10, 4.13, 5.8, 9.3.

### 26. Akcje na referencjach

- **Warunek akceptacji:** Referencje i reguły ich aktualizacji lub usuwania odpowiadają zatwierdzonemu modelowi; nie powstają niedozwolone powiązania światów ani fizyczne kaskady niszczące zachowaną historię. Wycofanie karty usuwa bieżące powiązania z obu końców bez usuwania innych kart i wydarzeń.
- **Weryfikacja:** Sprawdzić nieistniejące referencje, próbę połączenia światów oraz wycofanie karty, relacji i wydarzenia; porównać stan bieżący i zachowane wersje. Oddzielić działanie ograniczeń SQL od logicznego wycofania przez aplikację.
- **Dowód:** Jest [zatwierdzona polityka usuwania](../PROJECT.md#usuwanie-bieżącej-treści-i-zachowanie-historii), ale brak migracji i dowodu działania referencji. Docelowo: ograniczenia bazy, kod operacji i wyniki scenariuszy.
- **TODO:** 0.8, 4.7, 5.6, 5.10, 6.5–6.6, 6.8, 9.2–9.3.

### 27. Security Bingo

- **Warunek akceptacji:** Każda pozycja właściwej karty Security Bingo ma odpowiednią implementację i dowód. Zakres trzeba porównać z materiałem prowadzącego; przykładowe zabezpieczenia projektu nie są samodzielnie odtworzoną oficjalną kartą.
- **Weryfikacja:** Po uzyskaniu karty przypisać jej punkty do kodu i testów. Sprawdzić właściwe dla projektu przypadki, m.in. autoryzację, izolację, CSRF, XSS, SQL Injection, sesje, Markdown i uploady.
- **Dowód:** Istnieją [reguły bezpieczeństwa pracy](../AGENTS.md), ochrona plików w [Nginx](../docker/nginx/default.conf) oraz [obsługa błędów aplikacji](../public/index.php) z ogólnym komunikatem 500 i [konfiguracją ukrywającą diagnostykę PHP](../docker/php/conf.d/errors.ini). Zakres i weryfikację opisują [README — Błędy i logi](../README.md#błędy-i-logi). Brak źródłowej karty Bingo i kompletnego raportu. Do uzupełnienia: karta oraz przypisanie implementacji i dowodów jej punktom; te zabezpieczenia nie potwierdzają całego kryterium.
- **TODO:** 3.9, 4.8, 5.7–5.8, 5.12, 6.6–6.8, 8.6, 12.2, 12.9.

### 28. Brak replikacji kodu

- **Warunek akceptacji:** Powtarzalna nawigacja, renderowanie, walidacja i operacje mają wspólne miejsca odpowiednie dla warstw; nie ma niezależnych kopii tej samej reguły ani osobnych kopii kart dla każdego grafu.
- **Weryfikacja:** Przejrzeć zmienione moduły i prześledzić wspólny fragment używany w kilku widokach lub operacjach, bez budowania abstrakcji na zapas.
- **Dowód:** [BaseController::render()](../src/Controller/BaseController.php) jest wspólnym miejscem wyboru, walidacji i buforowanego wykonania widoku oraz opcjonalnego layoutu; korzystają z niego [HomeController](../src/Controller/HomeController.php) i [ErrorController](../src/Controller/ErrorController.php). [Treść home.php](../templates/home.php) jest składana ze [wspólnym layoutem](../templates/layouts/main.php) oraz fragmentami [nagłówka](../templates/partials/header.php), [nawigacji](../templates/partials/navigation.php) i [stopki](../templates/partials/footer.php). Dawny plik HTML został zastąpiony szablonami, a jego adres wskazuje ten sam kontroler. Zakres opisuje [README — Kontrolery i widoki](../README.md#kontrolery-i-widoki). Layout obsługuje widok strony głównej oraz wspólny widok błędów; brak pozostałych modułów i dowodu współdzielenia ich reguł oraz operacji. Infrastruktura nie potwierdza całego kryterium.
- **TODO:** 1.5–1.8, 2.2–2.3, 12.13; przegląd kolejnych modułów.

### 29. Czystość i przejrzystość kodu

- **Warunek akceptacji:** Kod ma jasne odpowiedzialności i nazwy, typowanie PHP, parametryzowane zapytania oraz kontrolowaną obsługę błędów, zgodnie z AGENTS. Opis dokumentacji odpowiada działającemu kodowi.
- **Weryfikacja:** Wykonać przegląd zmienionych modułów, kontrolę składni i odpowiednie testy; sprawdzić diff pod kątem niezwiązanych zmian i sekretów.
- **Dowód:** Istnieją [widok](../templates/home.php), [layout](../templates/layouts/main.php), [CSS](../public/assets/css/style.css), instrukcje pracy oraz typowane [router](../src/Core/Http/Router.php) i [kontrolery](../src/Controller/BaseController.php) z osobną [konfiguracją tras](../config/routes.php). `HomeController` wybiera widok i layout; `BaseController` sprawdza nazwy i rzeczywiste ścieżki w stałym katalogu `templates/`, przekazuje dane jako tablicę `$data` w odizolowanym zakresie i buforuje wynik obu szablonów. Punkt wejścia obsługuje również przechwycone wyjątki, usuwa częściową odpowiedź i korzysta z kontrolera błędów lub stałego komunikatu awaryjnego. Zakres weryfikacji opisują [README — Routing](../README.md#routing) i [README — Kontrolery i widoki](../README.md#kontrolery-i-widoki) oraz [Błędy i logi](../README.md#błędy-i-logi). Przegląd i sprawdzenia kolejnych modułów należy wykonywać wraz z ich implementacją.
- **TODO:** 0.9, 1.1–1.9, 12.13; weryfikacja na bieżąco.

### 30. Tłumaczenia

- **Warunek akceptacji:** Interfejs PL/EN obejmuje nawigację, formularze, walidację i komunikaty błędów; wybór języka zachowuje się spójnie między stronami. Nie wymaga automatycznego tłumaczenia autorskiej treści świata.
- **Weryfikacja:** Przejść istotne przepływy w obu językach, także błędne dane i puste stany; sprawdzić brak brakujących kluczy i problemów z dłuższymi etykietami.
- **Dowód:** Istnieje [layout z lang=pl](../templates/layouts/main.php), bez mechanizmu tłumaczeń. Docelowo: zasoby językowe, kod wyboru języka i wyniki scenariuszy.
- **TODO:** 2.5, 12.5.

### 31. Testy jednostkowe

- **Warunek akceptacji:** Istnieją izolowane testy rzeczywistych reguł domenowych, obejmujące przypadki poprawne, błędne i graniczne. Są powtarzalne i nie opierają wyniku na danych użytkownika.
- **Weryfikacja:** Uruchomić udokumentowane polecenie testów i zachować wynik; sprawdzić, jakie zachowanie badają i czy odróżniają błąd od poprawnej realizacji.
- **Dowód:** Istnieją samodzielne skrypty testów [routera](../tests/Unit/RouterTest.php) i [kontrolera/renderowania](../tests/Unit/ControllerTest.php), uruchamiane przez PHP CLI bez nowego frameworka. Polecenia i zakres opisują [README — Routing](../README.md#routing) i [README — Kontrolery i widoki](../README.md#kontrolery-i-widoki), a datowane wyniki [README — Weryfikacja konfiguracji](../README.md#weryfikacja-konfiguracji). 27 listopada 2026 po dodaniu obsługi błędów przeszły 22 scenariusze routera i 40 scenariuszy kontrolera/renderowania bez pominięć. Zakres obejmuje składanie z layoutem i kodowanie metadanych, izolację danych, ochronę ścieżek, buforowanie po błędzie oraz stałe komunikaty kontrolera błędów. Testy infrastruktury HTTP i renderowania nie zastępują wymaganych testów rzeczywistych reguł domenowych, które powstaną wraz z modułami.
- **TODO:** 10.4, 12.12; testy reguł wraz z implementacją modułów.

### 32. CRON

- **Warunek akceptacji:** Zadanie usuwające wygasłe zaproszenia wykonuje się zgodnie z udokumentowanym harmonogramem, jest idempotentne i zapisuje bezpieczny wynik; nie czyści zachowanej historii świata ani plików.
- **Weryfikacja:** Sprawdzić uruchomienie ręczne, ponowne uruchomienie i rzeczywiste wywołanie przez harmonogram na danych testowych.
- **Dowód:** Brak skryptu i harmonogramu. Docelowo: kod, konfiguracja harmonogramu oraz wyniki wykonania z czasem; samo ręczne uruchomienie nie potwierdza harmonogramu.
- **TODO:** 11.5–11.7, 12.9.

### 33. PHP Mailer

- **Warunek akceptacji:** Zaproszenie jest wysyłane z PHP przez mechanizm zgodny z kryterium PHP Mailer, a błędy transportu nie ujawniają sekretów ani nie powodują utraty danych. Jeśli prowadzący wymaga konkretnej biblioteki, należy to potwierdzić przed jej wyborem.
- **Weryfikacja:** Sprawdzić wiadomość zaproszenia w środowisku testowym, poprawny link, awarię transportu i bezpieczne komunikaty. Porównać wybrany mechanizm z dokładnym wymaganiem przedmiotu.
- **Dowód:** Brak implementacji mailera, SMTP i dowodu wysyłki. Docelowo: kod, konfiguracja bez danych uwierzytelniających oraz bezpieczny zapis wyniku. Ta checklista nie zatwierdza instalacji biblioteki.
- **TODO:** 11.1–11.4, 11.7, 12.9.

### 34. Upload zdjęcia

- **Warunek akceptacji:** Upload ilustracji i okładki sprawdza rozmiar, rzeczywisty MIME i dozwolone rozszerzenie, nadaje bezpieczną unikalną nazwę oraz respektuje świat i uprawnienia. Podmiana lub wycofanie zachowuje historyczne bajty pliku.
- **Weryfikacja:** Sprawdzić poprawny plik, niedozwolony typ, przekroczenie limitu, brak uprawnień, dostęp bezpośrednim adresem oraz podmianę i awarię zapisu wraz z historią.
- **Dowód:** Brak kodu uploadu i jego testów; sam katalog storage/uploads nie jest dowodem. Docelowo: formularz, walidacja serwerowa i wyniki scenariuszy dla bieżących oraz historycznych plików.
- **TODO:** 5.7–5.10, 8.6.

### 35. Zmienne w .env lub Key Vault

- **Warunek akceptacji:** Konfiguracja i sekrety są dostarczane przez środowisko lub uzgodniony magazyn, bez wartości zaszytych w kodzie lub historii zmian świata. .env nie jest śledzony, a przykład konfiguracji nie zawiera prawdziwych sekretów.
- **Weryfikacja:** Sprawdzić nazwy wymaganych zmiennych i ich odczyt przez aplikację, działanie bez wymaganej konfiguracji oraz ignorowanie .env. Nie wyświetlać wartości sekretów ani nie nadpisywać istniejącego pliku.
- **Dowód:** Istnieją [.env.example](../.env.example) i [odwołania do zmiennych w Compose](../compose.yaml). Odczyt Git potwierdził, że .env jest ignorowany i nie jest śledzony; jego zawartości nie odczytywano. Brak kompletnego odczytu konfiguracji backendu i SMTP. Docelowo: kod konfiguracji oraz wyniki sprawdzeń bez wartości sekretów.
- **TODO:** 3.1, 11.3, 12.10.

### 36. Deploy

- **Warunek akceptacji:** Aplikacja działa pod udokumentowanym publicznym adresem, odpowiada uzgodnionej wersji main i ma właściwą konfigurację produkcyjną oraz trwały zapis danych i plików.
- **Weryfikacja:** Odtworzyć środowisko według README i sprawdzić kluczowe przepływy pod adresem wdrożenia, HTTP oraz zapis i trwałość danych.
- **Dowód:** Brak adresu i wyniku wdrożenia. Docelowo: adres aplikacji, identyfikator wdrożonej rewizji i wyniki scenariuszy. Lokalny adres localhost i adres repozytorium nie są dowodem deployu.
- **TODO:** 12.10–12.12.

## Źródła i kwestie do doprecyzowania

- Lista obejmuje 36 kryteriów z nazwami, numeracją i punktacją zachowanymi w tym dokumencie. Źródłem wymagań przedmiotu są [materiały prowadzącego](https://ii.pk.edu.pl/~awidlak/subject/16), uzupełnione [odnośnikami w TODO](../TODO.md#źródła). Przegląd dokumentacyjny z 2026-11-13 nie obejmował pełnego odczytu oficjalnej rubryki. Dlatego warunki akceptacji WorldGraph służą planowaniu i weryfikacji projektu, a nie ustalaniu własnych oficjalnych progów ani potwierdzeniu punktów.
- Przed oceną #11 potwierdzić dokładny próg złożoności bazy; przed #19 wersję, poziom i zakres WCAG; przed #27 właściwą kartę Security Bingo. Robocze warunki WorldGraph są jawnie oznaczone i nie zastępują tych materiałów.
- Przed wyborem mechanizmu w #33 potwierdzić, czy „PHP Mailer” wymaga konkretnej biblioteki; wybór i instalacja zależności należą do implementacji poczty. Dla grafów przyjęto [zewnętrzną bibliotekę JavaScript wyłącznie do wizualizacji i interakcji](../ARCHITECTURE.md#biblioteka-grafowa--ustalenia-011), z własnym modelem, backendem i integracją Fetch. Informację o zgodzie prowadzącego odnotowano na podstawie oświadczenia autora projektu z 2026-11-13, bez niezależnej weryfikacji tej zgody. Konkretna biblioteka nadal wymaga wyboru i sprawdzenia w TODO 7.1; samo dopuszczenie biblioteki nie potwierdza implementacji ani spełnienia kryterium JavaScript, Fetch lub grafów.
- Zakres produktu i aktualne role, prywatność, formaty, usuwanie oraz historia: [PROJECT.md](../PROJECT.md). Model i odpowiedzialności: [ARCHITECTURE.md](../ARCHITECTURE.md). Sposób pracy i weryfikacji: [AGENTS.md](../AGENTS.md).
