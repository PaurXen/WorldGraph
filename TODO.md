# TODO — WorldGraph

Plan implementacji, wersja 2026-11-06.

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

Podstawowy proces: konto → prywatny świat → karty → relacje → graf → publikacja → przeglądanie przez gościa. Następnie wydarzenia, kontrola spójności i zaproszenia.

Nowy projekt jest prywatny. W pierwszej wersji publikacja obejmuje całą zawartość świata. Role w świecie: właściciel, redaktor, czytelnik. Szczegóły modelu pozostają do zatwierdzenia w etapie 0.

Punkt odniesienia dla stanu początkowego: przegląd gałęzi Initial-setup z 2026-11-06. Istnieją dokumentacja, szkielet katalogów, konfiguracja Docker Compose i statyczna strona HTML/CSS. Nie traktuj tego opisu jako bieżącej weryfikacji repozytorium; przed edycją sprawdź aktualne pliki. Poniższe checkboxy dotyczą zaplanowanych prac i nie oznaczają, że wszystkie istniejące elementy trzeba budować ponownie.

## 0. Ustalenia i dokumentacja

- [x] 0.1 Popraw kryterium nr 14 w docs/REQUIREMENTS.md: Python → JavaScript; zachowaj odnośnik do źródła.
- [x] 0.2 Zatwierdź MVP i listę funkcji odłożonych; zaktualizuj PROJECT.md.
- [x] 0.3 Ustal strony: Odkrywaj, logowanie, rejestracja, biblioteka, otwarty projekt, ustawienia projektu i konta. Ustalenia: [PROJECT.md — Strony i nawigacja](PROJECT.md#strony-i-nawigacja).
- [ ] 0.4 Ustal zakładki projektu: O świecie, Graf, Karty, Wydarzenia, Spójność.
- [ ] 0.5 ~~Przygotuj prototyp minimum 5 istotnych ekranów w wariancie mobilnym i desktopowym oraz eksport PDF.~~
- [ ] 0.6 Ustal model pojęciowy: User, World, WorldMember, Entity, Relationship, Event i uczestnicy wydarzenia.
- [ ] 0.7 Rozpisz macierz uprawnień gościa, właściciela, redaktora i czytelnika.
- [ ] 0.8 Ustal pola kart, początkowe typy relacji i zasady usuwania powiązanych danych.
- [ ] 0.9 Uzupełnij AGENTS.md o zasady Git, granice warstw, weryfikację i warunki ukończenia zadania.
- [ ] 0.10 Rozszerz checklistę kryteriów o warunki akceptacji, status i dowód realizacji.
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

- [ ] 4.1 Dodaj świat i członkostwo z rolą; zaktualizuj ERD.
- [ ] 4.2 Utwórz świat wraz z członkostwem właściciela w jednej transakcji.
- [ ] 4.3 Dodaj bibliotekę własnych i udostępnionych światów oraz stan pustej biblioteki.
- [ ] 4.4 Dodaj otwieranie projektu i zakładkę O świecie.
- [ ] 4.5 Dodaj edycję nazwy, opisu, tematów i założeń świata.
- [ ] 4.6 Dodaj nadawanie dostępu istniejącym użytkownikom, zmianę roli i odebranie dostępu przez właściciela.
- [ ] 4.7 Dodaj kontrolowane usuwanie świata z potwierdzeniem i ustaloną polityką zależnych danych.
- [ ] 4.8 Zweryfikuj role na oddzielnych kontach i bezpośrednich żądaniach HTTP; chroń rolę właściciela.

**Warunek ukończenia:** użytkownicy widzą właściwe projekty i mają różne możliwości zgodnie z rolą. Projekty nadal są prywatne.

## 5. Karty elementów świata

Zależność: etap 4.

- [ ] 5.1 Dodaj wspólny model karty należącej do świata; zacznij od postaci.
- [ ] 5.2 Dodaj listę, szczegóły, tworzenie i edycję karty.
- [ ] 5.3 Dodaj pola wspólne: nazwa, krótki opis, charakterystyka, historia/opis szczegółowy i aliasy.
- [ ] 5.4 Rozszerz model o miejsca, frakcje, rasy/gatunki i artefakty oraz ich zatwierdzone pola.
- [ ] 5.5 Dodaj tagi, wyszukiwanie, filtrowanie i stronicowanie.
- [ ] 5.6 Dodaj usuwanie karty zgodne z ustaloną polityką zależności.
- [ ] 5.7 Dodaj upload ilustracji: limit rozmiaru, rzeczywisty MIME, dozwolone rozszerzenie i unikalna nazwa.
- [ ] 5.8 Zapewnij kontrolę dostępu do ilustracji prywatnego świata; w bazie przechowuj ścieżkę, nie plik.
- [ ] 5.9 Wykorzystaj mechanizm ilustracji również dla okładki świata.
- [ ] 5.10 Sprawdź CRUD, walidację i izolację kart różnych światów.

**Warunek ukończenia:** działa encyklopedia świata bez konieczności używania grafu. Relacji takich jak przynależność do frakcji nie duplikujemy w osobnych polach kart.

## 6. Relacje

Zależność: etap 5.

- [ ] 6.1 Zatwierdź początkowe typy relacji, dopuszczalne typy końców, kierunek, duplikaty i połączenia do samego siebie.
- [ ] 6.2 Dodaj model: źródło, cel, typ, kierunek/wzajemność, opis i świat.
- [ ] 6.3 Dodaj tworzenie relacji z poziomu karty.
- [ ] 6.4 Wyświetlaj relacje przy obu końcach i umożliwiaj przejście do powiązanej karty.
- [ ] 6.5 Dodaj edycję i usuwanie relacji.
- [ ] 6.6 Egzekwuj izolację światów w backendzie i odpowiednich ograniczeniach bazy.
- [ ] 6.7 Dodaj Fetch GET i POST z JSON, obsługą statusów, ładowania, powodzenia i błędów.
- [ ] 6.8 Sprawdź nieuprawnione operacje, nieistniejące karty i próby łączenia światów.

**Warunek ukończenia:** relacje działają jako lista na prawdziwych danych i można je tworzyć bez przeładowania strony. Uniwersalne wagi 0–10 pozostają poza MVP.

## 7. Interaktywny graf

Zależność: etap 6 oraz rozstrzygnięcie 0.11.

- [ ] 7.1 Zapisz wybór technologii renderowania i jej ograniczenia.
- [ ] 7.2 Dodaj endpoint węzłów i krawędzi z kontrolą dostępu oraz ograniczeniem wielkości odpowiedzi.
- [ ] 7.3 Wyświetl graf istniejących kart i relacji.
- [ ] 7.4 Dodaj przesuwanie i przybliżanie.
- [ ] 7.5 Otwieraj kartę po zaznaczeniu węzła i opis relacji po wybraniu krawędzi.
- [ ] 7.6 Dodaj filtry typów, wyszukiwanie i widok bezpośrednich sąsiadów karty.
- [ ] 7.7 Zapewnij użyteczny wariant mobilny i alternatywną listę obsługiwaną klawiaturą.
- [ ] 7.8 Sprawdź pusty graf, pojedynczą kartę i reprezentatywny większy zbiór.

**Warunek ukończenia:** graf jest interaktywnym widokiem tych samych danych co encyklopedia. Tworzenie połączeń przeciąganiem i zapis własnych układów nie należą do tego etapu.

## 8. Publiczne światy i Odkrywaj

Zależność: etap 7.

- [ ] 8.1 Dodaj publikację i cofnięcie publikacji dostępne wyłącznie właścicielowi; wyjaśnij zakres publikacji.
- [ ] 8.2 Utwórz na stronie głównej katalog rzeczywistych publicznych światów: okładka, tytuł, autor, opis, tagi.
- [ ] 8.3 Dodaj wyszukiwanie, sortowanie, filtrowanie po tagach i stronicowanie katalogu.
- [ ] 8.4 Udostępnij gościowi odczyt publicznego projektu, kart, relacji, grafu i ilustracji.
- [ ] 8.5 Sprawdź, że gość nie może edytować projektu.
- [ ] 8.6 Sprawdź brak prywatnych danych w katalogu, API, wyszukiwaniu i pod bezpośrednimi adresami plików, również po cofnięciu publikacji.

**Kamień milowy A:** pełny proces konto → świat → karty → relacje → graf → publikacja → odczyt przez gościa działa od początku do końca.

## 9. Wydarzenia i chronologia

Zależność: etap 8.

- [ ] 9.1 Zatwierdź prosty model czasu fikcyjnego, nieznanych dat, przedziałów i porównywania czasu; nie zakładaj kalendarza gregoriańskiego.
- [ ] 9.2 Dodaj tworzenie, szczegóły, edycję i usuwanie wydarzenia.
- [ ] 9.3 Dodaj uczestników, ich role i miejsce; zapisuj zestaw zmian transakcyjnie.
- [ ] 9.4 Dodaj opis przebiegu, skutków i status opracowania wpisu niezależny od czasu wydarzenia.
- [ ] 9.5 Dodaj listę chronologiczną i czytelną prezentację nieznanego czasu.
- [ ] 9.6 Pokazuj wydarzenia na kartach uczestników i miejsc.
- [ ] 9.7 Dodaj zależności: przed, doprowadziło do, jest częścią.
- [ ] 9.8 Dodaj opcjonalną prezentację wydarzeń jako węzłów grafu bez kopiowania ich do tabeli kart.
- [ ] 9.9 Dodaj okres obowiązywania relacji między kartami i daty życia postaci potrzebne do reguł spójności.
- [ ] 9.10 Sprawdź niepełne daty, porządek wydarzeń, uprawnienia i publiczny odczyt.

**Warunek ukończenia:** wydarzenia są powiązane z kartami i tworzą czytelną chronologię. Rozbudowana graficzna oś czasu i własne kalendarze są poza pierwszą wersją.

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

- [ ] 11.1 Dodaj zaproszenie e-mail z rolą, bezpiecznym tokenem i terminem ważności.
- [ ] 11.2 Dodaj przyjęcie zaproszenia po zalogowaniu na właściwe konto; token jest jednorazowy.
- [ ] 11.3 Dodaj konfigurację SMTP przez środowisko i Mailpit do lokalnego demo.
- [ ] 11.4 Wydziel wysyłkę od kontrolera i obsłuż awarię bez utraty danych ani ujawniania szczegółów SMTP.
- [ ] 11.5 Dodaj idempotentny CRON czyszczący wygasłe zaproszenia.
- [ ] 11.6 Udokumentuj harmonogram, polecenie, logowanie wyniku i test ręczny.
- [ ] 11.7 Sprawdź poprawne, wygasłe, wykorzystane i skierowane do innego konta zaproszenie oraz awarię poczty.

**Warunek ukończenia:** zaproszenie prowadzi do nadania właściwego dostępu, a poczta i CRON mają działający scenariusz demonstracyjny.

## 12. Finalizacja i wdrożenie

Zależność: etapy 0–11.

- [ ] 12.1 Przejdź główny proces na kontach o różnych rolach oraz jako gość.
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
- graf losu i liczbowe miary wpływu;
- mapa z terenem, odległościami i kosztami podróży;
- zaawansowane kalendarze fikcyjne;
- pełna historia wersji i przywracanie danych;
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
