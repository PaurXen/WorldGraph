# AGENTS.md — WorldGraph

Wskazówki dla agenta programistycznego pracującego nad tym repozytorium.

## Zakres pracy

- Najpierw zapoznaj się z [PROJECT.md](PROJECT.md), [ARCHITECTURE.md](ARCHITECTURE.md), [TODO.md](TODO.md) i [docs/REQUIREMENTS.md](docs/REQUIREMENTS.md).
- Implementuj wyłącznie funkcjonalność wyraźnie zleconą przez użytkownika. Nie buduj całego projektu na podstawie samej prośby o plan lub strukturę.
- Nie dodawaj zależności, kontenerów, schematów SQL, endpointów ani klas „na zapas”.
- Przed większą zmianą zaproponuj zakres i listę plików, których dotknie.
- Realizuj już zlecony zakres bez ponownego pytania o tę samą zgodę. Rutynowe szczegóły wykonania w ramach zlecenia rozstrzygaj samodzielnie; zmiany zakresu lub zatwierdzonych reguł produktu uzgodnij z użytkownikiem.

## Zasady docelowe

- Backend: obiektowy PHP, rozdzielone `Controller`, `Service`, `Repository`, `Domain`; PostgreSQL jako baza.
- Strony HTML/CSS i JavaScript; Fetch API dla sensownych interakcji.
- W kodzie: typowanie, walidacja, parametryzowane SQL, autoryzacja w backendzie, testy.
- Nazwy klas i metod po angielsku, UI docelowo PL/EN.
- Każda encja, relacja i wydarzenie należy do określonego świata; nie dopuszczaj połączeń między odizolowanymi światami.
- Przy analizie fikcyjnej chronologii nie zakładaj automatycznie kalendarza gregoriańskiego.
- Nie deklaruj kryteriów oceniania jako zaliczonych bez rzeczywistych dowodów.

## Git i ochrona zmian

- Przed edycją sprawdź bieżącą gałąź, `git status` i diff istniejących zmian. Zachowaj zmiany użytkownika; nie nadpisuj ich i nie cofaj niezwiązanych zmian. Nie przełączaj gałęzi bez zlecenia.
- Commit, push, merge i zmiany historii wykonuj zgodnie z autoryzacją użytkownika. Obecnie obowiązuje polecenie bez commitów i push; samo zlecenie edycji plików nie znosi tego ograniczenia.
- Gdy commity zostaną zlecone, twórz małe, logiczne commity. Zgodnie z TODO nie squashuj historii; końcowe połączenie z `main` ma być merge commitem. Nie przepisuj historii użytkownika bez zlecenia.
- Po zmianie przejrzyj końcowy diff i uruchom `git diff --check`; upewnij się, że zmiany dotyczą zleconego zakresu.
- Nie nadpisuj istniejącego `.env`, nie ujawniaj sekretów i nie usuwaj danych ani wolumenów podczas zwykłej weryfikacji.

## Granice warstw

| Warstwa | Odpowiedzialność i granice |
|---|---|
| `Controller` | Parametry i format żądania, wywołanie operacji Service, odpowiedź HTTP. Bez SQL i reguł biznesowych. |
| `Service` | Przypadek użycia, aktualna autoryzacja oraz koordynacja zapisu danych, transakcji i historii zmian. |
| `Repository` | Parametryzowane SQL, pobieranie i zapisywanie danych. Bez obsługi HTTP i renderowania HTML. |
| `Domain` | Reguły modelu i ich walidacja. Bez zależności od HTTP, PDO i szablonów. |
| Widoki i JavaScript | Prezentacja danych i interakcje. Kontrolę dostępu egzekwuje backend; ukrycie przycisku nie zastępuje autoryzacji. |

Przepływ i model opisuje [ARCHITECTURE.md](ARCHITECTURE.md#planowany-przepływ). Źródłem zasad produktu pozostają [uprawnienia 0.7](PROJECT.md#role-prywatność-i-uprawnienia--ustalenia-07) oraz [formaty i reguły danych 0.8](PROJECT.md#dane-i-zapis--ustalenia-08); nie kopiuj całych macierzy do instrukcji pracy. Twórz klasy dopiero przy implementacji zleconego modułu.

## Weryfikacja

Dobieraj sprawdzenia do zmiany i dostępnych narzędzi. Korzystaj z aktualnych poleceń w [README.md](README.md) i istniejącej konfiguracji testów; nie zakładaj, że zaplanowane narzędzie już działa.

| Rodzaj zmiany | Wymagane sprawdzenia w odpowiednim zakresie |
|---|---|
| Dokumentacja | Spójność ustaleń, odnośniki, statusy TODO i `git diff --check`. Nie wymaga uruchamiania aplikacji ani dodawania frameworka testów. |
| PHP i logika | Składnia zmienionych plików oraz testy jednostkowe lub integracyjne sprawdzające zmienione zachowanie, jeśli narzędzia istnieją. |
| Routing i dostęp | Poprawne żądanie, błędne dane, niedozwolone operacje i bezpośrednie adresy, z uwzględnieniem właściwych ról. |
| Dane i historia | Izolacja światów, reguły modelu, wspólny zapis danych i historii oraz odrzucenie nieaktualnej rewizji, jeśli zmiana ich dotyczy. |
| Interfejs | Odpowiednie widoki mobilne i desktopowe, klawiatura, fokus oraz komunikaty błędów dla zmienionego przepływu. |

Nie dodawaj testów odtwarzających samą implementację ani narzędzi na zapas. Jeśli wymagane sprawdzenie jest niedostępne, podaj dokładnie, czego nie sprawdzono, dlaczego oraz jak je wykonać; nie przedstawiaj go jako zaliczonego.

## Warunki ukończenia zadania

- Zrealizowano zlecony zakres i jego warunki akceptacji, bez niezwiązanych funkcji lub zmian.
- Wykonano odpowiednie sprawdzenia i przejrzano diff. Niewykonane kontrole oraz pozostałe ograniczenia są jawnie opisane; nie deklaruj pełnej gotowości bez wymaganej weryfikacji.
- Zaktualizowano potrzebne dokumenty i, przy zmianie modelu implementowanej bazy, ERD. Checkbox w TODO oznaczaj dopiero po wykonaniu i sprawdzeniu zadania.
- Raport końcowy wskazuje zmienione pliki, rezultat, sposób weryfikacji i istotne ograniczenia. Ukończenie dokumentacji nie oznacza wdrożenia opisanej funkcji ani zaliczenia kryterium WdPAI.

## Reguła najważniejsza

**Nie implementuj niczego, czego nie zażądano.** Dokumenty opisują plan, a nie gotowy produkt.
