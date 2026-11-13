# TODO — WorldGraph

Plan implementacji, wersja 2026-11-13.

## Zasady pracy

- Ten dokument jest planem, nie poleceniem wykonania całej aplikacji.
- Implementuj wyłącznie zadanie wskazane w aktualnym zleceniu. Jeden prompt powinien obejmować małą, spójną zmianę.
- Przed pracą przeczytaj AGENTS.md, PROJECT.md, ARCHITECTURE.md i docs/REQUIREMENTS.md.
- Po zadaniu przedstaw zmienione pliki, sposób weryfikacji i pozostałe ograniczenia.
- Zaznaczaj `[x]` dopiero po wykonaniu i sprawdzeniu zadania. Folder lub deklaracja w dokumentacji nie jest implementacją.
- Aktualizuj dokumentację i ERD razem z implementacją. Testy, bezpieczeństwo, tłumaczenia i responsywność rozwijaj na bieżąco.
- Twórz małe, logiczne commity. Nie squashuj historii; końcowa wersja ma trafić do main przez merge commit.
- Nie dodawaj zależności, kontenerów ani funkcji na zapas.

## Zakres roboczy

WorldGraph służy do organizacji wiedzy o fikcyjnych światach: kart, relacji, wydarzeń i chronologii. Jeden projekt świata przechowuje dane, a graf i lista są sposobami ich prezentacji. Ta sama karta nie jest kopiowana dla różnych widoków grafu.

Podstawowy proces MVP: konto → prywatny świat → współpraca i komentarze → karty → relacje i wpływ postaci → graf → ustawienie prywatności → przeglądanie przez gościa → wydarzenia i chronologia. Każda udana zmiana danych i plików świata jest zapisywana w historii wersji. Kontrola spójności i zaproszenia e-mail pozostają dalszymi etapami.

Nowy projekt jest prywatny. Poziomy prywatności: publiczny — Gość odczytuje aktualną zawartość świata, ograniczony — Gość odczytuje tylko „O świecie”, prywatny — Gość nie ma dostępu. Gość oznacza brak przydzielonej roli, także po zalogowaniu. Publiczne komentowanie wymaga konta, ale nie członkostwa. Role członków: jeden Twórca, zero lub więcej Właścicieli, Redaktor, Czytelnik i Custom; zakres oraz macierz określa PROJECT.md. Historia nie staje się publiczna wraz z bieżącą zawartością.

Punkt odniesienia dla stanu początkowego: przegląd gałęzi Initial-setup z 2026-11-06. Istnieją dokumentacja, szkielet katalogów, konfiguracja Docker Compose i statyczna strona HTML/CSS. Nie traktuj tego opisu jako bieżącej weryfikacji repozytorium; przed edycją sprawdź aktualne pliki. Poniższe checkboxy dotyczą zaplanowanych prac i nie oznaczają, że wszystkie istniejące elementy trzeba budować ponownie.

## 0. Ustalenia i dokumentacja

- [x] 0.1 Popraw kryterium nr 14 w docs/REQUIREMENTS.md: Python → JavaScript; zachowaj odnośnik do źródła.
- [x] 0.2 Zatwierdź MVP i listę funkcji odłożonych; zaktualizuj PROJECT.md.
- [x] 0.3 Ustal strony: Odkrywaj, logowanie, rejestracja, biblioteka, otwarty projekt, ustawienia projektu i konta. Ustalenia: [PROJECT.md — Strony i nawigacja](PROJECT.md#strony-i-nawigacja).
- [x] 0.4 Ustal zakładki projektu: O świecie, Graf, Karty, Wydarzenia, Spójność.
- [?] 0.5 Przygotuj prototyp minimum 5 istotnych ekranów w wariancie mobilnym i desktopowym ~~oraz eksport PDF~~.
- [x] 0.6 Ustal model pojęciowy: User, World, WorldMember, Entity, Relationship, Event i uczestnicy wydarzenia.
- [x] 0.7 Rozpisz macierz uprawnień Gościa, Czytelnika, Redaktora, Właściciela, Twórcy i Custom oraz trzy poziomy prywatności; uwzględnij komentarze i historię zmian w MVP. Ustalenia: [PROJECT.md — Role, prywatność i uprawnienia](PROJECT.md#role-prywatność-i-uprawnienia--ustalenia-07).
- [x] 0.8 Ustal pola kart, początkowe typy relacji i zasady usuwania powiązanych danych. Użytkownik potwierdził format kart MYRA, kod rodzaju `same_as = 10`, reguły relacji, osobne wielokrotne wpisy wpływu, PostgreSQL, liczbowe ID i typ, Markdown w rekordach, zachowanie wersji oraz pierwszy poprawny zapis wygrywający konflikt. Ustalenia: [PROJECT.md — Dane i zapis](PROJECT.md#dane-i-zapis--ustalenia-08).
- [x] 0.9 Uzupełnij AGENTS.md o zasady Git, granice warstw, weryfikację i warunki ukończenia zadania. Ustalenia: [AGENTS.md](AGENTS.md).
- [x] 0.10 Rozszerz checklistę kryteriów o warunki akceptacji, status i dowód realizacji. Wariant B: tabela zbiorcza i opisy 36 kryteriów z weryfikacją oraz powiązaniami TODO w [docs/REQUIREMENTS.md](docs/REQUIREMENTS.md).
- [ ] 0.11 Wyjaśnij dopuszczalność zewnętrznej biblioteki grafowej przed jej wyborem.

**Warunek ukończenia:** zakres, nawigacja i podstawowy model są zapisane; nie ma niejawnych decyzji wymagających zgadywania przez agenta. Szczegółowe migracje powstają przy odpowiednich modułach.

## 1. Szkielet aplikacji PHP

Zależność: etap 0.

- [ ] 1.1 Dodaj Composer, przestrzenie nazw i autoloading.
- [ ] 1.2 Utwórz public/index.php jako punkt wejścia.
- [ ] 1.3 Dodaj router: metody HTTP, trasy statyczne, regex, parametry ścieżki i query.
- [ ] 1.4 Skonfiguruj Nginx do przekazywania tras aplikacji do front controllera.
- [ ] 1.5 Dodaj bazowy kontroler i wspólne renderowanie widoków.
- [ ] 1.6 Utwórz layout i współdzielone elementy nawigacji, nagłówka oraz stopki.
- [ ] 1.7 Przenieś istniejącą stronę startową do szablonu bez niepotrzebnego przeprojektowania.
- [ ] 1.8 Dodaj kontrolowane odpowiedzi 400/404/405 oraz bezpieczną obsługę nieoczekiwanych błędów.
- [ ] 1.9 Sprawdź poprawne trasy, niepoprawny identyfikator i niedozwoloną metodę HTTP.

**Warunek ukończenia:** strona działa przez PHP, parametry docierają do kontrolera, a nieznane trasy zwracają właściwy status. Baza nie jest jeszcze wymagana.

## 2. Wspólny interfejs

Zależność: etap 1.

- [ ] 2.1 Uporządkuj tokeny CSS: kolory, typografia, odstępy i zaokrąglenia; minimum 8 zmiennych.
- [ ] 2.2 Przygotuj współdzielone przyciski, formularze, komunikaty i karty.
- [ ] 2.3 Przygotuj nawigację desktopową i mobilną.
- [ ] 2.4 Dodaj motywy light/dark przez data-theme na html, localStorage i prefers-color-scheme; ustawiaj motyw przed renderowaniem.
- [ ] 2.5 Dodaj podstawowy mechanizm tłumaczeń PL/EN, również dla walidacji i błędów.
- [ ] 2.6 Ustal stany pustej listy, ładowania, powodzenia i błędu.
- [ ] 2.7 Sprawdź klawiaturę, focus-visible, etykiety, kontrast, powiększenie 200% i długie treści.

**Warunek ukończenia:** kolejne ekrany mogą korzystać ze wspólnego zestawu elementów, a motyw i język zachowują się spójnie między podstronami.

## 3. Baza danych i konta

Zależność: etap 2.

- [ ] 3.1 Dodaj połączenie PDO konfigurowane przez zmienne środowiskowe.
- [ ] 3.2 Ustal i udokumentuj uruchamianie migracji oraz danych demonstracyjnych.
- [ ] 3.3 Dodaj tabelę użytkowników, model i repozytorium; unikalność e-maila wymuś także w bazie.
- [ ] 3.4 Dodaj rejestrację: walidacja serwerowa, hashowanie hasła, bezpieczne komunikaty.
- [ ] 3.5 Dodaj logowanie, regenerację identyfikatora sesji i właściwe ustawienia cookie.
- [ ] 3.6 Dodaj ochronę tras i powrót po logowaniu wyłącznie do bezpiecznego adresu wewnętrznego.
- [ ] 3.7 Dodaj wylogowanie unieważniające sesję i cookie.
- [ ] 3.8 Dodaj ustawienia konta: nazwa wyświetlana i zmiana hasła po weryfikacji obecnego hasła.
- [ ] 3.9 Stosuj CSRF od pierwszej operacji zapisującej, kodowanie wyjścia i parametryzowane SQL.
- [ ] 3.10 Zweryfikuj poprawne i błędne logowanie, duplikat e-maila oraz brak dostępu po wylogowaniu.

**Warunek ukończenia:** pełny przepływ rejestracja → logowanie → chroniony widok → wylogowanie działa na danych PostgreSQL.

## 4. Biblioteka i światy

Zależność: etap 3.

Przed pierwszym zapisem świata w 4.2 przygotuj model historii z 4.9 i wspólny zapis z 4.10. Kolejne moduły mają korzystać z niego od pierwszej operacji zmieniającej dane; historia należy do podstawowego MVP etapów 1–9.

- [ ] 4.1 Dodaj świat, członkostwo i role `creator`, `owner`, `editor`, `reader`, `custom`; zaktualizuj ERD. Stosuj stałe liczbowe ID; karty mają dodatkowo osobny niezmienny typ. Każdy świat ma dokładnie jednego Twórcę i może mieć wielu Właścicieli.
- [ ] 4.2 Utwórz prywatny świat wraz z członkostwem Twórcy i pierwszym zestawem historii zmian w jednym logicznym zapisie.
- [ ] 4.3 Dodaj bibliotekę własnych i udostępnionych światów oraz stan pustej biblioteki.
- [ ] 4.4 Dodaj otwieranie projektu i zakładkę O świecie.
- [ ] 4.5 Dodaj edycję informacji i treści „O świecie” zgodnie z osobnymi uprawnieniami do treści oraz ustawień projektu.
- [ ] 4.6 Dodaj nadawanie dostępu istniejącym użytkownikom, zmianę roli i odebranie dostępu zgodnie z macierzą. Tylko Twórca może nadać rolę Właściciela; zwykłe zarządzanie członkami nie może zmienić ani usunąć Twórcy.
- [ ] 4.7 Dodaj usuwanie świata wyłącznie przez Twórcę, z potwierdzeniem i wpisem historii. Wycofaj bieżący świat ze zwykłego dostępu, zachowując wszystkie skutecznie zapisane dane, historię i wersje plików na dyskach bez automatycznego czyszczenia; zachowanie danych nie nadaje nowego dostępu do archiwum. Zależności obsłuż zgodnie z zatwierdzoną polityką 0.8.
- [ ] 4.8 Zweryfikuj role na oddzielnych kontach i bezpośrednich żądaniach HTTP: ochronę Twórcy, zakaz nadawania roli Właściciela przez inne osoby oraz brak samodzielnego rozszerzania uprawnień. Redaktor może usuwać także cudzą zawartość; każda taka zmiana podlega historii.
- [ ] 4.9 Dodaj model historii: `ChangeSet` grupujący jedną operację jak commit oraz wpisy jej zmian. Zapisuj świat, identyfikator i poprzednią rewizję, wykonawcę, rzeczywisty czas UTC, operację, zmienione obiekty/pliki oraz stan przed/po lub różnicę pozwalającą odtworzyć obie wersje.
- [ ] 4.10 Połącz każdą mutację świata z dopisaniem niezmiennej historii. Błąd zapisu historii ma wycofać zmianę. Dla tej samej wersji obiektu wygrywa pierwszy poprawnie zatwierdzony zapis; zapis nieaktualnej rewizji odrzuć jako konflikt 409 bez nadpisania, automatycznego scalania ani nowej rewizji skutecznej zmiany. Zachowaj niezapisany tekst do porównania i świadomego ponowienia. Niezależne obiekty mogą być zmieniane kolejno w liniowej historii świata. Obejmij członkostwo, role, Custom, ustawienia, prywatność i transfer Twórcy; kolejne moduły dołączają swoje dane do tego samego mechanizmu. Nie zapisuj haseł, tokenów, cookies ani sekretów środowiska.
- [ ] 4.11 Dodaj przegląd zestawów zmian oraz odczyt i porównanie historycznych wersji. Domyślne `history.read` mają Twórca, Właściciel i Redaktor; Custom może otrzymać je jawnie. Gość i Czytelnik nie mają go domyślnie. Obecne prawa do danych źródłowych nadal obowiązują, a historia ustawień i członkostwa wymaga odpowiednich praw administracyjnych. Nie udostępniaj operacji edycji lub usuwania wpisów historii.
- [ ] 4.12 Dodaj konfigurację Custom jako dodatnie uprawnienia obszar–operacja, bez jawnych zakazów i reguł dla pojedynczych kart. Uwzględnij minimum odczytu wynikające z prywatności, wymagane uprawnienia zależne i zakres administracyjny nadającego; uprawnienia zastrzeżone dla Twórcy nie podlegają delegowaniu.
- [ ] 4.13 Dodaj atomowe przekazanie roli Twórcy istniejącemu Właścicielowi wyłącznie przez obecnego Twórcę. Poprzedni Twórca zostaje Właścicielem; zachowaj historyczną informację o pierwotnym twórcy i zapisz transfer w historii.
- [ ] 4.14 Dodaj komentarze do świata i jego dostępnej zawartości: odczyt, dodawanie po zalogowaniu, edycję i usuwanie własnych komentarzy oraz moderację przez Właściciela i Twórcę lub Custom z odpowiednim uprawnieniem. Sprawdzaj dostęp do kontekstu komentarza i zapisuj utworzenie, edycję oraz usunięcie w historii.
- [ ] 4.15 Sprawdź kompletność historii, porównanie wersji, brak mutacji po awarii jej zapisu i ochronę wpisów. Sprawdź konflikt dwóch zapisów tej samej rewizji: pierwszy poprawny zapis pozostaje, drugi otrzymuje 409 i zachowuje roboczą treść; zapisy różnych obiektów nie tracą historii. Zweryfikuj własne/cudze komentarze, brak sekretów oraz odebranie praw także do historii i jej historycznych plików.

**Warunek ukończenia:** użytkownicy widzą właściwe projekty i mają możliwości zgodne z macierzą, w tym Custom i komentarze. Twórca pozostaje chroniony, jego transfer działa atomowo, a jedynie on nadaje rolę Właściciela i usuwa świat. Każda udana mutacja ma niezmienny zestaw historii z porównaniem wersji i osobną kontrolą dostępu. Projekty na tym etapie są jeszcze prywatne; trzy poziomy udostępnienia wdraża etap 8.

## 5. Karty elementów świata

Zależność: etap 4.

Użytkownik sprawdził i zatwierdził format pól na podstawie `myra.txt` w 0.8; opisuje go PROJECT.md. Opisy są źródłem Markdown w rekordach PostgreSQL; odwołania do plików służą ilustracjom i załącznikom. Nie dodawaj pól zależnych od typu ani dodatkowego unikalnego `fate_name` poza zatwierdzonym formatem.

- [ ] 5.1 Dodaj wspólny model karty należącej do świata; zacznij od postaci.
- [ ] 5.2 Dodaj listę, szczegóły, tworzenie i edycję karty.
- [ ] 5.3 Dodaj zatwierdzone pola wspólne: wymaganą nieunikalną nazwę oraz opcjonalne krótki opis, charakterystykę, opis fabularny, listę aliasów z kontekstem i listę celów. Rewizja jest metadanymi systemowymi; nie jest polem edytowanym przez autora.
- [ ] 5.4 Rozszerz model o miejsca, frakcje, rasy/gatunki i artefakty współdzielące zatwierdzony format karty, bez osobnych zestawów dodatkowych pól na tym etapie.
- [ ] 5.5 Dodaj tagi, wyszukiwanie, filtrowanie i stronicowanie.
- [ ] 5.6 Dodaj wycofywanie karty z bieżących widoków, usuwanie jej aktywnych relacji i wpływów przy obu końcach oraz odłączanie uczestnictwa i wskazania miejsca, bez usuwania innych kart i wydarzeń. Jej komentarze i ilustracje usuń z bieżących widoków kontekstu; wszystkie wcześniejsze dane i pliki zachowaj. Sprawdź uprawnienia do wszystkich skutków i zapisz je w jednym zestawie historii.
- [ ] 5.7 Dodaj upload ilustracji: limit rozmiaru, rzeczywisty MIME, dozwolone rozszerzenie i unikalna nazwa. Zamiana lub usunięcie ilustracji ma zachować jej poprzednie bajty i metadane do odczytu historycznej wersji, a nie tylko nazwę lub hash.
- [ ] 5.8 Zapewnij kontrolę dostępu do bieżących i historycznych ilustracji zgodnie z prywatnością, uprawnieniami i kontekstem. W bazie przechowuj odwołania do niezmiennych wersji plików; zapis metadanych i historii oraz publikacja przygotowanych plików muszą dawać jeden logiczny wynik także przy awarii.
- [ ] 5.9 Wykorzystaj mechanizm ilustracji również dla okładki świata.
- [ ] 5.10 Sprawdź CRUD, walidację, izolację kart różnych światów oraz historię wszystkich zmian kart i plików, także cudzej zawartości usuwanej przez Redaktora. Zweryfikuj stare wersje po zamianie lub usunięciu ilustracji i awarię zapisu pliku/historii.
- [ ] 5.11 Egzekwuj niezmienność ID i typu istniejącej karty oraz brak ponownego użycia ID. Do przedstawienia tego samego bytu w innym typie powstaje nowa karta; powiązanie z poprzednią wdraża 6.10 według zatwierdzonych reguł 0.8.
- [ ] 5.12 Dodaj bezpieczne renderowanie Markdown bez wykonywalnego HTML lub skryptów i obejmij źródłowy tekst historią. Sprawdź, że odrzucony zapis nieaktualnej rewizji zachowuje treść autora, a zmiana opisu nie zmienia ID ani typu karty.

**Warunek ukończenia:** działa encyklopedia świata bez konieczności używania grafu. Relacji takich jak przynależność do frakcji nie duplikujemy w osobnych polach kart.

## 6. Relacje

Zależność: etap 5.

- [ ] 6.1 Wdroż zatwierdzony w 0.8 katalog rodzajów relacji: należy do, jest przedstawicielem, znajduje się w, jest częścią, sojusz, wrogość i ten sam byt. Egzekwuj dozwolone końce, kierunek, brak połączenia rekordu z samym sobą i duplikaty według PROJECT.md. `same_as = 10` to kod rodzaju, nie waga; użytkownik już potwierdził te reguły.
- [ ] 6.2 Dodaj model: źródło, cel, typ, kierunek/wzajemność, opis i świat.
- [ ] 6.3 Dodaj tworzenie relacji z poziomu karty.
- [ ] 6.4 Wyświetlaj relacje przy obu końcach i umożliwiaj przejście do powiązanej karty.
- [ ] 6.5 Dodaj edycję i usuwanie relacji. Wycofanie jednego powiązania usuwa je z bieżących widoków obu końców i grafu, bez usuwania kart końcowych; zapis historyczny pozostaje.
- [ ] 6.6 Egzekwuj izolację światów w backendzie i odpowiednich ograniczeniach bazy.
- [ ] 6.7 Dodaj Fetch GET i POST z JSON, obsługą statusów, ładowania, powodzenia i błędów.
- [ ] 6.8 Sprawdź nieuprawnione operacje, nieistniejące karty, próby łączenia światów oraz historię tworzenia, edycji i usuwania relacji.
- [ ] 6.9 Dodaj osobny model i CRUD wpisów wpływu: świat, ID, rewizja, źródłowa i docelowa karta postaci oraz opis działania/kontekstu. Dopuść kilka wpisów A→B; egzekwuj odrębne uprawnienia `influences` oraz historię. Nie zapisuj wpływów jako zwykłego rodzaju relacji i nie narzucaj wag liczbowych.
- [ ] 6.10 Dodaj utworzenie nowej karty innego typu i powiązania `same_as` (kod rodzaju 10) z poprzednią jako jeden zestaw zmian. Zachowaj poprzednie ID i typ, bez automatycznego scalenia kart lub przenoszenia innych relacji; wymagaj uprawnień do wszystkich zmienianych danych.

**Warunek ukończenia:** relacje i osobne wpisy wpływu działają jako listy na prawdziwych danych i można je tworzyć bez przeładowania strony. Działa powiązanie `same_as` (kod 10) pomiędzy nową kartą innego typu a poprzednią, z zachowaniem odrębnych ID i historii. Uniwersalne wagi 0–10 pozostają poza MVP.

## 7. Interaktywny graf

Zależność: etap 6 oraz rozstrzygnięcie 0.11.

- [ ] 7.1 Zapisz wybór technologii renderowania i jej ograniczenia.
- [ ] 7.2 Dodaj endpoint węzłów i krawędzi z kontrolą dostępu oraz ograniczeniem wielkości odpowiedzi. Uprawnienie do grafu nie może ujawniać kart, relacji, wpływów ani wydarzeń, do których użytkownik nie ma odczytu.
- [ ] 7.3 Wyświetl graf istniejących kart i relacji w widokach Relacje, Lokacje i Los — wpływ postaci (FATE_GRAPH).
- [ ] 7.4 Dodaj przesuwanie i przybliżanie.
- [ ] 7.5 Otwieraj kartę po zaznaczeniu węzła i opis relacji po wybraniu krawędzi.
- [ ] 7.6 Dodaj filtry typów, wyszukiwanie i widok bezpośrednich sąsiadów karty.
- [ ] 7.7 Zapewnij użyteczny wariant mobilny i alternatywną listę obsługiwaną klawiaturą.
- [ ] 7.8 Sprawdź pusty graf, pojedynczą kartę i reprezentatywny większy zbiór w każdym widoku; w FATE_GRAPH sprawdź także kierunek i opis wpływu postaci na inne postacie i ich działania.

**Warunek ukończenia:** widoki Relacje, Lokacje i Los — wpływ postaci (FATE_GRAPH) są interaktywnymi widokami danych świata. Grafy i encyklopedia korzystają z tych samych kart i powiązań. FATE_GRAPH przedstawia kierunkowy wpływ postaci na inne postacie i ich działania; jest częścią podstawowego MVP etapów 1–9, niezależną od analizy spójności w etapie 10. Tworzenie połączeń przeciąganiem i zapis własnych układów nie należą do tego etapu.

## 8. Prywatność, udostępnianie i Odkrywaj

Zależność: etap 7.

- [ ] 8.1 Dodaj poziomy `public`, `restricted`, `private` oraz ich zmianę zgodnie z `world.visibility.update`; nowy świat pozostaje domyślnie prywatny. Zmiana prywatności podlega historii i nie nadaje członkostwa ani prawa edycji.
- [ ] 8.2 Utwórz na stronie głównej katalog rzeczywistych publicznych światów: okładka, tytuł, autor, opis, tagi.
- [ ] 8.3 Dodaj wyszukiwanie, sortowanie, filtrowanie po tagach i stronicowanie katalogu.
- [ ] 8.4 Udostępnij Gościowi pełny odczyt aktualnej zawartości świata publicznego, wyłącznie „O świecie” świata ograniczonego i brak dostępu do świata prywatnego. Uwzględnij konta bez członkostwa; historia i ustawienia administracyjne nie należą do publicznego odczytu.
- [ ] 8.5 Sprawdź brak edycji świata przez Gościa i komentowanie publicznego świata wyłącznie po zalogowaniu, bez wymogu członkostwa. Zweryfikuj minimum publicznego odczytu dla Custom i zmianę efektywnego dostępu po cofnięciu członkostwa.
- [ ] 8.6 Sprawdź brak chronionych danych w katalogu, API, wyszukiwaniu, grafach, komentarzach i pod bezpośrednimi adresami bieżących oraz historycznych plików przy wszystkich trzech poziomach prywatności, także po ich zmianie.

**Kamień milowy A:** pełny proces konto → świat → współpraca i komentarze → karty → relacje i wpływ postaci → graf → udostępnienie → odczyt przez Gościa działa od początku do końca. Trzy poziomy prywatności i ograniczenia ról są egzekwowane w backendzie, a wszystkie zmiany danych i plików mają dostępną uprawnionym osobom historię wersji. Pełne MVP etapów 1–9 wymaga również wydarzeń i chronologii.

## 9. Wydarzenia i chronologia

Zależność: etap 8.

- [ ] 9.1 Zatwierdź prosty model czasu fikcyjnego, nieznanych dat, przedziałów i porównywania czasu; nie zakładaj kalendarza gregoriańskiego.
- [ ] 9.2 Dodaj tworzenie, szczegóły, edycję i wycofanie wydarzenia z bieżących widoków zgodnie z 0.8. Wycofaj jego uczestnictwa, miejsca i zależności, zachowując karty, pozostałe wydarzenia i wszystkie historyczne dane oraz pliki.
- [ ] 9.3 Dodaj uczestników, ich role i miejsca; zapisuj dane oraz odpowiadający im zestaw historii transakcyjnie jako jedną operację.
- [ ] 9.4 Dodaj opis przebiegu, skutków i status opracowania wpisu niezależny od czasu wydarzenia.
- [ ] 9.5 Dodaj listę chronologiczną i czytelną prezentację nieznanego czasu.
- [ ] 9.6 Pokazuj wydarzenia na kartach uczestników i miejsc.
- [ ] 9.7 Dodaj zależności: przed, doprowadziło do, jest częścią.
- [ ] 9.8 Dodaj prezentację wydarzeń jako węzłów grafu bez kopiowania ich do tabeli kart; ten widok należy do podstawowego MVP.
- [ ] 9.9 Dodaj okres obowiązywania relacji między kartami i daty życia postaci potrzebne do reguł spójności.
- [ ] 9.10 Sprawdź niepełne daty, porządek wydarzeń, uprawnienia, odczyt zależny od prywatności oraz historię wydarzeń, statusów, uczestników, miejsc i zależności. `CURRENT_EVENT` korzysta z tej samej historii istniejącego wydarzenia.

**Warunek ukończenia podstawowego MVP etapów 1–9:** wydarzenia są powiązane z kartami i tworzą czytelną chronologię oraz graf zależności. Działają wszystkie zatwierdzone role, Custom, komentarze, prywatność i kompletna historia zmian danych oraz plików świata z porównaniem wersji. Rozbudowana graficzna oś czasu i własne kalendarze są poza pierwszą wersją; kontrola spójności należy do extended MVP w etapie 10.

## 10. Kontrola spójności

Zależność: etap 9.

- [ ] 10.1 Zdefiniuj 2–3 reguły wraz z zachowaniem dla brakujących i niepewnych danych.
- [ ] 10.2 Dodaj regułę uczestnictwa przed narodzinami i po śmierci, jeśli dane umożliwiają porównanie.
- [ ] 10.3 Dodaj wykrywanie cyklu w zależnościach wydarzeń typu przed.
- [ ] 10.4 Utwórz izolowane testy reguł: poprawne dane, konflikt, granice i brak danych.
- [ ] 10.5 Dodaj listę ostrzeżeń z przyczyną i odnośnikami do obiektów.
- [ ] 10.6 Dodaj świadome uzasadnienie wyjątku bez zmiany faktów źródłowych.
- [ ] 10.7 Ustal ponowną ocenę ostrzeżeń i wyjątków po edycji danych, aby nie pozostawiać nieaktualnych wyników.

**Warunek ukończenia:** użytkownik rozumie ostrzeżenie i może poprawić dane lub uzasadnić wyjątek. Walidator nie naprawia historii samodzielnie; brak daty nie jest błędem.

## 11. Zaproszenia, poczta i CRON

Zależność: etap 10; rozszerza członkostwo z etapu 4.

- [ ] 11.1 Dodaj zaproszenie e-mail z rolą, bezpiecznym tokenem i terminem ważności. Nadanie Właściciela przez zaproszenie może autoryzować tylko Twórca; zaproszenie nie zastępuje osobnej operacji przekazania roli Twórcy.
- [ ] 11.2 Dodaj przyjęcie zaproszenia po zalogowaniu na właściwe konto; token jest jednorazowy. Ponownie sprawdź aktualne uprawnienia wystawcy i odbiorcy oraz zapisz zmianę członkostwa w historii.
- [ ] 11.3 Dodaj konfigurację SMTP przez środowisko i Mailpit do lokalnego demo.
- [ ] 11.4 Wydziel wysyłkę od kontrolera i obsłuż awarię bez utraty danych ani ujawniania szczegółów SMTP.
- [ ] 11.5 Dodaj idempotentny CRON czyszczący wygasłe zaproszenia.
- [ ] 11.6 Udokumentuj harmonogram, polecenie, logowanie wyniku i test ręczny.
- [ ] 11.7 Sprawdź poprawne, wygasłe, wykorzystane i skierowane do innego konta zaproszenie oraz awarię poczty.

**Warunek ukończenia:** zaproszenie prowadzi do nadania właściwego dostępu, a poczta i CRON mają działający scenariusz demonstracyjny.

## 12. Finalizacja i wdrożenie

Zależność: etapy 0–11.

- [ ] 12.1 Przejdź główny proces na kontach o różnych rolach, z Custom i jako Gość przy trzech poziomach prywatności; zweryfikuj komentarze, transfer Twórcy oraz historię i porównanie wersji danych/plików.
- [ ] 12.2 Wykonaj końcowy przegląd autoryzacji, izolacji światów, CSRF, XSS, SQL Injection, sesji i uploadów.
- [ ] 12.3 Zweryfikuj minimum 5 istotnych widoków na telefonie, tablecie i komputerze.
- [ ] 12.4 Sprawdź klawiaturę, kontrast i oba motywy; przygotuj raport WCAG z datą, wynikami i naprawami.
- [ ] 12.5 Uzupełnij PL/EN, w tym błędy i walidację.
- [ ] 12.6 Umieść aktualny ERD Mermaid bezpośrednio w README.
- [ ] 12.7 Przygotuj eksport SQL ze schematem i bezpiecznymi danymi demo; sprawdź import do pustej bazy.
- [ ] 12.8 Uzupełnij README: autorzy, funkcje, architektura, routing, konfiguracja, testy, konta demo i zrzuty minimum 5 mobilnych oraz 5 desktopowych ekranów.
- [ ] 12.9 Dodaj tabelę dowodów Security Bingo oraz instrukcje poczty i CRON.
- [ ] 12.10 Przygotuj konfigurację produkcyjną, trwałość bazy i uploadów oraz bezpieczne sekrety.
- [ ] 12.11 Wdróż aplikację pod publicznym adresem; sprawdź zgodność z main i uzupełnij link w README.
- [ ] 12.12 Uruchom projekt z czystego klonu wyłącznie według README i wykonaj udokumentowane testy.
- [ ] 12.13 Przygotuj scenariusz prezentacji i wyjaśnienie przepływu żądania od interfejsu do bazy i odpowiedzi.

**Kamień milowy B:** działająca, udokumentowana aplikacja z odtwarzalnym środowiskiem i dowodami realizacji kryteriów. Nie deklaruj punktów ani zaliczenia wyłącznie na podstawie tej checklisty.

## Poza pierwszą wersją

Poniższa lista nie stanowi zlecenia implementacji:

- sceny i dialogi MYRA;
- uniwersalne wagi relacji 0–10;
- mapa z terenem, odległościami i kosztami podróży;
- zaawansowane kalendarze fikcyjne;
- automatyczne przywracanie poprzedniego stanu, gałęzie i scalanie wersji (zapis pełnej historii zmian oraz odczyt i porównanie wersji należą już do MVP);
- prywatność pojedynczych kart i ukrywanie spoilerów;
- współpraca w czasie rzeczywistym;
- AI i automatyczne generowanie fikcji;
- tworzenie relacji przeciąganiem, zapis wielu układów grafu i rozbudowana graficzna oś czasu.

## Szablon pojedynczego zadania dla agenta

- **Zadanie:** numer z TODO i konkretny rezultat.
- **Kontekst:** aktualny stan oraz dokumenty do przeczytania.
- **Zakres:** funkcje i przewidywane pliki.
- **Ograniczenia:** czego nie rozszerzać; zatwierdzone technologie.
- **Kryteria akceptacji:** obserwowalne zachowanie, również błędy i uprawnienia.
- **Weryfikacja:** odpowiednie testy, kontrola HTTP lub przeglądarka.
- **Raport:** zmiany, wyniki sprawdzeń, ograniczenia i aktualizacja checklisty.

## Źródła

- Repozytorium: https://github.com/PaurXen/WorldGraph/tree/Initial-setup
- Laboratoria i kryteria: https://ii.pk.edu.pl/~awidlak/subject/16
- Wykład CSS: https://www.youtube.com/watch?v=BpVcMmMZudY
- Pozycjonowanie i responsywność: https://www.youtube.com/watch?v=X-XylbveSQE
- Szkic użytkownika: data infr draft.txt (CLFR/MYRA).
- Ustalenia planistyczne tej rozmowy. Decyzje oznaczone jako robocze należy zatwierdzić przed zależną implementacją.
