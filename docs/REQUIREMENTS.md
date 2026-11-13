# REQUIREMENTS — kryteria WDPAI

**Warunek krytyczny:** obiektowy backend PHP. Punktacja według otrzymanej listy: maksymalnie 36 pkt; 5.0 od 34 pkt. Status w tej checkliście nie jest przyznaniem punktów ani oceną.

## Zasady statusów i dowodów

Przegląd dokumentacyjny: **2026-11-13**.

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
| 3 | [Architektura aplikacji](#3-architektura-aplikacji) | Częściowo | [Architektura](../ARCHITECTURE.md) jest planem; brak backendu. |
| 4 | [Routing](#4-routing) | TODO | Brak routera aplikacji; Nginx obsługuje istniejące pliki. |
| 5 | [Obiektowy backend](#5-obiektowy-backend) | TODO | Brak klas backendu; katalogi nie są implementacją. |
| 6 | [Diagram ERD w Mermaid](#6-diagram-erd-w-mermaid) | Częściowo | [Diagram pojęciowy](../ARCHITECTURE.md#powiązania-i-liczności), bez schematu bazy. |
| 7 | [Git](#7-git) | Częściowo | Repozytorium i historia istnieją; [zasady Git](../AGENTS.md#git-i-ochrona-zmian). |
| 8 | [Realizacja tematu](#8-realizacja-tematu) | Częściowo | [Zakres produktu](../PROJECT.md) i demonstracja, bez funkcji aplikacji. |
| 9 | [HTML](#9-html) | Częściowo | [Statyczna strona](../public/index.html) z semantyczną strukturą. |
| 10 | [PostgreSQL](#10-postgresql) | Częściowo | [Kontener bazy](../compose.yaml); brak tabel aplikacji. |
| 11 | [Złożoność bazy danych](#11-złożoność-bazy-danych) | Do doprecyzowania | Brak szczegółowego progu prowadzącego i schematu aplikacji. |
| 12 | [Eksport bazy do pliku .sql](#12-eksport-bazy-do-pliku-sql) | TODO | Brak eksportu bazy aplikacji. |
| 13 | [PHP](#13-php) | TODO | [PHP-FPM](../docker/php/Dockerfile) jest środowiskiem, bez kodu aplikacji. |
| 14 | [JavaScript](#14-javascript) | TODO | Brak skryptów aplikacji; kryterium 14 oznacza JavaScript. |
| 15 | [Fetch API (AJAX)](#15-fetch-api-ajax) | TODO | Brak działającego Fetch i endpointów aplikacji. |
| 16 | [Design](#16-design) | Częściowo | [HTML](../public/index.html) i [CSS](../public/assets/css/style.css); status prototypu w TODO 0.5 to `[?]`. |
| 17 | [Responsywność](#17-responsywność) | Częściowo | [Media queries](../public/assets/css/style.css) i dawne sprawdzenia jednej strony. |
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
| 28 | [Brak replikacji kodu](#28-brak-replikacji-kodu) | TODO | Brak wspólnych widoków i modułów aplikacji. |
| 29 | [Czystość i przejrzystość kodu](#29-czystość-i-przejrzystość-kodu) | Częściowo | Istnieją czytelne pliki statyczne i [zasady warstw](../AGENTS.md#granice-warstw). |
| 30 | [Tłumaczenia](#30-tłumaczenia) | TODO | Strona statyczna jest po polsku; brak przełączania PL/EN. |
| 31 | [Testy jednostkowe](#31-testy-jednostkowe) | TODO | Brak testów i konfiguracji narzędzia testowego. |
| 32 | [CRON](#32-cron) | TODO | Brak zadania i potwierdzonego harmonogramu. |
| 33 | [PHP Mailer](#33-php-mailer) | TODO | Brak wysyłki e-mail i konfiguracji SMTP aplikacji. |
| 34 | [Upload zdjęcia](#34-upload-zdjęcia) | TODO | Brak formularza i obsługi uploadu ilustracji. |
| 35 | [Zmienne w .env lub Key Vault](#35-zmienne-w-env-lub-key-vault) | Częściowo | [Szablon środowiska](../.env.example), Compose i ignorowanie .env. |
| 36 | [Deploy](#36-deploy) | TODO | Brak wskazanego wdrożenia aplikacji. |

## Szczegóły kryteriów

### 1. Dokumentacja w README.md

- **Warunek akceptacji:** README pozwala uruchomić aktualny projekt z czystego klonu i opisuje funkcje, ograniczenia, konfigurację, architekturę, routing, weryfikację i bezpieczne dane demonstracyjne; docelowo zawiera ERD oraz wymagane zrzuty widoków.
- **Weryfikacja:** Przejść instrukcję z czystego klonu i porównać opis z rzeczywistym zachowaniem oraz zawartością repozytorium.
- **Dowód:** Istnieje [README](../README.md) z uruchomieniem i opisem statycznego szablonu. Brak kompletnej dokumentacji działającej aplikacji. Docelowo: uzupełniony README oraz zapis wyniku odtworzenia środowiska z datą i rewizją.
- **TODO:** 12.6, 12.8, 12.9, 12.12; aktualizacje na bieżąco.

### 2. Docker

- **Warunek akceptacji:** Środowisko Nginx, PHP i PostgreSQL uruchamia się według README, aplikacja jest osiągalna przez HTTP, usługi mają właściwą gotowość, a odtworzenie kontenera nie niszczy danych wolumenu.
- **Weryfikacja:** Wykonać polecenia uruchomienia i sprawdzenia z README, ocenić stan usług i połączenie aplikacji z bazą. Nie usuwać danych ani wolumenów w celu testu.
- **Dowód:** Istnieją [compose.yaml](../compose.yaml), [Dockerfile PHP](../docker/php/Dockerfile), [konfiguracja Nginx](../docker/nginx/default.conf) i [historyczny opis weryfikacji](../README.md#weryfikacja-konfiguracji). Nie uruchamiano środowiska ponownie w 0.10. Docelowo: aktualne wyniki uruchomienia, HTTP i trwałości danych z datą i rewizją.
- **TODO:** 12.10, 12.12; polecenia bieżące w README.

### 3. Architektura aplikacji

- **Warunek akceptacji:** Kod realizuje podział Controller, Service, Repository i Domain zgodnie z AGENTS oraz ARCHITECTURE; HTTP, reguły domenowe, autoryzacja i SQL mają przypisane odpowiedzialności.
- **Weryfikacja:** Prześledzić rzeczywiste żądanie od routera do bazy i odpowiedzi oraz sprawdzić granice warstw w zmienionych modułach.
- **Dowód:** Istnieją [plan architektury](../ARCHITECTURE.md#planowany-przepływ) i [instrukcje warstw](../AGENTS.md#granice-warstw). Nie ma jeszcze implementacji backendu. Docelowo: odnośniki do rzeczywistych klas i opis prześledzonej operacji.
- **TODO:** 1.1–1.6, 0.9, 12.13; implementacja modułów 3–11.

### 4. Routing

- **Warunek akceptacji:** Poprawne trasy i metody uruchamiają właściwe operacje; parametry są walidowane, a nieznana trasa i niedozwolona metoda zwracają odpowiednio 404 i 405. Przekierowanie po logowaniu pozostaje wewnątrz aplikacji.
- **Weryfikacja:** Sprawdzić poprawne adresy, błędne parametry, nieistniejące trasy i metody oraz chronione strony przez bezpośrednie żądania HTTP.
- **Dowód:** Istnieje tylko [konfiguracja obsługi plików w Nginx](../docker/nginx/default.conf), nie router aplikacji. Docelowo: kod routera, konfiguracja front controllera oraz wyniki scenariuszy HTTP.
- **TODO:** 1.2–1.4, 1.8–1.9, 3.6.

### 5. Obiektowy backend

- **Warunek akceptacji:** Działające operacje aplikacji są realizowane przez współpracujące obiekty odpowiednich warstw; kod domenowy i przypadki użycia nie są zbiorem skryptów proceduralnych w szablonach.
- **Weryfikacja:** Przejść rzeczywisty przypadek użycia, wskazać odpowiedzialności obiektów oraz testy jego reguł.
- **Dowód:** [ARCHITECTURE](../ARCHITECTURE.md) określa plan. Brak dowodu działającego obiektowego backendu. Docelowo: rzeczywiste klasy, ich użycie i wyniki testów. To kryterium pozostaje warunkiem krytycznym.
- **TODO:** 1.1–1.6, moduły 3–11, 12.13.

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
- **Dowód:** Istnieją [zatwierdzone ustalenia](../PROJECT.md) i [statyczna demonstracja](../public/index.html). Nie jest to działający proces aplikacji. Docelowo: wyniki scenariuszy oraz odnośniki do zaimplementowanych modułów.
- **TODO:** 0.2–0.8, etapy 1–9, 12.1; rozszerzenia w etapach 10–11.

### 9. HTML

- **Warunek akceptacji:** Strony aplikacji mają poprawną semantyczną strukturę, nawigację i formularze z etykietami; dane dynamiczne są bezpiecznie umieszczane w HTML.
- **Weryfikacja:** Sprawdzić wygenerowany HTML, powiązania etykiet i pól, strukturę nagłówków oraz scenariusze z danymi użytkownika.
- **Dowód:** Istnieje [public/index.html](../public/index.html) z nagłówkiem, nawigacją, main i stopką. Brak widoków formularzy i dynamicznej aplikacji. Docelowo: szablony oraz wyniki walidacji wygenerowanych stron.
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
- **Dowód:** Istnieje [obraz PHP z pdo_pgsql](../docker/php/Dockerfile), lecz nie backend aplikacji. Docelowo: kod PHP oraz wyniki kontroli składni i działania; sam proces FPM nie spełnia warunku.
- **TODO:** 1.1–1.9, 3.1–3.10, kolejne moduły.

### 14. JavaScript

- **Warunek akceptacji:** JavaScript realizuje rzeczywiste interakcje aplikacji, np. motyw, aktualizację danych lub obsługę grafów, z czytelną obsługą błędów i stanów interfejsu.
- **Weryfikacja:** Sprawdzić interakcje w przeglądarce, błędy konsoli i scenariusze niepowodzenia; nie traktować samej obecności pliku JS jako dowodu.
- **Dowód:** Obecna [strona statyczna](../public/index.html) nie zawiera skryptów aplikacji. Docelowo: rzeczywiste pliki JS i wyniki scenariuszy. Zachowano uzgodnioną interpretację Python → JavaScript.
- **TODO:** 0.1, 2.4, 6.7, 7.3–7.8.

### 15. Fetch API (AJAX)

- **Warunek akceptacji:** Co najmniej uzgodniony przepływ pobierania i zapisu relacji korzysta z Fetch GET i POST oraz JSON bez przeładowania strony, z obsługą statusów, ładowania, powodzenia i błędu.
- **Weryfikacja:** Sprawdzić żądania w narzędziach przeglądarki: poprawny zapis, walidację, odmowę dostępu i błąd połączenia; wynik w interfejsie musi odpowiadać stanowi serwera.
- **Dowód:** Brak implementacji Fetch. Docelowo: skrypt, odpowiadające mu trasy/kontrolery i wyniki scenariuszy; statyczny graf SVG nie jest dowodem AJAX.
- **TODO:** 6.7, 7.2, 4.10.

### 16. Design

- **Warunek akceptacji:** Zatwierdzony prototyp obejmuje minimum pięć istotnych ekranów w wariancie mobilnym i desktopowym, a wdrożony interfejs stosuje spójne elementy i stany zgodnie z PROJECT i TODO.
- **Weryfikacja:** Ocenić prototyp z użytkownikiem i porównać z działającymi widokami, w tym stanami pustymi, błędami oraz uprawnieniami.
- **Dowód:** Istnieje [szablon demonstracyjny](../public/index.html) i [arkusz CSS](../public/assets/css/style.css). TODO 0.5 ma status `[?]`, więc nie uznajemy kompletnego prototypu za zatwierdzony. Docelowo: odnośnik do zaakceptowanych widoków i porównanie z implementacją; PDF nie jest obecnie wymagany przez 0.5.
- **TODO:** 0.5, 2.1–2.3, 2.6, 12.3, 12.8.

### 17. Responsywność

- **Warunek akceptacji:** Istotne widoki aplikacji działają na telefonie, tablecie i desktopie bez utraty treści i funkcji; grafy mają użyteczną alternatywę listową.
- **Weryfikacja:** Sprawdzić minimum pięć istotnych widoków na małych i dużych ekranach, długie treści, formularze, nawigację oraz graf/listę.
- **Dowód:** Istnieją [reguły responsywne CSS](../public/assets/css/style.css) i [opis wcześniejszych sprawdzeń](../README.md#weryfikacja-konfiguracji) dla jednej strony przy 320, 390, 768, 1024 i 1440 px. Nie dowodzi to responsywności przyszłej aplikacji. Docelowo: aktualne wyniki i zrzuty wielu widoków.
- **TODO:** 2.3, 2.7, 7.7, 12.3, 12.8.

### 18. Tryb dark/light mode

- **Warunek akceptacji:** Działają oba motywy, przełączanie przez data-theme, zapamiętanie wyboru i uwzględnienie preferencji systemowej; wybrany motyw jest ustawiany przed renderowaniem.
- **Weryfikacja:** Sprawdzić oba motywy, odświeżenie, przejścia między stronami, pierwszy odczyt preferencji systemowej i czytelność stanów interfejsu.
- **Dowód:** Istnieje tylko ciemna paleta w [CSS](../public/assets/css/style.css). Brak przełącznika i mechanizmu zapamiętywania. Docelowo: kod motywów/JS i wyniki scenariuszy.
- **TODO:** 2.4, 2.7, 12.4.

### 19. Zgodność z WCAG

- **Warunek akceptacji:** Po potwierdzeniu wymaganej wersji, poziomu i zakresu WCAG istotne przepływy spełniają uzgodnione kryteria. Robocze kontrole projektu obejmują klawiaturę, fokus, etykiety, kontrast, powiększenie i alternatywę dla grafu; nie są same w sobie deklaracją pełnej zgodności.
- **Weryfikacja:** Wykonać audyt według potwierdzonego zakresu, łącząc sprawdzenia ręczne z odpowiednimi narzędziami; zapisać wyniki, ograniczenia i poprawki.
- **Dowód:** W [HTML](../public/index.html) są m.in. skip-link i opisy SVG, a w [CSS](../public/assets/css/style.css) fokus i reduced-motion. Nie przeprowadzono pełnego audytu WCAG. Do uzupełnienia: wymagany poziom/wersja oraz raport z datą, zakresem i wynikami.
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
- **Dowód:** Istnieją [reguły bezpieczeństwa pracy](../AGENTS.md) i fragmenty ochrony w [Nginx](../docker/nginx/default.conf). Brak karty Bingo, backendu i kompletnego raportu. Do uzupełnienia: źródłowa karta oraz odnośniki i wyniki dla jej punktów.
- **TODO:** 3.9, 4.8, 5.7–5.8, 5.12, 6.6–6.8, 8.6, 12.2, 12.9.

### 28. Brak replikacji kodu

- **Warunek akceptacji:** Powtarzalna nawigacja, renderowanie, walidacja i operacje mają wspólne miejsca odpowiednie dla warstw; nie ma niezależnych kopii tej samej reguły ani osobnych kopii kart dla każdego grafu.
- **Weryfikacja:** Przejrzeć zmienione moduły i prześledzić wspólny fragment używany w kilku widokach lub operacjach, bez budowania abstrakcji na zapas.
- **Dowód:** Obecny [HTML](../public/index.html) jest pojedynczym szablonem demonstracyjnym. Brak dowodu ponownego użycia w aplikacji. Docelowo: wspólne szablony/klasy i wynik przeglądu.
- **TODO:** 1.5–1.7, 2.2–2.3, 12.13; przegląd kolejnych modułów.

### 29. Czystość i przejrzystość kodu

- **Warunek akceptacji:** Kod ma jasne odpowiedzialności i nazwy, typowanie PHP, parametryzowane zapytania oraz kontrolowaną obsługę błędów, zgodnie z AGENTS. Opis dokumentacji odpowiada działającemu kodowi.
- **Weryfikacja:** Wykonać przegląd zmienionych modułów, kontrolę składni i odpowiednie testy; sprawdzić diff pod kątem niezwiązanych zmian i sekretów.
- **Dowód:** Istnieją [HTML](../public/index.html), [CSS](../public/assets/css/style.css) i instrukcje pracy. Nie przeprowadzono przeglądu nieistniejącego backendu. Docelowo: przegląd rzeczywistych modułów i wyniki kontroli.
- **TODO:** 0.9, 1.1–1.9, 12.13; weryfikacja na bieżąco.

### 30. Tłumaczenia

- **Warunek akceptacji:** Interfejs PL/EN obejmuje nawigację, formularze, walidację i komunikaty błędów; wybór języka zachowuje się spójnie między stronami. Nie wymaga automatycznego tłumaczenia autorskiej treści świata.
- **Weryfikacja:** Przejść istotne przepływy w obu językach, także błędne dane i puste stany; sprawdzić brak brakujących kluczy i problemów z dłuższymi etykietami.
- **Dowód:** Istnieje [HTML z lang=pl](../public/index.html), bez mechanizmu tłumaczeń. Docelowo: zasoby językowe, kod wyboru języka i wyniki scenariuszy.
- **TODO:** 2.5, 12.5.

### 31. Testy jednostkowe

- **Warunek akceptacji:** Istnieją izolowane testy rzeczywistych reguł domenowych, obejmujące przypadki poprawne, błędne i graniczne. Są powtarzalne i nie opierają wyniku na danych użytkownika.
- **Weryfikacja:** Uruchomić udokumentowane polecenie testów i zachować wynik; sprawdzić, jakie zachowanie badają i czy odróżniają błąd od poprawnej realizacji.
- **Dowód:** Brak testów i ich konfiguracji; sam katalog tests nie jest dowodem. Docelowo: rzeczywiste testy, instrukcja uruchomienia oraz wynik z datą i rewizją. W 0.10 nie dodajemy frameworka.
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

- Zachowano nazwy, numery i punktację z wcześniejszej wersji tego pliku, pochodzącej z udostępnionej listy. [Odnośniki źródłowe w TODO](../TODO.md#źródła) wskazują m.in. [materiały prowadzącego](https://ii.pk.edu.pl/~awidlak/subject/16). Podczas tej aktualizacji nie udało się odczytać ich pełnej rubryki, więc nie dodano własnych progów punktowych ani oficjalnych minimów.
- Przed oceną #11 potwierdzić dokładny próg złożoności bazy; przed #19 wersję, poziom i zakres WCAG; przed #27 właściwą kartę Security Bingo. Robocze warunki WorldGraph są jawnie oznaczone i nie zastępują tych materiałów.
- Przed wyborem mechanizmu w #33 potwierdzić, czy „PHP Mailer” wymaga konkretnej biblioteki. Wybór i instalacja zależności nie są częścią 0.10. W [ustaleniach biblioteki grafowej 0.11](../ARCHITECTURE.md#biblioteka-grafowa--ustalenia-011) zapisano wariant A i informację użytkownika z 2026-11-13. Wybór konkretnej biblioteki pozostaje w 7.1. Zgoda na użycie biblioteki nie stanowi dowodu implementacji ani spełnienia kryterium JavaScript, Fetch lub grafów.
- Zakres produktu i aktualne role, prywatność, formaty, usuwanie oraz historia: [PROJECT.md](../PROJECT.md). Model i odpowiedzialności: [ARCHITECTURE.md](../ARCHITECTURE.md). Sposób pracy i weryfikacji: [AGENTS.md](../AGENTS.md).
