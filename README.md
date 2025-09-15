# Split – Gestione Spese Condivise

Applicazione web in **PHP + MySQL** che permette di:
- Inserire spese condivise tra più utenti.
- Decidere con quali partecipanti dividere ogni spesa.
- Calcolare automaticamente rimborsi e bilanci.
- Gestire notifiche per gli utenti.

## 🚀 Funzionalità principali
- Registrazione e login con sessioni.
- Aggiunta di spese con:
  - Nome spesa
  - Costo
  - Tipologia (Casa, Cibo, Attività, Altro)
  - Partecipanti (selezionabili tra tutti gli utenti, pre-selezionati di default)
- Notifiche automatiche agli altri utenti quando viene aggiunta una nuova spesa.
- Calcolo dei rimborsi: mostra chi deve dare soldi a chi.

## 📋 Requisiti
- PHP >= 7.4
- MySQL/MariaDB
- Estensioni PDO attive
- Composer (opzionale, se aggiungi librerie esterne)

## ⚙️ Installazione
1. Clona la repository:
   ```bash
   git clone https://github.com/tuo-user/split.git
   cd split
