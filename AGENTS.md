# AGENTS.md — WorldGraph

Wskazówki dla agenta programistycznego pracującego nad tym repozytorium.

## Zakres pracy
- Najpierw zapoznaj się z `PROJECT.md`, `ARCHITECTURE.md` i `docs/REQUIREMENTS.md`.
- Implementuj wyłącznie funkcjonalność wyraźnie zleconą przez użytkownika. Nie buduj całego projektu na podstawie samej prośby o plan lub strukturę.
- Nie dodawaj zależności, kontenerów, schematów SQL, endpointów ani klas „na zapas”.
- Przed większą zmianą zaproponuj zakres i listę plików, których dotknie.

## Zasady docelowe
- Backend: obiektowy PHP, rozdzielone `Controller`, `Service`, `Repository`, `Domain`; PostgreSQL jako baza.
- Strony HTML/CSS i JavaScript; Fetch API dla sensownych interakcji.
- W kodzie: typowanie, walidacja, parametryzowane SQL, autoryzacja w backendzie, testy.
- Nazwy klas i metod po angielsku, UI docelowo PL/EN.
- Każda encja, relacja i wydarzenie należy do określonego świata; nie dopuszczaj połączeń między odizolowanymi światami.
- Przy analizie fikcyjnej chronologii nie zakładaj automatycznie kalendarza gregoriańskiego.
- Nie deklaruj kryteriów oceniania jako zaliczonych bez rzeczywistych dowodów.

## Reguła najważniejsza
**Nie implementuj niczego, czego nie zażądano.** Dokumenty opisują plan, a nie gotowy produkt.
