# PROJECT.md — WorldGraph

> Dokument zatwierdzonego celu i zakresu MVP w wariancie B (punkt 0.2 TODO) oraz mapy stron (punkt 0.3 TODO). Opisuje plan produktu; aktualny stan implementacji przedstawia README.md.

## Problem
Twórcy rozbudowanych uniwersów przechowują informacje o postaciach, miejscach, frakcjach i fabule w wielu notatkach. Utrudnia to porządkowanie wiedzy, odnajdywanie powiązań i planowanie dalszego przebiegu historii. Współpraca wymaga również jasnego określenia, kto może przeglądać i edytować materiały.

## Cel produktu
WorldGraph ma służyć do planowania, porządkowania i notowania fabuły przez tworzenie uporządkowanej bazy wiedzy o fikcyjnym świecie oraz wizualizację powiązań między jego elementami.

Punktem odniesienia jest szkic CLFR/MYRA zapisany w `myra.txt`. Aplikacja ma rozwijać jego ideę w postaci interfejsu z kartami, opisami i grafem. Pierwsza wersja skupia się na elementach świata i ich relacjach; wydarzenia, chronologia oraz kontrola spójności są kolejnymi etapami realizacji tego celu.

## Odbiorcy
Głównym użytkownikiem jest twórca projektu/świata, który porządkuje własne uniwersum i planuje fabułę. Może pracować samodzielnie lub dopuścić współpracowników do edycji. Opublikowany świat może przeglądać każdy, również bez konta.

Narzędzie jest przeznaczone dla autorów, pisarzy, twórców RPG, scenarzystów i zespołów worldbuildingowych.

## Organizacja projektu i dostęp

- Jeden projekt reprezentuje jeden świat i przechowuje jego dane. Każda karta i relacja należy do tego świata; powiązania nie mogą łączyć odizolowanych światów.
- Nowy świat jest domyślnie prywatny. Jego twórca zostaje właścicielem, z pełnym dostępem do odczytu, edycji i zarządzania projektem.
- Właściciel może dopuścić współpracowników do projektu. W MVP nadaje dostęp istniejącym użytkownikom; zaproszenia e-mail są późniejszym rozszerzeniem.
- Redaktor może przeglądać i edytować zawartość udostępnionego świata. Czytelnik ma dostęp do odczytu. Szczegółową macierz uprawnień określi punkt 0.7 TODO.
- Właściciel zarządza dostępem współpracowników oraz publikacją i cofnięciem publikacji świata.
- Publikacja obejmuje całą zawartość świata i umożliwia każdemu jej odczyt. Sam dostęp publiczny nie przyznaje praw edycji; prawa właściciela i redaktorów wynikają z ich ról.

## Zatwierdzony zakres MVP — wariant B

Pierwsza użyteczna wersja to encyklopedia świata z interaktywnym grafem, współpracą i publikacją. Odpowiada zakresowi funkcjonalnemu etapów 1–8 TODO oraz opisanemu tam kamieniowi milowemu A.

1. **Konta i dostęp:** rejestracja, logowanie, sesja, wylogowanie i ustawienia konta; role właściciela, redaktora i czytelnika w określonym świecie.
2. **Światy:** biblioteka własnych i udostępnionych projektów, tworzenie świata, opis jego założeń, edycja, usuwanie oraz zarządzanie dostępem istniejących użytkowników.
3. **Karty elementów świata:** postacie, miejsca, frakcje, rasy/gatunki i artefakty; tworzenie, przeglądanie, edycja i usuwanie, opisy, aliasy, tagi oraz ilustracje. Szczegółowe pola określi punkt 0.8 TODO.
4. **Relacje:** tworzenie, przeglądanie, edycja i usuwanie opisanych powiązań między kartami tego samego świata. Typy i zasady relacji określi punkt 0.8 TODO.
5. **Lista i graf:** wyszukiwanie, filtrowanie i stronicowanie list; interaktywny graf z przesuwaniem, przybliżaniem, filtrowaniem i przejściem do szczegółów kart oraz relacji. Na urządzeniach mobilnych i dla obsługi klawiaturą dostępna jest alternatywna lista relacji.
6. **Publikacja i Odkrywaj:** publikowanie i cofanie publikacji całego świata, katalog publicznych światów z wyszukiwaniem, filtrowaniem i sortowaniem oraz odczyt opublikowanej zawartości przez gościa.

Karta jest zapisana raz. Encyklopedia, listy i graf prezentują te same dane, a zmiany są widoczne w odpowiednich widokach. Dopuszczalność zewnętrznej biblioteki grafowej wymaga wyjaśnienia w punkcie 0.11 TODO; wybór technologii nastąpi w punkcie 7.1.

## Strony i nawigacja

Ustalenia punktu 0.3 obejmują siedem głównych stron i pomocniczy ekran tworzenia świata. Adresy poniżej określają plan nawigacji do wykorzystania przy implementacji routingu i poszczególnych modułów.

### Mapa stron

| Strona | Planowany adres | Cel, zawartość i główne działania | Dostęp |
|---|---|---|---|
| **Odkrywaj — strona główna** | `/` | Katalog publicznych światów: okładka, tytuł, autor, opis i tagi; wyszukiwanie, filtrowanie, sortowanie i stronicowanie. Wybranie świata otwiera jego projekt. | Każdy, również bez konta; katalog zawiera wyłącznie opublikowane światy. |
| **Logowanie** | `/login` | Formularz logowania, komunikaty walidacji i odnośnik do rejestracji. Po powodzeniu przejście do biblioteki lub wcześniej żądanej strony zgodnie z zasadami powrotu poniżej. | Osoba niezalogowana; zalogowany użytkownik przechodzi do biblioteki. |
| **Rejestracja** | `/register` | Formularz utworzenia konta, komunikaty walidacji i odnośnik do logowania. Po utworzeniu konta przejście do logowania. | Osoba niezalogowana; zalogowany użytkownik przechodzi do biblioteki. |
| **Biblioteka** | `/library` | Własne i udostępnione światy z informacją o roli użytkownika. Otwieranie projektu i przycisk „Utwórz świat”. Pusta biblioteka zachęca do utworzenia pierwszego świata. | Zalogowany użytkownik; widzi tylko światy własne i te, do których otrzymał dostęp. |
| **Otwarty projekt** | `/worlds/{id}` | Przestrzeń wybranego świata: opis, karty i graf tych samych danych. Przeglądanie zawartości oraz działania edycyjne zgodne z rolą; właściciel może przejść do ustawień projektu. | Publiczny świat: każdy. Prywatny świat: właściciel i użytkownicy z nadanym dostępem. Edycja: właściciel i redaktorzy. |
| **Ustawienia projektu** | `/worlds/{id}/settings` | Osobna strona edycji podstawowych informacji o świecie, zarządzania dostępem współpracowników, publikacji i cofania publikacji oraz usuwania świata z potwierdzeniem. Powrót do otwartego projektu. | Właściciel danego świata. |
| **Ustawienia konta** | `/account/settings` | Osobna strona zmiany nazwy wyświetlanej i hasła po weryfikacji obecnego hasła; komunikaty walidacji i wyniku zapisu. Powrót do biblioteki. | Zalogowany użytkownik, wyłącznie dla własnego konta. |
| **Tworzenie świata — ekran pomocniczy** | `/worlds/new` | Osobny formularz otwierany z biblioteki. Po zapisaniu powstaje prywatny świat należący do twórcy i otwiera się jego projekt; anulowanie prowadzi do biblioteki. | Zalogowany użytkownik. |

`{id}` oznacza identyfikator wybranego świata. Ścieżka `/worlds/new` oznacza formularz tworzenia i musi być rozróżniana przez router od adresu istniejącego świata.

### Wspólna nawigacja i widok projektu

- Logo WorldGraph prowadzi do Odkrywaj. W MVP Odkrywaj pełni rolę strony głównej; obecny szablon demonstracyjny jest punktem wyjścia do późniejszej implementacji.
- Osoba niezalogowana ma w głównej nawigacji odnośniki „Odkrywaj”, „Zaloguj się” i „Zarejestruj się”.
- Zalogowany użytkownik ma odnośniki „Odkrywaj”, „Biblioteka” i „Ustawienia konta” oraz akcję „Wyloguj”. Wylogowanie kończy sesję i prowadzi do Odkrywaj; nie wymaga osobnej strony.
- Publiczny i prywatny świat korzystają ze wspólnego widoku projektu pod tym samym adresem. Zakres dostępu i dostępność działań edycyjnych wynikają z roli użytkownika w tym świecie.
- Ustawienia projektu są osobną stroną dostępną z projektu dla właściciela. Redaktorzy edytują zawartość w odpowiednich widokach projektu; czytelnicy i goście przeglądają ją bez działań edycyjnych.
- Biblioteka i Odkrywaj prowadzą do tego samego projektu. Z projektu można przejść do Odkrywaj, a po zalogowaniu również do biblioteki.

### Podstawowe przejścia

1. **Pierwsze użycie:** Odkrywaj → rejestracja → logowanie → biblioteka → tworzenie świata → prywatny projekt.
2. **Powrót do pracy:** logowanie → biblioteka → własny lub udostępniony projekt → przeglądanie albo edycja zgodnie z rolą.
3. **Publikacja:** projekt → ustawienia projektu → publikacja → świat dostępny w Odkrywaj i pod bezpośrednim adresem projektu. Cofnięcie publikacji przywraca dostęp wyłącznie uprawnionym osobom.
4. **Przeglądanie przez gościa:** Odkrywaj lub bezpośredni adres publicznego świata → projekt → jego zawartość w trybie odczytu.
5. **Współpraca:** właściciel otwiera ustawienia projektu i nadaje istniejącemu użytkownikowi dostęp; świat pojawia się w bibliotece tego użytkownika z przypisaną rolą. Zaproszenia e-mail pozostają rozszerzeniem po MVP.

Po zwykłym logowaniu użytkownik trafia do biblioteki. Jeśli logowanie zostało wywołane próbą otwarcia strony wymagającej konta, po powodzeniu wraca do tej strony, o ile adres jest bezpieczną ścieżką wewnątrz aplikacji i użytkownik ma wymagane uprawnienia. Cel powrotu należy zachować również przy przejściu przez rejestrację; przy niepoprawnym lub niedostępnym celu powrót prowadzi do biblioteki.

Próba wejścia na stronę wymagającą konta przez osobę niezalogowaną prowadzi do logowania bez ujawniania chronionej zawartości. Brak uprawnień po zalogowaniu uniemożliwia odczyt i edycję danych. Serwer sprawdza dostęp także przy bezpośrednich żądaniach, niezależnie od widoczności odnośników.

Gdy nie ma publicznych światów, Odkrywaj pokazuje komunikat o pustym katalogu. Brak dostępu i nieistniejące adresy wymagają kontrolowanej odpowiedzi; obsługa błędów i statusów HTTP należy do implementacji szkieletu aplikacji w etapie 1.

### Granice ustaleń 0.3

Mapa określa strony, ich cele, podstawowy dostęp, działania i przejścia. Zakładki wewnątrz projektu zostaną doprecyzowane w 0.4, pełna macierz uprawnień w 0.7, a szczegółowe pola kart i zasady relacji w 0.8. Widoki szczegółów i edycji kart zostaną rozwinięte wraz z tymi ustaleniami i etapem 5. Wydarzenia i spójność pozostają poza MVP zgodnie z zakresem 0.2.

Ukończenie 0.3 oznacza zapisanie i sprawdzenie tej mapy. Implementacja stron, formularzy i routingu jest realizowana w odpowiednich etapach TODO.

## Odniesienie do szkicu MYRA

| Element szkicu `myra.txt` | Zakres WorldGraph |
|---|---|
| `MAIN_LORE` | Opis i założenia świata, służące porządkowaniu kierunku fabuły, jej celów i tematów. |
| `MYRA_LIST` | Karty elementów świata z opisami i aliasami; szczegółowe pola będą ustalone w 0.8. |
| `RELETIONSHIPS_GRAPH` | Relacje między kartami i ich wizualizacja w MVP, bez uniwersalnej skali wag 0–10. |
| `EVENTS_LIST`, `CURRENT_EVENT`, `EVENTE_GRAPH` | Podstawa późniejszego modułu wydarzeń i chronologii. |
| Dialogi, historia zmian, graf losu i mapa lokacji | Funkcje odłożone poza pierwszą wersję. |

Szkic stanowi odniesienie do celu produktu. Szczegóły modelu danych i pól będą ustalane w punktach 0.6–0.8, w granicach zatwierdzonego zakresu.

## Warunek ukończenia MVP

Użytkownik może przejść cały proces:

**konto → prywatny świat → karty → relacje → graf → publikacja → odczyt przez gościa.**

Weryfikacja obejmuje również współpracę: właściciel nadaje istniejącemu użytkownikowi rolę redaktora, redaktor edytuje zawartość, a wprowadzone zmiany są widoczne w kartach i grafie. Czytelnik i gość pozostają przy odczycie. Cofnięcie publikacji przywraca prywatność świata, a użytkownik bez dostępu nie może odczytać ani zmienić jego danych przez interfejs, adres pliku lub bezpośrednie żądanie.

Zakres MVP uznaje się za wykonany po zaimplementowaniu i sprawdzeniu wymienionych funkcji oraz kontroli dostępu. Zatwierdzenie tego dokumentu oznacza ukończenie ustalenia zakresu w 0.2, nie implementację aplikacji.

## Funkcje po MVP, nadal w planie projektu

- **Wydarzenia i chronologia — etap 9:** uczestnicy, miejsca, opis przebiegu i skutków, zależności między wydarzeniami oraz proste porządkowanie czasu. Model czasu fikcyjnego wymaga osobnego ustalenia i nie zakłada automatycznie kalendarza gregoriańskiego.
- **Kontrola spójności — etap 10:** 2–3 reguły wykrywania potencjalnych konfliktów oraz wyjaśnienia ostrzeżeń i uzasadnienia wyjątków. Walidator zgłasza ostrzeżenia na podstawie danych strukturalnych; nie naprawia historii samodzielnie.
- **Zaproszenia e-mail, poczta i CRON — etap 11:** rozszerzenie współpracy o zaproszenia z terminem ważności i ich obsługę.
- **Finalizacja i wdrożenie — etap 12:** końcowa weryfikacja, dokumentacja, eksport bazy i publikacja aplikacji.

## Funkcje odłożone poza pierwszą wersję

- Osobne moduły scen i dialogów MYRA.
- Graf losu, liczbowe miary wpływu i uniwersalne wagi relacji 0–10.
- Mapa z terenem, odległościami, kosztami podróży i symulacjami fizycznymi.
- Zaawansowane kalendarze fikcyjne i rozbudowana graficzna oś czasu.
- Pełna historia wersji i przywracanie danych.
- Prywatność pojedynczych kart i ukrywanie spoilerów w opublikowanym świecie.
- Współpraca w czasie rzeczywistym.
- AI i automatyczne generowanie fikcji.
- Tworzenie relacji przeciąganiem i zapis własnych układów grafu.

## Realizacja na WdPAI

Projekt powstaje na przedmiot WdPAI. MVP jest pierwszym kamieniem milowym; funkcje przesunięte na później pozostają w planie końcowej wersji opisanej w TODO. Kryteria z `docs/REQUIREMENTS.md` wymagają osobnej weryfikacji i dowodów realizacji.

Zakładany stos to obiektowy PHP, PostgreSQL, HTML/CSS i JavaScript z Fetch API, uruchamiane lokalnie przez Docker Compose. Kryterium 14 oznacza JavaScript. Bezpieczeństwo, testy, responsywność, dostępność, PL/EN i motywy light/dark są rozwijane wraz z odpowiednimi etapami implementacji. Plan architektury znajduje się w `ARCHITECTURE.md`.

