# PROJECT.md — WorldGraph

> Dokument zatwierdzonego celu, zakresu MVP, nawigacji, uprawnień i struktury danych (punkty 0.2–0.4 oraz 0.6–0.8 TODO). Ustalenia 0.7 rozszerzają MVP o role Twórcy i Custom, trzy poziomy prywatności, komentarze oraz historię zmian danych i plików świata. W 0.8 zatwierdzono format kart oparty na MYRA, reguły relacji i wpływu postaci, sposób zapisu, usuwania i zachowania wersji oraz rozstrzygania równoległych edycji. Podstawowe MVP nadal obejmuje etapy 1–9; etap 10 stanowi extended MVP. Dokument opisuje plan produktu; aktualny stan implementacji przedstawia README.md.

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
- Nowy świat jest domyślnie prywatny. Osoba tworząca świat zostaje jego jedynym Twórcą (`creator`). Świat może mieć ponadto wielu Właścicieli (`owner`) albo nie mieć żadnego.
- Twórca i Właściciele zarządzają projektem i członkami. Wyłącznie Twórca może nadać rolę Właściciela, usunąć cały świat oraz przekazać rolę Twórcy istniejącemu Właścicielowi.
- Redaktor może tworzyć, edytować i usuwać zawartość świata, również dodaną przez innych. Czytelnik przegląda bieżącą zawartość i komentuje. Custom oznacza przydzielony zestaw uprawnień do obszarów i operacji.
- W MVP dostęp nadaje się istniejącym użytkownikom; zaproszenia e-mail są późniejszym rozszerzeniem. Gość to osoba bez członkostwa w tym świecie, także zalogowana.
- Prywatność ma trzy wartości: publiczny, ograniczony i prywatny. Określa minimalny dostęp Gościa; role i Custom mogą go rozszerzać. Szczegóły określa poniższa macierz 0.7.
- Każda skuteczna zmiana danych lub plików zarządzanych przez aplikację podlega wspólnej historii zmian świata, również gdy dotyczy cudzej zawartości, komentarzy, prywatności albo uprawnień.

## Zatwierdzony zakres MVP — etapy 1–9

Pierwsza użyteczna wersja to encyklopedia świata z kilkoma widokami grafów, współpracą, publikacją oraz wydarzeniami i chronologią. Obejmuje zakres funkcjonalny etapów 1–9 TODO. Kamień milowy A po etapie 8 pozostaje etapem pośrednim; ukończenie podstawowego MVP wymaga także realizacji etapu 9. Kontrola spójności z etapu 10 należy do extended MVP.

1. **Konta i dostęp:** rejestracja, logowanie, sesja, wylogowanie i ustawienia konta; role Twórcy, Właściciela, Redaktora, Czytelnika i Custom w określonym świecie oraz dostęp Gościa wynikający z prywatności.
2. **Światy:** biblioteka własnych i udostępnionych projektów, tworzenie świata, opis jego założeń, edycja, usuwanie wyłącznie przez Twórcę, zarządzanie dostępem istniejących użytkowników i przekazywanie roli Twórcy.
3. **Karty elementów świata:** postacie, miejsca, frakcje, rasy/gatunki i artefakty; tworzenie, przeglądanie, edycja i usuwanie, opisy, aliasy, cele, tagi oraz ilustracje. Wspólny format określa zatwierdzona sekcja 0.8.
4. **Relacje:** tworzenie, przeglądanie, edycja i usuwanie opisanych powiązań między kartami tego samego świata. Rodzaje i zasady relacji określa zatwierdzona sekcja 0.8.
5. **Listy i grafy:** wyszukiwanie, filtrowanie i stronicowanie list; widoki relacji, powiązań lokacji, wpływu postaci (`FATE_GRAPH`) i zależności wydarzeń inspirowane grafami MYRA. Grafy pozwalają na przesuwanie, przybliżanie, filtrowanie i przechodzenie do szczegółów wybranych elementów. Na urządzeniach mobilnych i dla obsługi klawiaturą dostępne są odpowiednie listy relacji, wpływów i zależności.
6. **Prywatność i Odkrywaj:** stany publiczny, ograniczony i prywatny; katalog publicznych światów z wyszukiwaniem, filtrowaniem i sortowaniem; pełny publiczny odczyt albo sam wstęp ograniczonego świata zgodnie z macierzą 0.7.
7. **Wydarzenia i chronologia:** tworzenie, przeglądanie, edycja i usuwanie wydarzeń, ich uczestnicy i miejsca, opis przebiegu i skutków, status opracowania oraz zależności między wydarzeniami. Lista chronologiczna uwzględnia brakujące i niepewne daty. Prosty model czasu fikcyjnego wymaga ustalenia w 9.1 i nie zakłada automatycznie kalendarza gregoriańskiego.
8. **Komentarze:** dodawanie po zalogowaniu, odczyt oraz edycja i usuwanie własnych komentarzy; moderacja zgodnie z uprawnieniami. Komentarz nie daje prawa zmiany treści świata i nie omija dostępu do komentowanego kontekstu.
9. **Historia i wersje:** kompletna historia skutecznych zmian danych i plików świata, grupowana w zestawy podobne do commitów, z odczytem wcześniejszych wersji i porównaniem zmian przez uprawnione osoby. Podstawa zapisu historii powstaje w etapie 4, a kolejne moduły korzystają z niej od pierwszej operacji zmieniającej dane.

Karta i wydarzenie są zapisywane po jednym razie w swoich modelach. Listy, szczegóły i widoki grafów prezentują te same dane; wydarzenie nie jest kopiowane do kart na potrzeby grafu. W 0.11 przyjęto wariant A: zewnętrzną bibliotekę JS wyłącznie do wizualizacji, układu i interakcji grafu, z własnym modelem, backendem i integracją Fetch. Dopuszczalność potwierdził prowadzący według informacji użytkownika z 2026-11-13; wybór konkretnej biblioteki pozostaje w 7.1. Zakres, źródło i warunki opisuje [ARCHITECTURE.md — Biblioteka grafowa](ARCHITECTURE.md#biblioteka-grafowa--ustalenia-011).

Model pojęciowy z 0.6, zaktualizowany zgodnie z 0.7, znajduje się w [ARCHITECTURE.md](ARCHITECTURE.md#planowany-model-pojęciowy). Bieżącego Twórcę wskazuje jedyne członkostwo `WorldMember` z rolą `creator`; role Właścicieli nie zastępują tego przypisania. Uczestnicy wydarzeń wskazują istniejące karty, a miejsca i zależności wydarzeń są odrębnymi powiązaniami tych samych danych.

## Role, prywatność i uprawnienia — ustalenia 0.7

### Role w świecie

| Rola | Zakres |
|---|---|
| Gość | Brak przydzielonego członkostwa w danym świecie. Dostęp wynika z jego prywatności, niezależnie od tego, czy osoba ma konto. |
| Czytelnik — `reader` | Odczyt bieżącej zawartości świata, dodawanie komentarzy oraz edycja i usuwanie własnych komentarzy. |
| Redaktor — `editor` | Prawa Czytelnika oraz tworzenie, edycja i usuwanie treści i ilustracji, również cudzych. Może edytować treść „O świecie”; ustawienia administracyjne są odrębne. |
| Właściciel — `owner` | Prawa Redaktora, zarządzanie ustawieniami, prywatnością i członkami oraz moderacja komentarzy. Nie może zmienić ani wykluczyć Twórcy, nadać roli Właściciela, przekazać roli Twórcy ani usunąć całego świata. |
| Twórca — `creator` | Prawa Właściciela oraz wyłączne prawo nadawania roli Właściciela, usunięcia świata i przekazania roli Twórcy. W każdym istniejącym świecie jest dokładnie jeden Twórca. |
| Custom — `custom` | Indywidualny zestaw dozwolonych operacji w danym świecie, bez automatycznego dziedziczenia praw Redaktora lub administratora. Dostęp podstawowy wynikający z prywatności nadal obowiązuje. |

Role dotyczą świata, nie całego konta. Pełny odczyt projektu oznacza bieżącą treść; nie udostępnia automatycznie wewnętrznych ustawień, listy członków ani dawnych wersji.

Przekazanie roli Twórcy jest jedną operacją: wskazany istniejący Właściciel zostaje Twórcą, a dotychczasowy Twórca Właścicielem. Nie może powstać okres bez Twórcy ani z dwoma Twórcami. Informacja o osobie pierwotnie tworzącej świat pozostaje historyczna. Zwykłe operacje zarządzania członkostwem, w tym operacje Właściciela i Custom, nie mogą zmienić ani usunąć członkostwa Twórcy.

### Prywatność i dostęp bez członkostwa

| Prywatność | Odczyt „O świecie” | Odczyt reszty bieżącej treści | Komentowanie przez osobę bez członkostwa |
|---|---|---|---|
| Publiczny — `public` | Tak | Tak | Tak, po zalogowaniu |
| Ograniczony — `restricted` | Tak | Nie | Nie |
| Prywatny — `private` | Nie | Nie | Nie |

Domyślny stan nowego świata to `private`. Publiczny odczyt nie daje prawa edycji treści ani wglądu do administracji lub historii. Dodanie komentarza zawsze wymaga konta; zalogowanie i komentowanie publicznego świata nie tworzy automatycznie `WorldMember`.

Katalog Odkrywaj obejmuje światy publiczne. Gość może wejść na „O świecie” ograniczonego świata przez jego bezpośredni adres; pozostałe dane nie mogą być ujawnione przez graf, wyszukiwanie, odnośniki do plików ani odpowiedzi API. Zmiana na `private` odbiera dostęp osobom bez odpowiedniego członkostwa, a zmiana na `restricted` pozostawia im wyłącznie „O świecie”.

### Macierz operacji

Wiersze dotyczą jednego świata. „Z prywatności” oznacza dostęp z tabeli powyżej, a „Przydzielone” — odpowiednie uprawnienie Custom wraz ze spełnionymi warunkami dostępu do danych.

| Operacja | Gość | Czytelnik | Redaktor | Właściciel | Twórca | Custom |
|---|---|---|---|---|---|---|
| Odczyt bieżącej treści | Z prywatności | Tak | Tak | Tak | Tak | Przydzielone + prywatność |
| Dodawanie komentarzy | Publiczny, po zalogowaniu | Tak | Tak | Tak | Tak | Przydzielone + prywatność |
| Edycja i usuwanie własnych komentarzy | Publiczny, po zalogowaniu | Tak | Tak | Tak | Tak | Przydzielone + prywatność |
| Usuwanie cudzych komentarzy | Nie | Nie | Nie | Tak | Tak | Przydzielone |
| Edycja treści „O świecie” | Nie | Nie | Tak | Tak | Tak | Przydzielone |
| Tworzenie, edycja i usuwanie kart, relacji, wpływów i wydarzeń, także cudzych | Nie | Nie | Tak | Tak | Tak | Przydzielone |
| Dodawanie i usuwanie ilustracji | Nie | Nie | Tak | Tak | Tak | Przydzielone |
| Odczyt i zmiana ustawień, zmiana prywatności | Nie | Nie | Nie | Tak | Tak | Przydzielone |
| Odczyt i zarządzanie członkami, rolami oraz Custom | Nie | Nie | Nie | Tak, z ograniczeniami poniżej | Tak | Przydzielone, z ograniczeniami poniżej |
| Nadanie roli Właściciela | Nie | Nie | Nie | Nie | Tak | Nie |
| Zmiana lub usunięcie członkostwa Twórcy zwykłą operacją | Nie | Nie | Nie | Nie | Nie | Nie |
| Przekazanie roli Twórcy istniejącemu Właścicielowi | Nie | Nie | Nie | Nie | Tak | Nie |
| Usunięcie całego świata | Nie | Nie | Nie | Nie | Tak | Nie |
| Odczyt historii i porównywanie wersji | Nie | Nie | Tak, w dostępnym zakresie | Tak | Tak | Przydzielone, w dostępnym zakresie |
| Nadpisanie lub usunięcie wpisu historii zwykłą operacją | Nie | Nie | Nie | Nie | Nie | Nie |

Usuwanie cudzej zawartości przez Redaktora dotyczy treści świata; nie obejmuje moderowania cudzych komentarzy, usunięcia świata ani zmiany logów. Zasady 0.8 określają wycofanie danych i powiązań z bieżących widoków, bez niszczenia ich historycznych wersji i plików. Usunięcie kontekstu ukrywa jego komentarze w bieżących widokach, bez usuwania cudzej wypowiedzi z historii; nie daje prawa do osobnej moderacji cudzych komentarzy. Usunięcie świata wymaga potwierdzenia Twórcy.

### Katalog parametrów

Parametr ma postać `obszar.operacja`. Każdy element listy operacji oznacza osobne uprawnienie, np. `entities.read` i `entities.delete`. Nazwy `read`, `create`, `update`, `delete` odpowiadają odczytowi, tworzeniu, edycji i usuwaniu; skrót `w` nie łączy ich w jedno niejawne zezwolenie.

| Obszar lub parametr | Operacje / znaczenie |
|---|---|
| `about` | `read`, `update` — wstęp i treść „O świecie”. |
| `entities` | `read`, `create`, `update`, `delete` — karty zatwierdzonych typów. |
| `relationships` | `read`, `create`, `update`, `delete` — powiązania kart. |
| `influences` | `read`, `create`, `update`, `delete` — kierunkowy wpływ postaci prezentowany w `FATE_GRAPH`. |
| `events` | `read`, `create`, `update`, `delete` — wydarzenia, ich uczestnicy, miejsca i zależności. |
| `graphs.read` | Przeglądanie grafów, wyłącznie w zakresie dostępnych danych źródłowych. |
| `media` | `read`, `upload`, `delete` — ilustracje i okładka, z kontrolą dostępu do ich kontekstu. |
| `comments` | `read`, `create`, `update_own`, `delete_own`, `delete_any` — ostatnie uprawnienie służy moderacji; nie daje prawa redagowania cudzej wypowiedzi. |
| `settings` | `read`, `update` — wewnętrzne ustawienia i podstawowe informacje projektu; nie zastępuje szczególnych uprawnień do prywatności, członków, usunięcia i przekazania. |
| `world.visibility.update` | Przełączanie pomiędzy trzema stanami prywatności. |
| `members` | `read`, `add`, `remove` — lista i członkostwo istniejących użytkowników. |
| `members.roles.update` | Zmiana ról z ochroną Twórcy i wyłącznością nadawania `owner` przez Twórcę. |
| `members.permissions.update` | Przydzielanie zestawu Custom, z ograniczeniem zakresu możliwego do nadania. |
| `world.delete` | Usunięcie świata — wyłącznie Twórca; nie jest przełącznikiem delegowanym do Custom. |
| `creator.transfer` | Przekazanie roli Twórcy — wyłącznie obecny Twórca, istniejącemu Właścicielowi. |
| `history.read` | Odczyt zestawów zmian, wersji i różnic; nie omija uprawnień do danych historycznych. Domyślnie Twórca, Właściciel i Redaktor; opcjonalnie Custom. |

### Reguły łączenia i egzekwowania uprawnień

- Dostęp wynika z sumy praw wynikających z prywatności oraz roli albo przydzielonych uprawnień Custom w tym samym świecie. Następnie obowiązują warunki operacji: logowanie, własny komentarz, dostęp do kontekstu, izolacja świata i ochrona Twórcy. Brak zezwolenia oznacza odmowę.
- Custom nie zawiera jawnych zakazów i nie nadaje praw osobno dla pojedynczych kart. Wyłączony przełącznik nie odbiera dostępu publicznego. Role Czytelnika i Redaktora nie są niejawnie przydzielane użytkownikowi Custom.
- Włączenie tworzenia, edycji lub usuwania treści wymaga odczytu właściwego obszaru i kontekstu. Konfiguracja Custom ma wymuszać te zależności. Przydział `graphs.read`, `media.read`, `comments.read` lub `history.read` sam w sobie nie odblokowuje ukrytych danych źródłowych.
- Zarządzanie członkami, rolami i Custom nie pozwala zwiększyć własnych praw ani nadać innym praw wykraczających poza zakres zarządzającego. Nadanie `owner` jest dodatkowo zastrzeżone Twórcy, także przy dodaniu członka i zaproszeniu. Rola `creator` powstaje tylko przy utworzeniu świata albo w odrębnej operacji przekazania.
- Odebranie członkostwa przywraca dostęp wynikający z prywatności: w publicznym świecie pozostaje publiczny odczyt i komentowanie po zalogowaniu, w ograniczonym sam wstęp, a w prywatnym brak dostępu. Obniżenie roli lub zmiana Custom musi być respektowana przy kolejnych żądaniach, także z wcześniej otwartą stroną.
- Backend sprawdza uprawnienia przy każdym żądaniu, w tym Fetch, bezpośrednim adresie danych i pliku. Ukrycie przycisku, filtra albo zakładki nie jest kontrolą dostępu. Graf nie ujawnia nazw, opisów ani końców relacji, do których użytkownik nie ma dostępu.
- Ustawienia własnego konta i tworzenie nowego świata wynikają z zalogowania, a nie z roli w cudzym świecie. Nowy świat otrzymuje własnego Twórcę i własne członkostwa.

### Historia zmian i wersje w MVP

- Historia obejmuje wszystkie skuteczne operacje utworzenia, edycji i usunięcia danych świata i zarządzanych przez aplikację plików: wprowadzenie, ustawienia, prywatność, członkostwa i Custom, karty, relacje, wpływy, wydarzenia i ich powiązania, komentarze oraz ilustracje. Obejmuje również zmianę Twórcy oraz samo utworzenie i usunięcie świata.
- Jedna operacja użytkownika tworzy jeden zestaw zmian podobny do commitu: identyfikator świata i rewizji, poprzednią rewizję, wykonawcę, rzeczywisty czas zapisu w UTC, rodzaj operacji oraz listę zmienionych obiektów i plików. Czas historii jest niezależny od fikcyjnej chronologii świata.
- Każdy wpis zawiera stan przed i po albo różnicę pozwalającą odtworzyć oba stany. Utworzenie i usunięcie są jawne. Dla plików trzeba zachować wcześniejsze wersje zawartości i metadane identyfikujące wersję; sama ścieżka lub suma kontrolna nie wystarcza. Zmiana lub usunięcie ilustracji nie może nadpisać jej poprzednich bajtów.
- Zmiana danych i zapis historii stanowią jeden logiczny zapis: błąd historii nie może pozostawić nieudokumentowanej mutacji. Historia grupuje wszystkie skutki operacji, także zmiany powiązań i plików. Równoległe edycje nie mogą po cichu zgubić wersji ani łańcucha zmian.
- Wpisów historii nie można zwyczajnie edytować ani usuwać; kolejna zmiana dopisuje nowy zestaw. Przegląd i porównanie wersji są częścią MVP. Automatyczne przywracanie stanu, gałęzie i scalanie wersji nie zostały zlecone.
- Historia ma własną kontrolę dostępu. Publiczny odczyt i rola Czytelnika nie udostępniają jej domyślnie. `history.read` wymaga też aktualnego prawa do odpowiednich danych; wpisy o ustawieniach i członkostwach wymagają odpowiednich praw administracyjnych. Filtrowanie obejmuje treść, nazwy i identyfikatory obiektów, różnice oraz historyczne pliki, aby nie ujawniać dawnych lub usuniętych danych nieuprawnionym osobom.
- Do historii nie trafiają hasła, tokeny, cookies, sekrety środowiska ani zawartość `.env`. Historia aplikacji dotyczy danych i plików świata; kod repozytorium nadal wersjonuje Git. Nie jest to monitoring dowolnych zmian systemu plików hosta.
- Zgodnie z 0.8 wszystkie skutecznie zapisane dane, historia i wersje plików pozostają na dyskach, również po usunięciu świata z bieżących widoków. Na razie nie ma terminu retencji, automatycznego czyszczenia ani fizycznej kaskady niszczącej archiwum. Zachowanie na dysku nie udostępnia danych publicznie; historia nadal wymaga `history.read` i odpowiednich uprawnień do obszaru oraz kontekstu.

### Warunek ukończenia 0.7

Ukończenie 0.7 oznacza spójny zapis ról, prywatności, macierzy, parametrów, ochrony Twórcy i zatwierdzonego zakresu historii w dokumentacji oraz zadaniach implementacyjnych. Weryfikacja późniejszej implementacji musi objąć zmianę i odebranie praw, próby samodzielnego zwiększenia dostępu, niedozwolone nadanie `owner`, atomowe przekazanie Twórcy, trzy poziomy prywatności, komentowanie po zalogowaniu, usunięcie cudzej treści przez Redaktora oraz kompletność i ochronę historii. Odhaczenie 0.7 nie oznacza działającej autoryzacji, komentarzy ani wersjonowania.

## Dane i zapis — ustalenia 0.8

### Zatwierdzone decyzje

- **Baza danych:** PostgreSQL jako relacyjna baza główna. Zbiory kart, relacji, wydarzeń, uczestnictwa i historii są ze sobą powiązane; grafy prezentują te dane. Dodatkowy silnik bazy grafowej nie jest potrzebny w zatwierdzonym zakresie.
- **Identyfikacja:** stałe liczbowe `id` oraz osobny `type`. Docelowo ID jest generowane jako `BIGINT` i chronione kluczem głównym; nie jest zmieniane ani ponownie przydzielane po usunięciu. Wszystkie typy kart należą do wspólnego zbioru kart ze wspólną numeracją; odrębne zbiory, np. wydarzenia, mogą mieć własną numerację. Odwołanie poza jednym zbiorem musi określać także rodzaj obiektu. Prefiks w rodzaju `c42` może służyć prezentacji, bez dodatkowego obowiązkowego identyfikatora `fate_name`.
- **Treść:** opisy przechowywane w rekordach bazy, w źródłowej postaci Markdown. Pliki służą ilustracjom i załącznikom, a rekord wskazuje konkretną wersję pliku. Markdown nie daje prawa do osadzania wykonywalnego HTML lub skryptów; sposób bezpiecznego renderowania zostanie dobrany podczas implementacji, bez wyboru nowej biblioteki w tym zadaniu.
- **Niezmienność typu:** istniejąca karta postaci nie może zmienić typu. Powstaje nowa karta z nowym ID i innym typem, powiązana z poprzednią jako ten sam byt. Poprzednia karta zachowuje ID i typ; utworzenie nowej nie oznacza automatycznego scalenia ani przeniesienia pozostałych relacji.
- **Oznaczenie tożsamości:** `10` jest zatwierdzonym kodem rodzaju relacji `same_as` — „są tym samym”. Nie jest wagą ani elementem uniwersalnej skali 0–10. Nowsza decyzja użytkownika zastępuje odmienne znaczenia dawnej skali w `myra.txt`, którego nie zmieniamy.
- **Usuwanie relacji:** usunięta bieżąca relacja znika przy obu końcach i w grafie. Jest to jedno powiązanie prezentowane w kilku miejscach; usunięcie go nie usuwa kart końcowych. Historia relacji pozostaje zachowana.
- **Zachowanie danych:** na razie wszystkie skutecznie zapisane dane, logi i historyczne wersje plików pozostają na dyskach bez automatycznej daty wygaśnięcia. Usunięcie świata wycofuje jego bieżącą zawartość ze zwykłego dostępu, bez niszczenia zachowanych wersji i bez nadawania nowego dostępu do archiwum.
- **Równoległy zapis:** wygrywa pierwszy poprawnie zatwierdzony zapis aktualnej wersji danego obiektu. Późniejsza próba zapisu oparta na nieaktualnej rewizji jest odrzucana jako konflikt (`409`), bez nadpisania pierwszego zapisu i bez automatycznego scalania. Niezapisana treść pozostaje dostępna autorowi do porównania i ponowienia po zapoznaniu się z aktualną wersją. Nie oznacza to wykonywania commitów Git przez aplikację. Zmiany niezależnych obiektów mogą zostać kolejno zapisane do wspólnej historii świata.

### Format karty zatwierdzony na podstawie MYRA

Użytkownik sprawdził i zatwierdził poniższy format przed jego wprowadzeniem. Jest wspólny dla postaci, lokacji, frakcji, ras/gatunków i artefaktów. Na tym etapie nie dodajemy osobnych zestawów pól zależnych od typu.

| Pole | Format i obowiązkowość |
|---|---|
| `id` | Systemowy, niezmienny identyfikator liczbowy. |
| `world_id` | Systemowe wskazanie świata, do którego należy karta. |
| `type` | Wymagany przy tworzeniu, później niezmienny: postać, lokacja, frakcja, rasa/gatunek lub artefakt. |
| `revision` | Systemowe oznaczenie wersji do kontroli zapisu; użytkownik nie edytuje go ręcznie. |
| `name` | Wymagana niepusta nazwa; może się powtarzać. |
| `aliases` | Opcjonalna lista elementów `{name, context}`: nazwa aliasu oraz opcjonalny kontekst, np. przez kogo lub kiedy jest używany. |
| `characteristics` | Opcjonalny Markdown: wygląd, charakter, budowa lub cechy elementu odpowiednie do typu. |
| `descriptions` | Opcjonalny Markdown: opis fabularny i pozostałe informacje. Nie jest logiem zmian aplikacji. |
| `goals` | Opcjonalna lista celów z treścią Markdown. |
| `summary` | Opcjonalny krótki opis, wynikający z dotychczasowego zakresu MVP. |
| `tags` | Opcjonalna lista tagów z zakresu MVP. |
| `illustrations` | Opcjonalne odwołania do wersjonowanych plików ilustracji. |

Jedyną identyfikacją karty jest `id` w jej zbiorze; nie dodajemy obowiązkowego unikalnego `fate_name`. Opisy nie zastępują strukturalnych relacji do innych kart. Format list, obowiązkowość i znaczenie pól są zatwierdzone; szczegóły fizycznego zapisu i techniczne limity żądania oraz plików powstaną z walidacją odpowiednich modułów. Wydarzenia pozostają odrębnymi wpisami, a ich dokładny model czasu należy do 9.1. Dialogi nadal pozostają poza podstawowym MVP.

### Początkowe rodzaje i reguły relacji

| Rodzaj | Dozwolone końce | Kierunek |
|---|---|---|
| `belongs_to` — należy do | Postać → frakcja | Skierowana |
| `is_of_species` — jest przedstawicielem | Postać → rasa/gatunek | Skierowana |
| `located_in` — znajduje się w | Postać lub artefakt → lokacja | Skierowana |
| `part_of` — jest częścią | Lokacja → lokacja | Skierowana |
| `allied_with` — sojusz | Postać/frakcja ↔ postać/frakcja | Wzajemna |
| `hostile_to` — wrogość | Postać/frakcja ↔ postać/frakcja | Wzajemna |
| `same_as` — ten sam byt, kod `10` | Dowolne dwie różne karty, także różnych typów | Wzajemna |

- Relacja ma własne liczbowe ID, świat, dwa końce, rodzaj, opis i rewizję. Oba końce wskazują istniejące karty tego samego świata; rekord nie może być połączony sam ze sobą.
- Dla pary i danego rodzaju istnieje najwyżej jedna bieżąca relacja w danym kierunku. Odwrócony zapis relacji wzajemnej jest duplikatem, a przeciwny kierunek relacji skierowanej jest osobnym powiązaniem. Między parą kart mogą istnieć różne rodzaje relacji.
- Rodzaj określa kierunek i dozwolone typy końców. `same_as` nie scala ID, treści, uprawnień ani pozostałych relacji i nie tworzy automatycznie dalszych powiązań. Kod `10` dotyczy tego rodzaju, a nie siły wpływu w `FATE_GRAPH`.
- Utworzenie nowej karty innego typu i powiązania `same_as` z poprzednią jest jedną operacją i jednym zestawem historii. Wymaga odpowiednich praw do kart oraz relacji; dotychczasowa karta nie zmienia typu.

### Osobne wpisy wpływu postaci

Wpływ jest odrębnym rodzajem danych, a nie rodzajem zwykłej relacji. Ma własne ID, świat i rewizję oraz źródłową i docelową kartę dwóch różnych postaci tego świata i opis działania lub kontekstu w Markdown. Wykorzystuje istniejące karty; nie tworzy kopii postaci.

Między tą samą parą A→B może istnieć kilka wpisów wpływu opisujących różne działania lub konteksty. Odczyt i zmiany podlegają `influences`; prawa do zwykłych relacji nie dają automatycznie dostępu do wpływów. `FATE_GRAPH` przedstawia ich kierunek i opis, pozostając częścią MVP. Nie wprowadzamy obowiązkowych wag liczbowych ani nie stosujemy do wpływów kodu tożsamości `10`.

### Usuwanie bieżącej treści i zachowanie historii

- Usunięcie karty wycofuje ją z bieżących list i grafów. Usuwa aktywne relacje i wpływy, w których karta jest dowolnym końcem, oraz odłącza jej uczestnictwa i wskazania jako miejsca wydarzeń. Inne karty i same wydarzenia pozostają.
- Komentarze i ilustracje przypisane do usuniętej karty znikają z bieżących widoków jej kontekstu. Zapisane komentarze, dane relacji i uczestnictwa oraz wszystkie wcześniejsze wersje plików pozostają zachowane w historii i magazynie.
- Analogicznie wycofanie wydarzenia usuwa z bieżącej treści jego uczestnictwa, miejsca i zależności po obu końcach oraz udostępnianie jego kontekstu. Nie usuwa kart uczestników, lokacji ani pozostałych wydarzeń. Usunięcie samej relacji lub wpływu usuwa jedno powiązanie z widoków obu końców, bez usuwania kart.
- Usunięcie komentarza albo odłączenie ilustracji zmienia bieżący stan; nie niszczy dawnych treści lub bajtów pliku. Wycofanie całego świata jest operacją Twórcy, wyłącza zwykły dostęp i zachowuje dane zgodnie z zatwierdzoną retencją bez terminu czyszczenia.
- Wszystkie skutki operacji stanowią jeden zestaw zmian. Usuwanie nie jest obejściem Custom: backend sprawdza prawa do wszystkich zmienianych danych, a brak wymaganych praw odrzuca całą operację bez częściowej zmiany. Dostęp do zachowanej historii nadal wymaga `history.read` i odpowiednich praw źródłowych.

### Warunek ukończenia 0.8

Format karty, katalog i reguły relacji, interpretacja `10` oraz osobny zapis wielokrotnych wpływów zostały potwierdzone przez użytkownika. Zapisano też zatwierdzone zasady identyfikacji, Markdown, niezmienności typu, usuwania, retencji i pierwszego poprawnego zapisu wygrywającego konflikt. Ukończenie 0.8 oznacza spójne ustalenia dokumentacyjne; nie tworzy tabel, formularzy ani działającego mechanizmu zapisu.

## Strony i nawigacja

Ustalenia punktu 0.3 obejmują siedem głównych stron i pomocniczy ekran tworzenia świata. Ustalenia 0.7 dodają pomocniczy widok historii. Adresy poniżej określają plan nawigacji do wykorzystania przy implementacji routingu i poszczególnych modułów.

### Mapa stron

| Strona | Planowany adres | Cel, zawartość i główne działania | Dostęp |
|---|---|---|---|
| **Odkrywaj — strona główna** | `/` | Katalog publicznych światów: okładka, tytuł, autor, opis i tagi; wyszukiwanie, filtrowanie, sortowanie i stronicowanie. Wybranie świata otwiera jego projekt. | Każdy, również bez konta; katalog zawiera wyłącznie opublikowane światy. |
| **Logowanie** | `/login` | Formularz logowania, komunikaty walidacji i odnośnik do rejestracji. Po powodzeniu przejście do biblioteki lub wcześniej żądanej strony zgodnie z zasadami powrotu poniżej. | Osoba niezalogowana; zalogowany użytkownik przechodzi do biblioteki. |
| **Rejestracja** | `/register` | Formularz utworzenia konta, komunikaty walidacji i odnośnik do logowania. Po utworzeniu konta przejście do logowania. | Osoba niezalogowana; zalogowany użytkownik przechodzi do biblioteki. |
| **Biblioteka** | `/library` | Własne i udostępnione światy z informacją o roli użytkownika. Otwieranie projektu i przycisk „Utwórz świat”. Pusta biblioteka zachęca do utworzenia pierwszego świata. | Zalogowany użytkownik; widzi tylko światy własne i te, do których otrzymał dostęp. |
| **Otwarty projekt** | `/worlds/{id}` | Domyślnie otwiera „O świecie”, czyli wstęp/README świata. Wspólny nagłówek i zakładki prowadzą do grafów, kart, wydarzeń oraz placeholdera Spójność. Działania wynikają z macierzy 0.7. | Publiczny: odczyt bieżącej treści dla każdego. Ograniczony: Gość widzi tylko „O świecie”. Prywatny: wymagane członkostwo i odpowiednie uprawnienia. |
| **Ustawienia projektu** | `/worlds/{id}/settings` | Podstawowe informacje, ustawienia prywatności i zarządzanie członkami według parametrów 0.7. Usunięcie świata i przekazanie roli Twórcy są osobnymi chronionymi operacjami. Powrót do projektu. | Twórca, Właściciele oraz Custom z `settings.read`; każda operacja wymaga własnego uprawnienia. |
| **Ustawienia konta** | `/account/settings` | Osobna strona zmiany nazwy wyświetlanej i hasła po weryfikacji obecnego hasła; komunikaty walidacji i wyniku zapisu. Powrót do biblioteki. | Zalogowany użytkownik, wyłącznie dla własnego konta. |
| **Tworzenie świata — ekran pomocniczy** | `/worlds/new` | Osobny formularz otwierany z biblioteki. Po zapisaniu powstaje prywatny świat należący do twórcy i otwiera się jego projekt; anulowanie prowadzi do biblioteki. | Zalogowany użytkownik. |
| **Historia — ekran pomocniczy** | `/worlds/{id}/history` | Zestawy zmian z autorem i czasem, lista zmienionych danych i plików, odczyt oraz porównanie wersji. Odnośnik z nagłówka projektu, bez dodawania szóstej zakładki. | `history.read` oraz aktualne uprawnienia do przeglądanych danych; domyślnie Twórca, Właściciele i Redaktorzy. |

`{id}` oznacza identyfikator wybranego świata. Ścieżka `/worlds/new` oznacza formularz tworzenia i musi być rozróżniana przez router od adresu istniejącego świata.

### Wspólna nawigacja i widok projektu

- Logo WorldGraph prowadzi do Odkrywaj. W MVP Odkrywaj pełni rolę strony głównej; obecny szablon demonstracyjny jest punktem wyjścia do późniejszej implementacji.
- Osoba niezalogowana ma w głównej nawigacji odnośniki „Odkrywaj”, „Zaloguj się” i „Zarejestruj się”.
- Zalogowany użytkownik ma odnośniki „Odkrywaj”, „Biblioteka” i „Ustawienia konta” oraz akcję „Wyloguj”. Wylogowanie kończy sesję i prowadzi do Odkrywaj; nie wymaga osobnej strony.
- Wszystkie trzy stany prywatności korzystają ze wspólnego widoku projektu pod tym samym adresem. Zakres danych i dostępne działania wynikają z prywatności oraz roli lub Custom w tym świecie.
- Ustawienia projektu są osobną stroną dostępną zgodnie z `settings.read`; jej działania wymagają odpowiadających im uprawnień. Redaktorzy edytują treść w widokach projektu. Czytelnicy oraz zalogowani Goście publicznego świata mogą komentować bez prawa zmieniania treści świata.
- Biblioteka i Odkrywaj prowadzą do tego samego projektu. Z projektu można przejść do Odkrywaj, a po zalogowaniu również do biblioteki.

### Podstawowe przejścia

1. **Pierwsze użycie:** Odkrywaj → rejestracja → logowanie → biblioteka → tworzenie świata → prywatny projekt.
2. **Powrót do pracy:** logowanie → biblioteka → własny lub udostępniony projekt → przeglądanie albo edycja zgodnie z rolą.
3. **Prywatność:** projekt → ustawienia projektu → wybór `public`, `restricted` albo `private` przez uprawnioną osobę. Publiczny świat pojawia się w Odkrywaj; ograniczony udostępnia Gościowi tylko wstęp, a prywatny wymaga odpowiednio nadanego dostępu.
4. **Przeglądanie przez Gościa:** Odkrywaj lub bezpośredni adres publicznego świata → odczyt projektu → opcjonalne logowanie i komentarz. Bezpośredni adres ograniczonego świata otwiera tylko „O świecie”.
5. **Współpraca:** uprawniony zarządzający nadaje istniejącemu użytkownikowi rolę lub Custom; świat pojawia się w jego bibliotece. Tylko Twórca może nadać `owner`. Zaproszenia e-mail pozostają rozszerzeniem po MVP.
6. **Historia:** uprawniona osoba otwiera historię z nagłówka, wybiera zestaw zmian i porównuje wersje dostępnych jej danych lub plików. Edycja treści dopisuje nowy zestaw zmian.

Po zwykłym logowaniu użytkownik trafia do biblioteki. Jeśli logowanie zostało wywołane próbą otwarcia strony wymagającej konta, po powodzeniu wraca do tej strony, o ile adres jest bezpieczną ścieżką wewnątrz aplikacji i użytkownik ma wymagane uprawnienia. Cel powrotu należy zachować również przy przejściu przez rejestrację; przy niepoprawnym lub niedostępnym celu powrót prowadzi do biblioteki.

Próba wejścia na stronę wymagającą konta przez osobę niezalogowaną prowadzi do logowania bez ujawniania chronionej zawartości. Brak uprawnień po zalogowaniu uniemożliwia odczyt i edycję danych. Serwer sprawdza dostęp także przy bezpośrednich żądaniach, niezależnie od widoczności odnośników.

Gdy nie ma publicznych światów, Odkrywaj pokazuje komunikat o pustym katalogu. Brak dostępu i nieistniejące adresy wymagają kontrolowanej odpowiedzi; obsługa błędów i statusów HTTP należy do implementacji szkieletu aplikacji w etapie 1.

### Granice ustaleń 0.3

Mapa określa strony, ich cele, podstawowy dostęp, działania i przejścia. Zakładki wewnątrz projektu opisuje poniższa sekcja ustaleń 0.4. Macierz 0.7 aktualizuje dostęp do tych stron i dodaje historię; ustalenia 0.8 określają zatwierdzony format kart, reguły relacji, wpływy, zachowanie danych i sposób zapisu. Widoki szczegółów i edycji kart będą rozwijane w etapie 5. Wydarzenia należą do podstawowego MVP, a właściwa kontrola spójności do extended MVP.

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

Wstęp/README oznacza rolę treści „O świecie”; sposób jej formatowania zostanie dobrany podczas implementacji. `CURRENT_EVENT` oznacza pracę nad istniejącym wydarzeniem i jego statusem opracowania, bez tworzenia kopii wydarzenia. Zmiany tego wydarzenia korzystają ze wspólnej historii świata. Szczegółowe pola pozostają do ustalenia przy modelu danych i module wydarzeń.

### Widoki zakładki Graf

| Widok | Inspiracja z `myra.txt` | Zakres |
|---|---|---|
| **Relacje** | `RELETIONSHIPS_GRAPH` | Domyślny widok kart świata i ich opisanych relacji, z filtrowaniem i przejściem do szczegółów. Podstawowe MVP. |
| **Lokacje** | `LOCATIONES_GRAPH` | Widok połączeń między kartami miejsc, wyodrębniony z danych świata. Podstawowe MVP obejmuje graf powiązań lokacji; mapa przestrzenna, teren, odległości i koszty podróży pozostają odłożone. |
| **Wydarzenia** | `EVENTE_GRAPH` | Wydarzenia jako węzły oraz ich zależności, np. „przed”, „doprowadziło do” i „jest częścią”. Podstawowe MVP, etap 9; dane są wspólne z zakładką Wydarzenia. |
| **Los — wpływ postaci** | `FATE_GRAPH` | Graf przedstawiający wpływ poszczególnych postaci na inne postacie i ich działania w fabule. Węzły reprezentują istniejące karty postaci, a skierowane powiązania pokazują osobne wpisy wpływu zatwierdzone w 0.8; kilka różnych wpisów A→B jest dozwolonych. Nie ma obowiązkowych wag liczbowych. Jest niezależny od kontroli spójności i należy do podstawowego MVP, etap 7 w ramach etapów 1–9. |

Widok lokacji jest sposobem przeglądania istniejących kart i relacji. Graf wydarzeń korzysta z modelu wydarzeń i ich zależności. Przełączanie widoku zmienia prezentację danych, bez kopiowania kart ani wydarzeń. Dokładne parametry adresu wybranego widoku grafu zostaną ustalone przy jego implementacji.

### Układ inspirowany GitHubem

- Nad treścią znajduje się wspólny nagłówek projektu: Twórca/nazwa świata, oznaczenie publiczny/ograniczony/prywatny oraz rola zalogowanego użytkownika, jeśli ma nadany dostęp. Odnośnik do historii jest dostępny zgodnie z `history.read`.
- Pod nagłówkiem biegnie poziomy pasek pięciu zakładek. Aktywna zakładka jest wyraźnie oznaczona, a wspólny nagłówek i nawigacja pozostają dostępne po przejściu do innej zakładki.
- Treść wybranej zakładki znajduje się pod paskiem. W zakładce Graf osobny przełącznik wybiera widok grafu.
- Na małych ekranach pasek zakładek można przewijać poziomo; aktywna pozycja pozostaje widoczna i dostępna z klawiatury. Odnośniki zachowują czytelne nazwy i widoczny fokus.
- Zakładki są odnośnikami do osobnych adresów, dzięki czemu można odświeżyć widok, zapisać link i korzystać z historii przeglądarki.
- Ustawienia projektu pozostają osobną stroną `/worlds/{id}/settings`, dostępną z nagłówka zgodnie z macierzą 0.7. Historia jest pomocniczą stroną `/worlds/{id}/history`. Inspiracja GitHubem dotyczy organizacji nagłówka, zakładek, treści i zestawów zmian.

### TBI, dostęp i puste stany

Wszystkie pięć zakładek jest widocznych. Zakładki spoza podstawowego MVP oraz funkcje jeszcze niewdrożone są klikalne i pod swoim adresem wyświetlają stronę **TBI — funkcja zaplanowana do wdrożenia**. Strona zachowuje kontekst świata, wskazuje wybraną zakładkę i oferuje powrót do „O świecie”. W ukończonym podstawowym MVP dotyczy to Spójności. Wydarzenia i wszystkie cztery widoki grafów, w tym Los (`FATE_GRAPH`), muszą już działać; podczas ich implementacji TBI może być wyłącznie stanem przejściowym. Placeholder nie przedstawia wyników analizy ani pozornie działających narzędzi.

Przed wyświetleniem zakładki lub strony TBI serwer sprawdza dostęp do jej danych, również przy wejściu bezpośrednim adresem. Wspólny układ nie nadaje dostępu: Gość ograniczonego świata otrzymuje wyłącznie „O świecie”, a prywatnego żadnej treści. Niedostępna zakładka wymaga kontrolowanej odmowy dostępu, a nie TBI. Edycja wstępu wymaga `about.update`; zmiany ustawień administracyjnych i członkostwa wymagają odrębnych praw z macierzy 0.7.

Puste stany dostępnych funkcji są odróżnione od TBI:

- „O świecie” bez opisu pokazuje informację o braku wprowadzenia; osoba z `about.update` może je uzupełnić.
- „Karty” bez elementów pokazują pustą listę i możliwość dodania karty osobie z prawem edycji.
- Graf bez węzłów lub połączeń opisuje brak danych dla wybranego widoku; pojedynczy element można pokazać również bez relacji.
- „Wydarzenia” bez wpisów pokazują pustą listę i możliwość dodania wydarzenia osobie z prawem edycji. Po wdrożeniu extended MVP i wykonaniu analizy Spójność może pokazać brak wykrytych ostrzeżeń; ten stan jest odróżniony od TBI.

Ukończenie 0.4 oznacza ustalenie zawartości, adresów, nawigacji i zachowania zakładek oraz aktualizację granic MVP. Implementacja interfejsu, grafów, wydarzeń i analizy spójności pozostaje w odpowiednich etapach TODO.

## Odniesienie do szkicu MYRA

| Element szkicu `myra.txt` | Zakres WorldGraph |
|---|---|
| `MAIN_LORE` | Wstęp/README w zakładce „O świecie”: opis i założenia świata, kierunek fabuły, jej cele i tematy. |
| `MYRA_LIST` | Karty elementów świata według formatu zatwierdzonego w 0.8: opisy, aliasy i cele oraz dodatki dotychczasowego MVP, bez dodatkowego unikalnego `fate_name`. |
| `RELETIONSHIPS_GRAPH` | Relacje między kartami i ich wizualizacja w MVP, bez uniwersalnej skali wag 0–10. |
| `EVENTS_LIST`, `CURRENT_EVENT`, `EVENTE_GRAPH` | Wydarzenia, praca nad bieżącymi wpisami, chronologia i graf zależności w podstawowym MVP, etap 9. |
| `LOCATIONES_GRAPH` | Inspiracja widoku połączeń lokacji w podstawowym MVP; funkcje mapy przestrzennej pozostają odłożone. |
| `FATE_GRAPH` | Graf osobnych, kierunkowych wpisów wpływu postaci na inne postacie i ich działania, zatwierdzonych w 0.8; wiele wpisów dla pary A→B, bez obowiązkowych wag. Realizowany w etapie 7 i wymagany w podstawowym MVP etapów 1–9. |
| `UPDATE_LIST` | Wspólna historia zmian danych i plików świata w podstawowym MVP: zestawy zmian, wersje i porównanie zgodnie z 0.7. |
| Dialogi | Funkcja odłożona poza pierwszą wersję. |

Szkic stanowi odniesienie do celu produktu. Model pojęciowy 0.6 zaktualizowano zgodnie z 0.7–0.8 w ARCHITECTURE.md; macierz uprawnień, wymagania historii, format kart, reguły relacji i wpływu oraz skutki usuwania zapisano powyżej po wymaganym potwierdzeniu użytkownika. Nowsze decyzje mają pierwszeństwo przed odmiennymi zapisami szkicu, którego nie nadpisujemy. Kod rodzaju `same_as = 10` nie jest dawną wagą MYRA.

## Warunek ukończenia MVP

Użytkownik może przejść cały proces:

**konto → prywatny świat → karty → relacje i wydarzenia → widoki grafów i chronologia → publikacja → odczyt przez gościa.**

Weryfikacja obejmuje współpracę i ochronę ról: Twórca nadaje `owner`, Właściciel nadaje rolę Redaktora lub zestaw Custom bez zwiększania własnych praw, a Redaktor edytuje i usuwa także cudzą treść. Właściciel nie może nadać `owner`, zmienić ani usunąć Twórcy lub całego świata. Przekazanie roli Twórcy istniejącemu Właścicielowi zachowuje dokładnie jednego Twórcę i zapisuje poprzedniego jako Właściciela.

Czytelnik komentuje bez możliwości edycji treści świata. Publiczny świat udostępnia bieżącą treść Gościowi i pozwala mu komentować po zalogowaniu; ograniczony udostępnia mu wyłącznie „O świecie”, a prywatny odmawia dostępu. Zmiana prywatności, odebranie członkostwa i ograniczenie Custom są respektowane w interfejsie, odpowiedziach API, wyszukiwaniu, grafach i bezpośrednich adresach plików.

Każda skuteczna zmiana jest widoczna w historii jako zestaw z autorem, czasem, zmienionymi obiektami i plikami oraz odtwarzalnym stanem przed i po. Sprawdzenie obejmuje tworzenie, edycję, usuwanie, zmianę praw i prywatności, komentarze, wymianę ilustracji z zachowaniem dawnych bajtów, operacje wieloobiektowe, awarię zapisu i równoległe edycje. Uprawniona osoba może porównać wersje; Gość i Czytelnik nie otrzymują historii automatycznie, a dawnych danych administracyjnych nie ujawnia sam `history.read`.

W części wydarzeń użytkownik może utworzyć i edytować wydarzenie, powiązać je z uczestnikami i miejscem, określić status opracowania oraz zależności, a następnie przeglądać te same dane w liście, chronologii i grafie wydarzeń. Publiczny odczyt obejmuje także wydarzenia. Sprawdzenie modelu czasu uwzględnia brakujące i niepewne daty oraz izolację światów.

Weryfikacja nawigacji obejmuje domyślne „O świecie”, osobne adresy zakładek, przełączanie widoków grafów oraz widoczną Spójność prowadzącą do TBI. Sprawdzenie `FATE_GRAPH` obejmuje utworzenie i edycję opisu kierunkowego wpływu jednej postaci na inną i jej działania oraz odczyt tego wpływu w grafie i odpowiedniej liście. Wszystkie wymagane funkcje etapów 1–9 muszą działać; TBI dla Wydarzeń lub któregokolwiek z czterech widoków grafu oznacza, że MVP nie jest jeszcze ukończone.

Zakres MVP uznaje się za wykonany po zaimplementowaniu i sprawdzeniu wymienionych funkcji, historii oraz kontroli dostępu. Ustalenia dokumentacyjne 0.2–0.4 opisują zakres i nawigację, 0.6 model pojęciowy, 0.7 uprawnienia i historię, a 0.8 format i zasady danych; nie oznaczają implementacji aplikacji.

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
- Automatyczne przywracanie stanu, gałęzie i scalanie wersji; zapis wszystkich zmian, wcześniejsze wersje i ich porównanie należą już do MVP.
- Prywatność pojedynczych kart i ukrywanie spoilerów w opublikowanym świecie.
- Współpraca w czasie rzeczywistym.
- AI i automatyczne generowanie fikcji.
- Tworzenie relacji przeciąganiem i zapis własnych układów grafu.

## Realizacja na WdPAI

Projekt powstaje na przedmiot WdPAI. MVP jest pierwszym kamieniem milowym; funkcje przesunięte na później pozostają w planie końcowej wersji opisanej w TODO. Kryteria z `docs/REQUIREMENTS.md` wymagają osobnej weryfikacji i dowodów realizacji.

Zakładany stos to obiektowy PHP, PostgreSQL, HTML/CSS i JavaScript z Fetch API, uruchamiane lokalnie przez Docker Compose. Kryterium 14 oznacza JavaScript. Bezpieczeństwo, testy, responsywność, dostępność, PL/EN i motywy light/dark są rozwijane wraz z odpowiednimi etapami implementacji. Plan architektury znajduje się w `ARCHITECTURE.md`.

