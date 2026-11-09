# PROJECT.md — WorldGraph

> Dokument zatwierdzonego celu, zakresu MVP oraz mapy stron i zakładek projektu (punkty 0.2–0.4 TODO). Ustalenia 0.4 rozszerzają wcześniejszy wariant B o etap 9; etap 10 stanowi extended MVP. Dokument opisuje plan produktu; aktualny stan implementacji przedstawia README.md.

## Problem
Twórcy rozbudowanych uniwersów przechowują informacje o postaciach, miejscach, frakcjach i fabule w wielu notatkach. Utrudnia to porządkowanie wiedzy, odnajdywanie powiązań i planowanie dalszego przebiegu historii. Współpraca wymaga również jasnego określenia, kto może przeglądać i edytować materiały.

## Cel produktu
WorldGraph ma służyć do planowania, porządkowania i notowania fabuły przez tworzenie uporządkowanej bazy wiedzy o fikcyjnym świecie oraz wizualizację powiązań między jego elementami.

Punktem odniesienia jest szkic CLFR/MYRA zapisany w `myra.txt`. Aplikacja ma rozwijać jego ideę w postaci interfejsu z wprowadzeniem do świata, kartami, kilkoma widokami grafów oraz wydarzeniami i chronologią. Kontrola spójności jest rozszerzeniem w extended MVP; w podstawowym MVP pozostaje widocznym placeholderem TBI.

## Odbiorcy
Głównym użytkownikiem jest twórca projektu/świata, który porządkuje własne uniwersum i planuje fabułę. Może pracować samodzielnie lub dopuścić współpracowników do edycji. Opublikowany świat może przeglądać każdy, również bez konta.

Narzędzie jest przeznaczone dla autorów, pisarzy, twórców RPG, scenarzystów i zespołów worldbuildingowych.

## Organizacja projektu i dostęp

- Jeden projekt reprezentuje jeden świat i przechowuje jego dane. Każda karta, relacja i wydarzenie należy do tego świata; powiązania i uczestnictwo w wydarzeniach nie mogą łączyć odizolowanych światów.
- Nowy świat jest domyślnie prywatny. Jego twórca zostaje właścicielem, z pełnym dostępem do odczytu, edycji i zarządzania projektem.
- Właściciel może dopuścić współpracowników do projektu. W MVP nadaje dostęp istniejącym użytkownikom; zaproszenia e-mail są późniejszym rozszerzeniem.
- Redaktor może przeglądać i edytować zawartość udostępnionego świata. Czytelnik ma dostęp do odczytu. Szczegółową macierz uprawnień określi punkt 0.7 TODO.
- Właściciel zarządza dostępem współpracowników oraz publikacją i cofnięciem publikacji świata.
- Publikacja obejmuje całą zawartość świata i umożliwia każdemu jej odczyt. Sam dostęp publiczny nie przyznaje praw edycji; prawa właściciela i redaktorów wynikają z ich ról.

## Zatwierdzony zakres MVP — etapy 1–9

Pierwsza użyteczna wersja to encyklopedia świata z kilkoma widokami grafów, współpracą, publikacją oraz wydarzeniami i chronologią. Obejmuje zakres funkcjonalny etapów 1–9 TODO. Kamień milowy A po etapie 8 pozostaje etapem pośrednim; ukończenie podstawowego MVP wymaga także realizacji etapu 9. Kontrola spójności z etapu 10 należy do extended MVP.

1. **Konta i dostęp:** rejestracja, logowanie, sesja, wylogowanie i ustawienia konta; role właściciela, redaktora i czytelnika w określonym świecie.
2. **Światy:** biblioteka własnych i udostępnionych projektów, tworzenie świata, opis jego założeń, edycja, usuwanie oraz zarządzanie dostępem istniejących użytkowników.
3. **Karty elementów świata:** postacie, miejsca, frakcje, rasy/gatunki i artefakty; tworzenie, przeglądanie, edycja i usuwanie, opisy, aliasy, tagi oraz ilustracje. Szczegółowe pola określi punkt 0.8 TODO.
4. **Relacje:** tworzenie, przeglądanie, edycja i usuwanie opisanych powiązań między kartami tego samego świata. Typy i zasady relacji określi punkt 0.8 TODO.
5. **Listy i grafy:** wyszukiwanie, filtrowanie i stronicowanie list; widoki relacji, powiązań lokacji, wpływu postaci (`FATE_GRAPH`) i zależności wydarzeń inspirowane grafami MYRA. Grafy pozwalają na przesuwanie, przybliżanie, filtrowanie i przechodzenie do szczegółów wybranych elementów. Na urządzeniach mobilnych i dla obsługi klawiaturą dostępne są odpowiednie listy relacji, wpływów i zależności.
6. **Publikacja i Odkrywaj:** publikowanie i cofanie publikacji całego świata, katalog publicznych światów z wyszukiwaniem, filtrowaniem i sortowaniem oraz odczyt opublikowanej zawartości przez gościa.
7. **Wydarzenia i chronologia:** tworzenie, przeglądanie, edycja i usuwanie wydarzeń, ich uczestnicy i miejsca, opis przebiegu i skutków, status opracowania oraz zależności między wydarzeniami. Lista chronologiczna uwzględnia brakujące i niepewne daty. Prosty model czasu fikcyjnego wymaga ustalenia w 9.1 i nie zakłada automatycznie kalendarza gregoriańskiego.

Karta i wydarzenie są zapisywane po jednym razie w swoich modelach. Listy, szczegóły i widoki grafów prezentują te same dane; wydarzenie nie jest kopiowane do kart na potrzeby grafu. Dopuszczalność zewnętrznej biblioteki grafowej wymaga wyjaśnienia w punkcie 0.11 TODO; wybór technologii nastąpi w punkcie 7.1.

Model pojęciowy ustalony w 0.6 — definicje, powiązania, liczności i diagram Mermaid — znajduje się w [ARCHITECTURE.md](ARCHITECTURE.md#planowany-model-pojęciowy). Właściciela świata wskazuje jedyne członkostwo `WorldMember` z rolą `owner`; uczestnicy wydarzeń wskazują istniejące karty, a miejsca i zależności wydarzeń są odrębnymi powiązaniami tych samych danych.

## Strony i nawigacja

Ustalenia punktu 0.3 obejmują siedem głównych stron i pomocniczy ekran tworzenia świata. Adresy poniżej określają plan nawigacji do wykorzystania przy implementacji routingu i poszczególnych modułów.

### Mapa stron

| Strona | Planowany adres | Cel, zawartość i główne działania | Dostęp |
|---|---|---|---|
| **Odkrywaj — strona główna** | `/` | Katalog publicznych światów: okładka, tytuł, autor, opis i tagi; wyszukiwanie, filtrowanie, sortowanie i stronicowanie. Wybranie świata otwiera jego projekt. | Każdy, również bez konta; katalog zawiera wyłącznie opublikowane światy. |
| **Logowanie** | `/login` | Formularz logowania, komunikaty walidacji i odnośnik do rejestracji. Po powodzeniu przejście do biblioteki lub wcześniej żądanej strony zgodnie z zasadami powrotu poniżej. | Osoba niezalogowana; zalogowany użytkownik przechodzi do biblioteki. |
| **Rejestracja** | `/register` | Formularz utworzenia konta, komunikaty walidacji i odnośnik do logowania. Po utworzeniu konta przejście do logowania. | Osoba niezalogowana; zalogowany użytkownik przechodzi do biblioteki. |
| **Biblioteka** | `/library` | Własne i udostępnione światy z informacją o roli użytkownika. Otwieranie projektu i przycisk „Utwórz świat”. Pusta biblioteka zachęca do utworzenia pierwszego świata. | Zalogowany użytkownik; widzi tylko światy własne i te, do których otrzymał dostęp. |
| **Otwarty projekt** | `/worlds/{id}` | Domyślnie otwiera „O świecie”, czyli wstęp/README świata. Wspólny nagłówek i zakładki prowadzą do grafów, kart, wydarzeń oraz placeholdera Spójność. Działania edycyjne zależą od roli; właściciel może przejść do ustawień projektu. | Publiczny świat: każdy. Prywatny świat: właściciel i użytkownicy z nadanym dostępem. Edycja: właściciel i redaktorzy. |
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

Mapa określa strony, ich cele, podstawowy dostęp, działania i przejścia. Zakładki wewnątrz projektu opisuje poniższa sekcja ustaleń 0.4. Pełna macierz uprawnień pozostaje do ustalenia w 0.7, a szczegółowe pola kart i zasady relacji w 0.8. Widoki szczegółów i edycji kart zostaną rozwinięte wraz z tymi ustaleniami i etapem 5. Zgodnie ze zmianą zakresu w 0.4 wydarzenia należą do podstawowego MVP, a właściwa kontrola spójności do extended MVP.

Ukończenie 0.3 oznacza zapisanie i sprawdzenie tej mapy. Implementacja stron, formularzy i routingu jest realizowana w odpowiednich etapach TODO.

## Zakładki projektu — ustalenia 0.4

### Zawartość i adresy

Zakładki występują w kolejności: **O świecie → Graf → Karty → Wydarzenia → Spójność**. Każda ma osobny adres, dostępny również bezpośrednio. Otwarcie świata pod `/worlds/{id}` zawsze prowadzi do „O świecie”.

| Zakładka | Planowany adres | Zawartość i przeznaczenie | Zakres |
|---|---|---|---|
| **O świecie** | `/worlds/{id}` | Wstęp/README projektu świata: jego opis, kierunek fabuły, cele, tematy i założenia inspirowane `MAIN_LORE`. Stanowi punkt orientacyjny dla autora, współpracownika i publicznego czytelnika. | Podstawowe MVP. |
| **Graf** | `/worlds/{id}/graph` | Przełączane widoki grafów inspirowane `myra.txt`, opisane poniżej. Wybór elementu prowadzi do odpowiadającej mu karty, wydarzenia lub szczegółów powiązania. | Widoki relacji, lokacji i wpływu postaci w etapie 7 oraz wydarzeń w etapie 9; wszystkie należą do podstawowego MVP. |
| **Karty** | `/worlds/{id}/entities` | Encyklopedia elementów z `MYRA_LIST`, rozszerzona o artefakty zgodnie z zakresem 0.2. Lista, wyszukiwanie, filtry, szczegóły, opisy, aliasy i relacje; tworzenie oraz edycja zgodnie z rolą. | Podstawowe MVP. |
| **Wydarzenia** | `/worlds/{id}/events` | Wydarzenia i praca nad bieżąco opracowywanymi wydarzeniami, inspirowane `EVENTS_LIST` i `CURRENT_EVENT`. Lista i szczegóły, uczestnicy, miejsca, przebieg, skutki, status opracowania, chronologia oraz zależności między wydarzeniami. | Podstawowe MVP, etap 9. |
| **Spójność** | `/worlds/{id}/consistency` | W podstawowym MVP widoczny placeholder prowadzący do strony TBI. W extended MVP: analiza już utworzonych grafów i list świata, wskazująca nieścisłości oraz potencjalne naciągnięcia fabularne, w tym deus ex machina, z wyjaśnieniami i możliwością uzasadnienia przez autora. | Placeholder w podstawowym MVP; funkcjonalność w extended MVP, etap 10. |

Wstęp/README oznacza rolę treści „O świecie”; sposób jej formatowania zostanie dobrany podczas implementacji. `CURRENT_EVENT` oznacza pracę nad istniejącym wydarzeniem i jego statusem opracowania, bez tworzenia kopii wydarzenia lub osobnego systemu historii wersji. Szczegółowe pola pozostają do ustalenia przy modelu danych i module wydarzeń.

### Widoki zakładki Graf

| Widok | Inspiracja z `myra.txt` | Zakres |
|---|---|---|
| **Relacje** | `RELETIONSHIPS_GRAPH` | Domyślny widok kart świata i ich opisanych relacji, z filtrowaniem i przejściem do szczegółów. Podstawowe MVP. |
| **Lokacje** | `LOCATIONES_GRAPH` | Widok połączeń między kartami miejsc, wyodrębniony z danych świata. Podstawowe MVP obejmuje graf powiązań lokacji; mapa przestrzenna, teren, odległości i koszty podróży pozostają odłożone. |
| **Wydarzenia** | `EVENTE_GRAPH` | Wydarzenia jako węzły oraz ich zależności, np. „przed”, „doprowadziło do” i „jest częścią”. Podstawowe MVP, etap 9; dane są wspólne z zakładką Wydarzenia. |
| **Los — wpływ postaci** | `FATE_GRAPH` | Graf przedstawiający wpływ poszczególnych postaci na inne postacie i ich działania w fabule. Węzły reprezentują postacie, a skierowane powiązania pokazują, kto wpływa na kogo i w jaki sposób. Jest narzędziem opisu wpływu, niezależnym od kontroli spójności. Należy do podstawowego MVP i jest realizowany w etapie 7, w ramach etapów 1–9. Sposób zapisu wpływu i ewentualne miary lub wagi wymagają ustalenia w 0.8. |

Widok lokacji jest sposobem przeglądania istniejących kart i relacji. Graf wydarzeń korzysta z modelu wydarzeń i ich zależności. Przełączanie widoku zmienia prezentację danych, bez kopiowania kart ani wydarzeń. Dokładne parametry adresu wybranego widoku grafu zostaną ustalone przy jego implementacji.

### Układ inspirowany GitHubem

- Nad treścią znajduje się wspólny nagłówek projektu: właściciel/nazwa świata, oznaczenie publiczny/prywatny oraz rola zalogowanego użytkownika, jeśli ma nadany dostęp.
- Pod nagłówkiem biegnie poziomy pasek pięciu zakładek. Aktywna zakładka jest wyraźnie oznaczona, a wspólny nagłówek i nawigacja pozostają dostępne po przejściu do innej zakładki.
- Treść wybranej zakładki znajduje się pod paskiem. W zakładce Graf osobny przełącznik wybiera widok grafu.
- Na małych ekranach pasek zakładek można przewijać poziomo; aktywna pozycja pozostaje widoczna i dostępna z klawiatury. Odnośniki zachowują czytelne nazwy i widoczny fokus.
- Zakładki są odnośnikami do osobnych adresów, dzięki czemu można odświeżyć widok, zapisać link i korzystać z historii przeglądarki.
- Ustawienia projektu pozostają osobną stroną `/worlds/{id}/settings`, dostępną dla właściciela z nagłówka projektu. Inspiracja GitHubem dotyczy organizacji nagłówka, zakładek i treści.

### TBI, dostęp i puste stany

Wszystkie pięć zakładek jest widocznych. Zakładki spoza podstawowego MVP oraz funkcje jeszcze niewdrożone są klikalne i pod swoim adresem wyświetlają stronę **TBI — funkcja zaplanowana do wdrożenia**. Strona zachowuje kontekst świata, wskazuje wybraną zakładkę i oferuje powrót do „O świecie”. W ukończonym podstawowym MVP dotyczy to Spójności. Wydarzenia i wszystkie cztery widoki grafów, w tym Los (`FATE_GRAPH`), muszą już działać; podczas ich implementacji TBI może być wyłącznie stanem przejściowym. Placeholder nie przedstawia wyników analizy ani pozornie działających narzędzi.

Przed wyświetleniem zakładki lub strony TBI serwer sprawdza dostęp do danego świata, również przy wejściu bezpośrednim adresem. Publiczny i prywatny świat zachowują wspólny układ. Właściciel i redaktorzy korzystają z dopuszczonych działań edycyjnych, a czytelnicy i goście z odczytu. Edycja podstawowych informacji o świecie pozostaje w ustawieniach projektu dostępnych właścicielowi zgodnie z 0.3; pełna macierz uprawnień należy do 0.7.

Puste stany dostępnych funkcji są odróżnione od TBI:

- „O świecie” bez opisu pokazuje informację o braku wprowadzenia; właściciel może przejść do jego uzupełnienia w ustawieniach.
- „Karty” bez elementów pokazują pustą listę i możliwość dodania karty osobie z prawem edycji.
- Graf bez węzłów lub połączeń opisuje brak danych dla wybranego widoku; pojedynczy element można pokazać również bez relacji.
- „Wydarzenia” bez wpisów pokazują pustą listę i możliwość dodania wydarzenia osobie z prawem edycji. Po wdrożeniu extended MVP i wykonaniu analizy Spójność może pokazać brak wykrytych ostrzeżeń; ten stan jest odróżniony od TBI.

Ukończenie 0.4 oznacza ustalenie zawartości, adresów, nawigacji i zachowania zakładek oraz aktualizację granic MVP. Implementacja interfejsu, grafów, wydarzeń i analizy spójności pozostaje w odpowiednich etapach TODO.

## Odniesienie do szkicu MYRA

| Element szkicu `myra.txt` | Zakres WorldGraph |
|---|---|
| `MAIN_LORE` | Wstęp/README w zakładce „O świecie”: opis i założenia świata, kierunek fabuły, jej cele i tematy. |
| `MYRA_LIST` | Karty elementów świata z opisami i aliasami; szczegółowe pola będą ustalone w 0.8. |
| `RELETIONSHIPS_GRAPH` | Relacje między kartami i ich wizualizacja w MVP, bez uniwersalnej skali wag 0–10. |
| `EVENTS_LIST`, `CURRENT_EVENT`, `EVENTE_GRAPH` | Wydarzenia, praca nad bieżącymi wpisami, chronologia i graf zależności w podstawowym MVP, etap 9. |
| `LOCATIONES_GRAPH` | Inspiracja widoku połączeń lokacji w podstawowym MVP; funkcje mapy przestrzennej pozostają odłożone. |
| `FATE_GRAPH` | Graf wpływu postaci na inne postacie i ich działania, realizowany w etapie 7 i wymagany w podstawowym MVP etapów 1–9. Szczegóły zapisu wpływu i ewentualnych miar należy ustalić w 0.8. |
| Dialogi i historia zmian | Funkcje odłożone poza pierwszą wersję. |

Szkic stanowi odniesienie do celu produktu. Podstawowy model pojęciowy zapisano w 0.6 w ARCHITECTURE.md; macierz uprawnień oraz szczegółowe pola, typy relacji i zasady usuwania pozostają do ustalenia w 0.7–0.8, w granicach zatwierdzonego zakresu.

## Warunek ukończenia MVP

Użytkownik może przejść cały proces:

**konto → prywatny świat → karty → relacje i wydarzenia → widoki grafów i chronologia → publikacja → odczyt przez gościa.**

Weryfikacja obejmuje również współpracę: właściciel nadaje istniejącemu użytkownikowi rolę redaktora, redaktor edytuje zawartość, a wprowadzone zmiany są widoczne w kartach i grafie. Czytelnik i gość pozostają przy odczycie. Cofnięcie publikacji przywraca prywatność świata, a użytkownik bez dostępu nie może odczytać ani zmienić jego danych przez interfejs, adres pliku lub bezpośrednie żądanie.

W części wydarzeń użytkownik może utworzyć i edytować wydarzenie, powiązać je z uczestnikami i miejscem, określić status opracowania oraz zależności, a następnie przeglądać te same dane w liście, chronologii i grafie wydarzeń. Publiczny odczyt obejmuje także wydarzenia. Sprawdzenie modelu czasu uwzględnia brakujące i niepewne daty oraz izolację światów.

Weryfikacja nawigacji obejmuje domyślne „O świecie”, osobne adresy zakładek, przełączanie widoków grafów oraz widoczną Spójność prowadzącą do TBI. Sprawdzenie `FATE_GRAPH` obejmuje utworzenie i edycję opisu kierunkowego wpływu jednej postaci na inną i jej działania oraz odczyt tego wpływu w grafie i odpowiedniej liście. Wszystkie wymagane funkcje etapów 1–9 muszą działać; TBI dla Wydarzeń lub któregokolwiek z czterech widoków grafu oznacza, że MVP nie jest jeszcze ukończone.

Zakres MVP uznaje się za wykonany po zaimplementowaniu i sprawdzeniu wymienionych funkcji oraz kontroli dostępu. Ustalenia dokumentacyjne 0.2–0.4 opisują zakres i nawigację, a 0.6 model pojęciowy; nie oznaczają implementacji aplikacji.

## Extended MVP — etap 10

Extended MVP rozszerza podstawowe MVP o kontrolę spójności działającą na już utworzonych grafach i listach świata. Analiza wykorzystuje zapisane karty, relacje, wydarzenia, chronologię i powiązania między nimi, aby wskazywać nieścisłości oraz potencjalne naciągnięcia fabularne lub deus ex machina. Przykładowym sygnałem może być rozstrzygnięcie wydarzenia, dla którego w danych nie zapisano wcześniejszej przyczyny lub przygotowania.

Zakładka Spójność pod istniejącym adresem zastępuje TBI listą ostrzeżeń. Każde ostrzeżenie wskazuje analizowane wpisy i powiązania, wyjaśnia przyczynę zgłoszenia oraz pozwala autorowi uzupełnić dane lub uzasadnić zamierzony zabieg fabularny. Ocena jest ponawiana po zmianie danych. Brak zapisanych informacji i niepewność są uwzględniane w wyjaśnieniu; sygnał potencjalnego naciągnięcia wymaga oceny autora.

Pierwszy zakres analizy obejmie 2–3 jawne, testowalne reguły, doprecyzowane przed implementacją etapu 10. Reguły dotyczą danych strukturalnych i zależności zapisanych przez autora; automatyczna interpretacja literacka dowolnego tekstu pozostaje poza tym zakresem. Walidator zgłasza ostrzeżenia i nie naprawia historii samodzielnie. `FATE_GRAPH` przedstawia wpływ postaci; kontrola spójności jest osobnym modułem analizującym dostępne dane świata.

## Dalsze etapy projektu

- **Zaproszenia e-mail, poczta i CRON — etap 11:** rozszerzenie współpracy o zaproszenia z terminem ważności i ich obsługę.
- **Finalizacja i wdrożenie — etap 12:** końcowa weryfikacja, dokumentacja, eksport bazy i publikacja aplikacji.

## Funkcje odłożone poza pierwszą wersję

- Osobne moduły scen i dialogów MYRA.
- Uniwersalne wagi relacji 0–10.
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

