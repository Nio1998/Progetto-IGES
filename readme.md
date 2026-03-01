# 🎮 Raaf_Gaming: Piattaforma E-commerce per il Mercato Videoludico

[![Status](https://img.shields.io/badge/Status-In%20Migrazione%20a%20Laravel-orange)](https://github.com/Nio1998/Progetto-IGES)
[![Tecnologia Target](https://img.shields.io/badge/Framework-Laravel-red)](https://laravel.com/)
[![Linguaggio](https://img.shields.io/badge/Linguaggio-PHP%208.x-blue)](https://www.php.net/)

## 🚀 Panoramica del Progetto

**Raaf\_Gaming** è una piattaforma di **e-commerce** specializzata nel settore del gaming. Offre un catalogo completo di prodotti, inclusi **videogiochi** (fisici e digitali/DLC), **console** e **abbonamenti** ai servizi di gioco.

Questo progetto rappresenta l'iniziativa di ** Migrazione completa ** del sistema da una piattaforma **Java Legacy** ad un'architettura moderna e scalabile basata su **PHP/Laravel**.

---

## ✨ Motivazione e Obiettivi della Migrazione

La Migrazione è guidata dalla necessità di superare una marcata **obsolescenza tecnologica** e di sfruttare l'opportunità per ottimizzare l'architettura complessiva.

### 🎯 Obiettivi

* **Migrazione Tecnologica:** Passaggio integrale da **Java Legacy** a **PHP/Laravel**. 
* **Ottimizzazione Architetturale:** Evoluzione e miglioramento dell'architettura per una maggiore scalabilità e manutenibilità futura.
* **Modernizzazione:** Adottare un framework di supporto moderno per garantire la sostenibilità del codice.

### 💡 Struttura Logica

Il sistema Legacy, pur soffrendo di obsolescenza, è caratterizzato da una struttura logica interna **coerente e ben ingegnerizzata**. L'obiettivo è trasferire e migliorare questa logica nel nuovo ambiente Laravel.

---

## 🛠️ Stack Tecnologico

Il nuovo sistema Raaf\_Gaming è costruito su uno stack moderno e robusto:

| Componente | Tecnologia | Ruolo |
| :--- | :--- | :--- |
| **Backend** | PHP | Linguaggio di programmazione principale |
| **Framework** | **Laravel**  | Framework per la logica di business
| **Database** | MySQL | Gestione persistente dei dati di catalogo, ordini e utenti |
| **Frontend** | Blade | Interfaccia utente |

---

## 🛒 Funzionalità Core (E-commerce)

Il sistema gestisce l'intero processo di vendita, dal catalogo al post-vendita, e include una componente sociale.

* **Catalogo:** Visualizzazione, ricerca e aggiornamento di Videogiochi, Console e Abbonamenti.
* **Ciclo dell'Ordine:** Gestione completa dell'acquisto, dal carrello al tracking della spedizione.
* **Feedback & Social:** Sistema di **Recensioni** sui prodotti per lo scambio di opinioni tra utenti.

### 👥 Gestione degli Attori

La piattaforma distingue nettamente le funzionalità per quattro tipologie di ruoli:

| Ruolo | Funzionalità Chiave |
| :--- | :--- |
| **Utenti (Visitatori)** | Navigazione catalogo, gestione carrello. |
| **Utenti (Registrati)** | Navigazione catalogo, gestione carrello, acquisto, tracking ordini, gestione profilo/carta fedeltà. |
| **Gestori Backoffice (Magazzino)** | Gestione Logistica, **Restock** (approvvigionamento stock), Inserimento Nuovi Prodotti. |
| **Gestori Backoffice (Ordini)** | Gestione del ciclo di vita degli ordini e gestione delle **Spedizioni**. |

## 🧪 Testing

### Test di Unità (Pest)

I test di unità sono scritti con **Pest** e si avviano tramite Artisan:
```bash
php artisan test
```

### Test di Sistema (Codeception)

I test di sistema (acceptance) sono scritti con **Codeception**. Prima di eseguirli assicurarsi che siano attivi:

1. **Il server Laravel** (`php artisan serve`)
2. **Il database MySQL**
3. **ChromeDriver** — il driver si trova in `tests/resources/driver/`, scegliere l'eseguibile corretto per il proprio sistema operativo (`.exe` per Windows, senza estensione per Mac/Linux) e avviarlo sulla porta `9515`:
```bash
# Windows
tests\resources\driver\chromedriver.exe --port=9515

# Mac / Linux
./tests/resources/driver/chromedriver --port=9515
```

Solo una volta che tutti e tre i servizi sono attivi, avviare i test con:
```bash
vendor\bin\codecept run acceptance
```

> ⚠️ **Nota:** Non tutti i test di accettazione potrebbero essere eseguiti correttamente in sequenza. Alcuni test modificano lo stato del database portandolo in una condizione incompatibile con l'esecuzione dei test successivi. In caso di fallimenti inattesi, è sufficiente ripristinare il database allo stato corretto e rieseguire i test.

## 📊 Code Coverage

### Coverage Test di Unità (Pest)

Per generare il file di coverage XML per i test di unità, è necessario avere **Xdebug** installato (nel nostro caso installato su XAMPP). Avviare il server in modalità coverage e successivamente lanciare i test con il flag apposito:
```bash
php -d xdebug.mode=coverage artisan serve
```
```bash
php artisan test --coverage-clover=nome.xml
```

### Coverage Test di Sistema (Codeception)

Per i test di sistema, avviare il server con Xdebug abilitato al tracciamento delle richieste:
```bash
php -d xdebug.mode=coverage -d xdebug.start_with_request=yes artisan serve
```
```bash
vendor\bin\codecept run acceptance --coverage-xml
```

> ⚠️ **Nota:** Prima di eseguire i test di sistema con coverage, ricordarsi di **decommentare** la riga `include c3.php` in `public/index.php` (viene lasciata commentata in produzione poiché necessaria esclusivamente durante i test).

### Merge e Visualizzazione della Coverage

Sono stati predisposti due script dedicati:
- **Merge XML:** unisce il file di coverage dei test di unità e quello dei test di sistema in un unico XML complessivo, secondo la logica documentata nella documentazione di progetto.
- **Conversione HTML:** converte il file XML risultante in formato HTML per una più agevole lettura e consultazione.