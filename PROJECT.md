# PROJECT.md — WorldGraph

> Dokument zatwierdzonego celu i zakresu MVP w wariancie B, ustalonego w punkcie 0.2 TODO. Opisuje plan produktu; aktualny stan implementacji przedstawia README.md.

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

