# Guida Configurazione OAuth2 (SMTP) - SPARKS
*Aggiornamento: Febbraio 2025*

Questa guida spiega come ottenere il **Client ID** e il **Client Secret** per Microsoft e Google, necessari per l'autenticazione OAuth2 in SPARKS.

---

## ☁️ 1. Microsoft (Azure / Entra ID)

Microsoft ha dismesso la Basic Auth per Outlook e Microsoft 365. Segui questi passi per registrare SPARKS.

### Passi nel Portale Azure (Entra ID):
1.  **Accesso**: Vai su [portal.azure.com](https://portal.azure.com) ed entra con un account amministratore.
2.  **Registrazione App**:
    *   Cerca **"Microsoft Entra ID"** (precedentemente Azure Active Directory).
    *   Vai su **"Registrazioni app"** > **"Nuova registrazione"**.
    *   **Nome**: SPARKS (o un nome a tua scelta).
    *   **Tipi di account supportati**: Scegli "Account in qualsiasi directory organizzativa" (se vuoi supportare più tenant) o "Solo in questa directory".
    *   **URI di reindirizzamento**: Seleziona **Web** e inserisci l'indirizzo esatto che vedi in SPARKS:
        `https://amatucci.marco89.win/sparks/admin/smtp/oauth-callback`
    *   Clicca su **Registra**.
3.  **Ottieni ID**: Copia l'**ID applicazione (client)**. Questo è il tuo **Client ID**.
4.  **Crea Segreto**:
    *   Vai su **"Certificati e segreti"** > **"Nuovo segreto client"**.
    *   Descrizione: `SPARKS SMTP Key`.
    *   Scadenza: Inserisci la massima disponibile (solitamente 24 mesi).
    *   **IMPORTANTE**: Copia il **Valore** (non l'ID del segreto) appena lo crei. Scomparirà per sempre dopo la chiusura della pagina. Questo è il tuo **Client Secret**.
5.  **Permessi API**:
    *   Vai su **"Autorizzazioni API"** > **"Aggiungi un'autorizzazione"**.
    *   Scegli **Microsoft Graph**.
    *   Seleziona **"Autorizzazioni delegate"**.
    *   Cerca e aggiungi: `SMTP.Send`, `Mail.Send`, `offline_access`, `openid`, `profile`, `email`.
    *   Clicca su **"Concedi consenso amministratore per... "** (se sei admin).

### In SPARKS:
- **Provider**: Microsoft
- **Client ID**: Incolla l'ID Applicazione.
- **Client Secret**: Incolla il Valore del segreto.

---

## 🔍 2. Google (Gmail / Workspace)

Google richiede l'abilitazione di un progetto e delle API Gmail.

### Passi nella Google Cloud Console:
1.  **Accesso**: Vai su [console.cloud.google.com](https://console.cloud.google.com).
2.  **Crea Progetto**: Clicca sul selettore dei progetti in alto e crea un **"Nuovo progetto"** (es. "SPARKS Mailer").
3.  **Abilita API**:
    *   Vai in **"API e servizi"** > **"Libreria"**.
    *   Cerca **"Gmail API"** e clicca su **Abilita**.
4.  **Schermata Consenso (OAuth Consent Screen)**:
    *   Vai in **"API e servizi"** > **"Schermata consenso OAuth"**.
    *   Scegli **User Type**: **External** (se usi un account @gmail.com) o **Internal** (se hai un dominio aziendale Workspace).
    *   Inserisci le info obbligatorie (Email supporto, Nome App, Email sviluppatore).
    *   **Scope**: Aggiungi lo scope `https://mail.google.com/` (necessario per IMAP/SMTP).
5.  **Crea Credenziali**:
    *   Vai in **"API e servizi"** > **"Credenziali"**.
    *   Clicca **"Crea credenziali"** > **"ID client OAuth"**.
    *   **Tipo applicazione**: **Applicazione Web**.
    *   **URIs di reindirizzamento autorizzati**: Inserisci l'indirizzo esatto che vedi in SPARKS:
        `https://amatucci.marco89.win/sparks/admin/smtp/oauth-callback`
    *   Clicca su **Crea**.
6.  **Ottieni ID**: Apparirà un popup con il tuo **Client ID** e **Client Secret**. Copiali subito.

### In SPARKS:
- **Provider**: Google
- **Client ID**: Incolla l'ID Cliente.
- **Client Secret**: Incolla il Segreto Cliente.

---

## 🛠️ Risoluzione Problemi comuni
- **Errore di Redirect**: Verifica che l'URL inserito in Azure/Google inizi con `https` e finisca esattamente con `/oauth-callback`.
- **Refresh Token**: Se SPARKS non riesce a rinnovare il token, verifica di aver aggiunto lo scope `offline_access` (per Microsoft) o che l'App non sia in "Testing" (per Google).

---
*Manuale di configurazione tecnico per SPARKS EQS v1.0.0*
